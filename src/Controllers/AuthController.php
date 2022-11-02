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
    public function login($request, $response, $args)
    {
        $captcha = Captcha::generate();

        $ary  = $request->getQueryParams();
        $code = '';
        if (isset($ary['code'])) {
            $antiXss = new AntiXSS();
            $code    = $antiXss->xss_clean($ary['code']);
        }

        if (Setting::obtain('enable_telegram_bot') == true) {
            $login_text   = TelegramSessionManager::add_login_session();
            $login        = explode('|', $login_text);
            $login_token  = $login[0];
            $login_number = $login[1];
        } else {
            $login_token  = '';
            $login_number = '';
        }
        
        if (Setting::obtain('enable_login_captcha') == true) {
            $geetest_html = $captcha['geetest'];
        } else {
            $geetest_html = null;
        }

        $this->view()
            ->assign('geetest_html', $geetest_html)
            ->assign('login_token', $login_token)
            ->assign('login_number', $login_number)
            ->assign('telegram_bot_id', Setting::obtain('telegram_bot_id'))
            ->assign('code', $code)
            ->assign('base_url', Setting::obtain('website_general_url'))
            ->assign('recaptcha_sitekey', $captcha['recaptcha'])
            ->assign('enable_email_verify', Setting::obtain('reg_email_verify'))
            ->display('auth/signin.tpl');
        return $response;
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function getCaptcha($request, $response, $args)
    {
        $captcha = Captcha::generate();
        return $response->withJson([
            'recaptchaKey' => $captcha['recaptcha'],
            'GtSdk'        => $captcha['geetest'],
            'respon'       => 1,
        ]);
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function loginHandle($request, $response, $args)
    {
        $email = strtolower(trim($request->getParam('email')));
        $passwd     = $request->getParam('passwd');
        $code       = $request->getParam('code');
        $rememberMe = $request->getParam('remember_me');

        $trans = I18n::get();
        if (Setting::obtain('enable_login_captcha') == true) {
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
            $user->collectLoginIP($_SERVER['REMOTE_ADDR'], 1);
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
        $user->collectLoginIP($_SERVER['REMOTE_ADDR']);
        $user->last_signin_time = date('Y-m-d H:i:s');
        $user->save();

        return $response->withJson([
            'ret' => 1,
            'msg' => $trans->t('auth.notify.signin.success')
        ]);
    }

    public function qrcode_loginHandle($request, $response, $args)
    {
        // $data = $request->post('sdf');
        $token  = $request->getParam('token');
        $number = $request->getParam('number');

        $ret = TelegramSessionManager::step2_verify_login_session($token, $number);
        if ($ret === 0) {
            return $response->withJson([
                'ret' => 0,
                'msg' => '此令牌无法被使用。'
            ]);
        }

        $user = User::find($ret);

        Auth::login($user->id, 3600 * 24);
        // 记录登录成功
        $user->collectLoginIP($_SERVER['REMOTE_ADDR']);

        return $response->withJson([
            'ret' => 1,
            'msg' => '登录成功'
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
        $captcha = Captcha::generate();

        $ary  = $request->getQueryParams();
        $code = '';
        if (isset($ary['code'])) {
            $antiXss = new AntiXSS();
            $code    = $antiXss->xss_clean($ary['code']);
        }

        if (Setting::obtain('enable_telegram_bot') == true) {
            $login_text   = TelegramSessionManager::add_login_session();
            $login        = explode('|', $login_text);
            $login_token  = $login[0];
            $login_number = $login[1];
        } else {
            $login_token  = '';
            $login_number = '';
        }
        
        if (Setting::obtain('enable_login_captcha') == true) {
            $geetest_html = $captcha['geetest'];
        } else {
            $geetest_html = null;
        }
        if (Setting::obtain('reg_mode')  == 'close') {
            $this->view()
                ->display('auth/soon.tpl');
            return $response;
        }
        $this->view()
            ->assign('geetest_html', $geetest_html)
            ->assign('login_token', $login_token)
            ->assign('login_number', $login_number)
            ->assign('telegram_bot_id', Setting::obtain('telegram_bot_id'))
            ->assign('code', $code)
            ->assign('base_url', Setting::obtain('website_general_url'))
            ->assign('recaptcha_sitekey', $captcha['recaptcha'])
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

        $user->name                 = $antiXss->xss_clean($name);
        $user->email                = $email;
        $user->password             = Hash::passwordHash($passwd);
        $user->passwd               = Tools::genRandomChar(16);
        $user->uuid                 = Uuid::uuid5(Uuid::NAMESPACE_DNS, $email . '|' . $current_timestamp);
        $user->t                    = 0;
        $user->u                    = 0;
        $user->d                    = 0;
        $user->method               = $configs['sign_up_for_method'];
        $user->protocol             = $configs['sign_up_for_protocol'];
        $user->protocol_param       = $configs['sign_up_for_protocol_param'];
        $user->obfs                 = $configs['sign_up_for_obfs'];
        $user->obfs_param           = $configs['sign_up_for_obfs_param'];
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
        $user->expire_in        = date('Y-m-d H:i:s', time() + $configs['sign_up_for_free_time'] * 86400);
        $user->reg_date         = date('Y-m-d H:i:s');
        $user->reg_ip           = $_SERVER['REMOTE_ADDR'];
        $user->theme            = $_ENV['theme'];
        $user->node_group       = 0;

        if ($user->save()) {
            Auth::login($user->id, 3600);
            // 记录登录成功
            $user->collectLoginIP($_SERVER['REMOTE_ADDR']);
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

        if (Setting::obtain('enable_reg_captcha') == true) {
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

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function qrcode_check($request, $response, $args)
    {
        $token  = $request->getParam('token');
        $number = $request->getParam('number');
        $user   = Auth::getUser();
        if ($user->isLogin) {
            return $response->withJson([
                'ret' => 0
            ]);
        }

        if (Setting::obtain('enable_telegram_bot') == true) {
            $ret = TelegramSessionManager::check_login_session($token, $number);
            $res['ret'] = $ret;
            return $response->withJson($res);
        }

        return $response->withJson([
            'ret' => 0
        ]);
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function telegram_oauth($request, $response, $args)
    {
        if (Setting::obtain('enable_telegram_bot') == true) {
            $auth_data = $request->getQueryParams();
            if ($this->telegram_oauth_check($auth_data) === true) { // Looks good, proceed.
                $telegram_id = $auth_data['id'];
                $user        = User::where('telegram_id', $telegram_id)->first(); // Welcome Back :)
                if ($user == null) {
                    return $this->view()
                        ->assign('title', '您需要先进行邮箱注册后绑定Telegram才能使用授权登录')
                        ->assign('message', '很抱歉带来的不便，请重新试试')
                        ->assign('redirect', '/auth/login')
                        ->display('telegram_error.tpl');
                }
                Auth::login($user->id, 3600);
                // 记录登录成功
                $user->collectLoginIP($_SERVER['REMOTE_ADDR']);

                // 登陆成功！
                return $this->view()
                    ->assign('title', '登录成功')
                    ->assign('message', '正在前往仪表盘')
                    ->assign('redirect', '/user')
                    ->display('telegram_success.tpl');
            }
            // 验证失败
            return $this->view()
                ->assign('title', '登陆超时或非法构造信息')
                ->assign('message', '很抱歉带来的不便，请重新试试')
                ->assign('redirect', '/auth/login')
                ->display('telegram_error.tpl');
        }
        return $response->withRedirect('/404');
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    private function telegram_oauth_check($auth_data)
    {
        $check_hash = $auth_data['hash'];
        $bot_token  = Setting::obtain('telegram_bot_token');
        unset($auth_data['hash']);
        $data_check_arr = [];
        foreach ($auth_data as $key => $value) {
            $data_check_arr[] = $key . '=' . $value;
        }
        sort($data_check_arr);
        $data_check_string = implode("\n", $data_check_arr);
        $secret_key        = hash('sha256', $bot_token, true);
        $hash              = hash_hmac('sha256', $data_check_string, $secret_key);
        if (strcmp($hash, $check_hash) !== 0) {
            return false; // Bad Data :(
        }

        if ((time() - $auth_data['auth_date']) > 300) { // Expire @ 5mins
            return false;
        }

        return true; // Good to Go
    }
}
