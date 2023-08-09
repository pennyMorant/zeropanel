<?php

namespace App\Controllers;

use App\Models\{
    User,
    Setting,
    InviteCode,
};
use App\Utils\Hash;
use App\Services\{
    Auth,
    Captcha
};
use Slim\Http\Response;
use Slim\Http\ServerRequest;
use Pkly\I18Next\I18n;
use voku\helper\AntiXSS;
use Exception;
use Ramsey\Uuid\Uuid;

class AuthController extends BaseController
{
    public function signInIndex(ServerRequest $request, Response $response, array $args)
    {
        $captcha = [];

        if (Setting::obtain('enable_signin_captcha') === true) {
            $captcha = Captcha::generate();
        }

        $this->view()
            ->assign('captcha', $captcha)
            ->display('auth/signin.tpl');
        return $response;
    }

    public function signinHandle(ServerRequest $request, Response $response, array $args)
    {
        $postdata = $request->getParsedBody();
        $email    = filter_var($postdata['email'], FILTER_VALIDATE_EMAIL);
        $passwd   = $postdata['passwd'];

        $trans = I18n::get();
        try {
            if (Setting::obtain('enable_signin_captcha') == true) {
                $ret = Captcha::verify($postdata);
                if (!$ret) {
                    throw new \Exception($trans->t('captcha failed'));
                }
            }

            $user = User::where('email', $email)->first();
            if (is_null($user)) {
                throw new \Exception($trans->t('email does not exist'));
            }

            if (!Hash::checkPassword($user->password, $passwd)) {
                // 记录登录失败
                $user->collectSigninIp($_SERVER['REMOTE_ADDR'], 1);
                throw new \Exception($trans->t('passwd error'));
            }
        } catch (\Exception $e) {
            return $response->withJson([
                'ret' => 0,
                'msg' => $e->getMessage(),
            ]);
        }
        Auth::login($user->id, 3600 * 24 * 7);
        $user->collectSigninIp($_SERVER['REMOTE_ADDR']);
        
        // 更新用户信息
        $user->last_signin_time = date('Y-m-d H:i:s');

        return $response->withJson([
            'ret' => 1,
            'msg' => $trans->t('signin success')
        ]);
    }

    public function signUpIndex(ServerRequest $request, Response $response, array $args)
    {
        if (Setting::obtain('reg_mode') == 'close') {
            $this->view()->display('auth/soon.tpl');
        } else {

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
            $email_suffixs = json_decode(Setting::obtain('limit_email_suffix'), true);
            $this->view()
                ->assign('code', $code)
                ->assign('base_url', Setting::obtain('website_url'))
                ->assign('email_suffixs', $email_suffixs)
                ->assign('captcha', $captcha)
                ->display('auth/signup.tpl');
        }
        return $response;
    }

    public function signUpHandle(ServerRequest $request, Response $response, array $args)
    {
        $postdata = $request->getParsedBody();
        $email    = filter_var($postdata['email'], FILTER_VALIDATE_EMAIL);
        $passwd   = $postdata['passwd'];
        $code     = $request->getParsedBodyParam('code');

        $trans = I18n::get();

        try {
            if (Setting::obtain('enable_signup_captcha') == true) {
                $ret = Captcha::verify($request->getParsedBody());
                if (!$ret) {
                    throw new \Exception($trans->t('captcha failed'));
                }
            }

            // check email
            $user = User::where('email', $email)->first();
            $email_suffix = json_decode(Setting::obtain('limit_email_suffix'), true);
            if (count(array_filter($email_suffix)) > 0) {
                if (!in_array(explode('@', $email)[1], $email_suffix)) {
                    throw new \Exception($trans->t('邮箱域名不支持'));
                }
            }
            if (!is_null($user)) {
                throw new \Exception($trans->t('email has been registered'));
            }

            if (empty($code) && Setting::obtain('reg_mode') === 'invite') {
                throw new \Exception($trans->t('referral code must be filled in'));
            }

            $c = InviteCode::where('code', $code)->first();
            if (!empty($code)) {
                if (is_null($c)) {
                    throw new \Exception($trans->t('referral code does not exist'));
                } else if ($c->user_id != 0) {
                    $gift_user = User::where('id', $c->user_id)->first();
                    if (is_null($gift_user)) {
                        throw new \Exception ($trans->t('referral code has expired'));
                    }
                }
            }
        } catch (\Exception $e) {
            return $response->withJson([
                'ret' => 0,
                'msg' => $e->getMessage(),
            ]);
        }
        
        $configs = Setting::getClass('register');
        // do reg user
        $user                       = new User();
        $antiXss                    = new AntiXSS();
        $current_timestamp          = time();
        $user->email                = $antiXss->xss_clean($email);
        $user->password             = Hash::passwordHash($passwd);
        $user->passwd               = $user->createShadowsocksPasswd();
        $user->uuid                 = Uuid::uuid5(Uuid::NAMESPACE_DNS, $email . '|' . $current_timestamp);
        $user->t                    = 0;
        $user->u                    = 0;
        $user->d                    = 0;
        $user->transfer_enable      = $configs['signup_default_traffic'] * 1024 * 1024 * 1024;
        $user->money                = 0;

        //dumplin：填写邀请人，写入邀请奖励
        $user->ref_by = 0;
        if (!is_null($c) && $c->user_id != 0) {
            $invitation = Setting::getClass('invite');
            // 设置新用户
            $user->ref_by = $c->user_id;
            $user->money = $invitation['invitation_to_signup_credit_reward'];
            // 给邀请人反流量
            $gift_user->transfer_enable += $invitation['invitation_to_signup_traffic_reward'] * 1024 * 1024 * 1024;
            $gift_user->save();
        }

        $user->class_expire     = date('Y-m-d H:i:s', time() + $configs['signup_default_class_time'] * 86400);
        $user->class            = $configs['signup_default_class'];
        $user->node_iplimit     = $configs['signup_default_ip_limit'];
        $user->node_speedlimit  = $configs['signup_default_speed_limit'];
        $user->signup_date      = date('Y-m-d H:i:s');
        $user->signup_ip        = $_SERVER['REMOTE_ADDR'];
        $user->theme            = $_ENV['theme'];
        $user->node_group       = 0;
        $user->subscription_token   = $user->createSubToken();
        $user->save();
        Auth::login($user->id, 3600);
        $user->collectSigninIp($_SERVER['REMOTE_ADDR']);

        return $response->withJson([
            'ret'   => 1,
            'msg'   => $trans->t('signup success'),
        ]);
    }
}
