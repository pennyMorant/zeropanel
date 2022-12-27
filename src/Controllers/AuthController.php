<?php

namespace App\Controllers;

use App\Models\{
    User,
    Setting,
    InviteCode,
    EmailVerify
};
use App\Utils\{
    GA,
    Hash,
    Check,
    Tools,
    TelegramSessionManager
};
use App\Services\{
    Auth,
    Mail,
    Captcha
};
use Slim\Http\{
    Request,
    Response
};
use Pkly\I18Next\I18n;
use voku\helper\AntiXSS;
use Exception;
use Ramsey\Uuid\Uuid;

/**
 *  AuthController
 */
class AuthController extends BaseController
{
    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function signin($request, $response, $args)
    {
        $captcha = [];

        if (Setting::obtain('enable_signin_captcha') === true) {
            $captcha = Captcha::generate();
        }

        $this->view()
            ->assign('base_url', Setting::obtain('website_general_url'))
            ->assign('captcha', $captcha)
            ->assign('enable_email_verify', Setting::obtain('reg_email_verify'))
            ->display('auth/signin.tpl');
        return $response;
    }

    

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function signinHandle($request, $response, $args)
    {
        $email = strtolower(trim($request->getParam('email')));
        $passwd     = $request->getParam('passwd');
        $code       = $request->getParam('code');
        $rememberMe = $request->getParam('remember_me');

        $trans = I18n::get();
        if (Setting::obtain('enable_signin_captcha') == true) {
            $ret = Captcha::verify($request->getParams());
            if (!$ret) {
                return $response->withJson([
                    'ret' => 0,
                    'msg' => $trans->t('auth.notify.captcha.error')
                ]);
            }
        }

        $user = User::where('email', $email)->first();
        if ($user == null) {
            return $response->withJson([
                'ret' => 0,
                'msg' => $trans->t('auth.notify.signin.error_email')
            ]);
        }

        if (!Hash::checkPassword($user->password, $passwd)) {
            // 记录登录失败
            $user->collectSigninIp($_SERVER['REMOTE_ADDR'], 1);
            return $response->withJson([
                'ret' => 0,
                'msg' => $trans->t('auth.notify.signin.error_passwd')
            ]);
        }

        $time = 3600 * 24;
        if ($rememberMe) {
            $time = 3600 * 24 * ($_ENV['rememberMeDuration'] ?: 7);
        }

        Auth::login($user->id, $time);
        // 记录登录成功
        $user->collectSigninIp($_SERVER['REMOTE_ADDR']);
        $user->last_signin_time = date('Y-m-d H:i:s');
        $user->save();

        return $response->withJson([
            'ret' => 1,
            'msg' => $trans->t('auth.notify.signin.success')
        ]);
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function sendVerify($request, $response, $next)
    {

        $trans = I18n::get();

        if (Setting::obtain('reg_email_verify')) {
            $email = trim($request->getParam('email'));
            $email = strtolower($email);

            if ($email == '') {
                return $response->withJson([
                    'ret' => 0,
                    'msg' => $trans->t('auth.notify.email.error_no_email')
                ]);
            }
            
            // check email format
            $check_res = Check::isEmailLegal($email);
            if ($check_res['ret'] == 0) {
                return $response->withJson($check_res);
            }

            $user = User::where('email', $email)->first();
            if ($user != null) {
                return $response->withJson([
                    'ret' => 0,
                    'msg' => $trans->t('auth.notify.email.error_used_email')
                ]);
            }

            $ipcount = EmailVerify::where('ip', '=', $_SERVER['REMOTE_ADDR'])
                ->where('expire_in', '>', time())
                ->count();
            if ($ipcount >= Setting::obtain('email_verify_ip_limit')) {
                return $response->withJson([
                    'ret' => 0,
                    'msg' => $trans->t('auth.notify.email.error_ip')
                ]);
            }

            $mailcount = EmailVerify::where('email', '=', $email)
            ->where('expire_in', '>', time())
            ->count();
            if ($mailcount >= 3) {
                return $response->withJson([
                    'ret' => 0,
                    'msg' => $trans->t('auth.notify.email.error_more_times')
                ]);
            }

            $code          = Tools::genRandomNum(6);
            $ev            = new EmailVerify();
            $ev->expire_in = time() + Setting::obtain('email_verify_ttl');
            $ev->ip        = $_SERVER['REMOTE_ADDR'];
            $ev->email     = $email;
            $ev->code      = $code;
            $ev->save();

            try {
                Mail::send(
                    $email,
                    Setting::obtain('website_general_name') . '- 验证邮件',
                    'auth/verify.tpl',
                    [
                        'code' => $code,
                        'expire' => date('Y-m-d H:i:s', time() + Setting::obtain('email_verify_ttl'))
                    ],
                    []
                );
            } catch (Exception $e) {
                return $response->withJson([
                    'ret' => 0,
                    'msg' => $trans->t('auth.notify.email.error_sendcode')
                ]);
            }

            return $response->withJson([
                'ret' => 1,
                'msg' => $trans->t('auth.notify.email.success')
            ]);
        }
        return $response->withJson([
            'ret' => 0,
            'msg' => ''
        ]);
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function signUp($request, $response, $args)
    {
        $captcha = [];

        if (Setting::obtain('enable_signup_captcha') === true) {
            $captcha = Captcha::generate();
        }

        $ary  = $request->getQueryParams();
        $code = '';
        if (isset($ary['code'])) {
            $antiXss = new AntiXSS();
            $code    = $antiXss->xss_clean($ary['code']);
        }
        
        if (Setting::obtain('reg_mode')  == 'close') {
            $this->view()
                ->display('auth/soon.tpl');
            return $response;
        }
        $this->view()
            ->assign('code', $code)
            ->assign('base_url', Setting::obtain('website_general_url'))
            ->assign('captcha', $captcha)
            ->assign('enable_email_verify', Setting::obtain('reg_email_verify'))
            ->display('auth/signup.tpl');
        return $response;
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function register_helper($name, $email, $passwd, $code, $telegram_id)
    {
        $trans = I18n::get();
        if (Setting::obtain('reg_mode') == 'close') {
            $res['ret'] = 0;
            $res['msg'] = $trans->t('auth.notify.error_not_open');
            return $res;
        }
        
        if ($code == '' && Setting::obtain('reg_mode') === 'invite') {
            $res['ret'] = 0;
            $res['msg'] = $trans->t('auth.notify.signup.error_need_invite_code');
            return $res;
        }

        $c = InviteCode::where('code', $code)->first();
        if ($code !== '') {
            if ($c == null) {
                $res['ret'] = 0;
                $res['msg'] = $trans->t('auth.notify.signup.error_unknown_invite_code');
                return $res;
            } else if ($c->user_id != 0) {
                $gift_user = User::where('id', $c->user_id)->first();
                if ($gift_user == null) {
                    $res['ret'] = 0;
                    $res['msg'] = $trans->t('auth.notify.signup.error_expired_invite_code');
                    return $res;
                }
            }
        }
        
        $configs = Setting::getClass('register');
        // do reg user
        $user                       = new User();
        $antiXss                    = new AntiXSS();
        $current_timestamp          = time();
        $user->email                = $email;
        $user->password             = Hash::passwordHash($passwd);
        $user->passwd               = Tools::genRandomChar(16);
        $user->uuid                 = Uuid::uuid5(Uuid::NAMESPACE_DNS, $email . '|' . $current_timestamp);
        $user->t                    = 0;
        $user->u                    = 0;
        $user->d                    = 0;
        $user->transfer_enable      = Tools::toGB($configs['sign_up_for_free_traffic']);
        $user->money                = 0;

        //dumplin：填写邀请人，写入邀请奖励
        $user->ref_by = 0;
        if ($c != null && $c->user_id != 0) {
            $invitation = Setting::getClass('invite');
            // 设置新用户
            $user->ref_by = $c->user_id;
            $user->money = $invitation['invitation_to_register_balance_reward'];
            // 给邀请人反流量
            $gift_user->transfer_enable += $invitation['invitation_to_register_traffic_reward'] * 1024 * 1024 * 1024;
            $gift_user->save();
        }
        
        if ($telegram_id) {
            $user->telegram_id = $telegram_id;
        }

        $user->class_expire     = date('Y-m-d H:i:s', time() + $configs['sign_up_for_class_time'] * 86400);
        $user->class            = $configs['sign_up_for_class'];
        $user->node_connector   = $configs['connection_device_limit'];
        $user->node_speedlimit  = $configs['connection_rate_limit'];
        $user->signup_date         = date('Y-m-d H:i:s');
        $user->signup_ip           = $_SERVER['REMOTE_ADDR'];
        $user->theme            = $_ENV['theme'];
        $user->node_group       = 0;

        if ($user->save()) {
            Auth::login($user->id, 3600);
            // 记录登录成功
            $user->collectSigninIp($_SERVER['REMOTE_ADDR']);
            $user->sendMail(
                '',
                'news/welcome.tpl',
                [],
                [],
                $_ENV['email_queue']
            );
            $res['ret'] = 1;
            $res['msg'] = $trans->t('auth.notify.signup.success');

            return $res;
        }
        $res['ret'] = 0;
        $res['msg'] = '未知错误';
        return $res;
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function registerHandle($request, $response, $args)
    {
        $name = $request->getParam('name');
        $email = $request->getParam('email');
        $email = trim($email);
        $email = strtolower($email);
        $passwd = $request->getParam('passwd');
        $repasswd = $request->getParam('repasswd');
        $code = trim($request->getParam('code'));

        $trans = I18n::get();

        if (Setting::obtain('reg_mode') == 'close') {
            $res['ret'] = 0;
            $res['msg'] = $trans->t('auth.notify.signup.error_not_open');
            return $response->withJson($res);
        }

        if (Setting::obtain('enable_signup_captcha') == true) {
            $ret = Captcha::verify($request->getParams());
            if (!$ret) {
                return $response->withJson([
                    'ret' => 0,
                    'msg' => $trans->t('auth.notify.captcha.error')
                ]);
            }
        }

        // check email format
        $check_res = Check::isEmailLegal($email);
        if ($check_res['ret'] == 0) {
            return $response->withJson($check_res);
        }

        // check email
        $user = User::where('email', $email)->first();
        if ($user != null) {
            return $response->withJson([
                'ret' => 0,
                'msg' => $trans->t('auth.notify.email.error_used_email')
            ]);
        }

        if (Setting::obtain('reg_email_verify')) {
            $email_code = trim($request->getParam('emailcode'));
            $mailcount = EmailVerify::where('email', '=', $email)
                ->where('code', '=', $email_code)
                ->where('expire_in', '>', time())
                ->first();
            if ($mailcount == null) {
                return $response->withJson([
                    'ret' => 0,
                    'msg' => $trans->t('auth.notify.signup.error_emailcode')
                ]);
            }
        }

        // check pwd length
        if (strlen($passwd) < 8) {
            return $response->withJson([
                'ret' => 0,
                'msg' => '密码请大于8位'
            ]);
        }

        // check pwd re
        if ($passwd != $repasswd) {
            return $response->withJson([
                'ret' => 0,
                'msg' => '两次密码输入不符'
            ]);
        }
        if (Setting::obtain('reg_email_verify')) {
            EmailVerify::where('email', $email)->delete();
        }
        
        return $response->withJson(
            $this->register_helper($name, $email, $passwd, $code, 0)
        );
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function logout($request, $response, $next)
    {
        Auth::logout();
        return $response
            ->withStatus(302)
            ->withHeader('Location', '/');
    }
}
