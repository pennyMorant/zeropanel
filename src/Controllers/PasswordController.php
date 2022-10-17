<?php

namespace App\Controllers;

use App\Models\{
    User,
    PasswordReset,
    Setting
};
use App\Utils\Hash;
use App\Services\Password;
use Slim\Http\{
    Request,
    Response
};
use Pkly\I18Next\I18n;
/***
 * Class Password
 * @package App\Controllers
 * 密码重置
 */
class PasswordController extends BaseController
{
    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function handleReset($request, $response, $args)
    {
        $email = strtolower($request->getParam('email'));
        $user  = User::where('email', $email)->first();
        if ($user == null) {
            return $response->withJson([
                'ret' => 0,
                'msg' => I18n::get()->t('auth.notify.resetpasswd.error_email_not_exist')
            ]);
        }
        if (Password::sendResetEmail($email)) {
            $msg = I18n::get()->t('auth.notify.resetpasswd.success_send_email_code');
        } else {
            $msg = I18n::get()->t('auth.notify.resetpasswd.success_send_email_fail');;
        }
        return $response->withJson([
            'ret' => 1,
            'msg' => $msg
        ]);
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function token($request, $response, $args)
    {
        $token = PasswordReset::where('token', $args['token'])->where('expire_time', '>', time())->orderBy('id', 'desc')->first();
        if ($token == null) return $response->withStatus(302)->withHeader('Location', '/password/reset');
        
        $this->view()->display('password/token.tpl');
        return $response;
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function handleToken($request, $response, $args)
    {
        $tokenStr = $args['token'];
        $password = $request->getParam('password');
        $repasswd = $request->getParam('repasswd');

        if ($password != $repasswd) {
            return $response->withJson([
                'ret' => 0,
                'msg' => I18n::get()->t('auth.notify.resetpasswd.error_passwd_not_same')
            ]);
        }

        if (strlen($password) < 8) {
            return $response->withJson([
                'ret' => 0,
                'msg' => I18n::get()->t('auth.notify.resetpasswd.error_passwd_too_short')
            ]);
        }

        // check token
        $token = PasswordReset::where('token', $tokenStr)->where('expire_time', '>', time())->orderBy('id', 'desc')->first();
        if ($token == null) {
            return $response->withJson([
                'ret' => 0,
                'msg' => I18n::get()->t('auth.notify.resetpasswd.error_reset_url_invalid')
            ]);
        }
        /** @var PasswordReset $token */
        $user = $token->getUser();
        if ($user == null) {
            return $response->withJson([
                'ret' => 0,
                'msg' => I18n::get()->t('auth.notify.resetpasswd.error_reset_url_invalid')
            ]);
        }

        // reset password
        $hashPassword    = Hash::passwordHash($password);
        $user->password      = $hashPassword;

        if (!$user->save()) {
            $rs['ret'] = 0;
            $rs['msg'] = I18n::get()->t('auth.notify.resetpasswd.error_resetpasswd_fail');
        } else {
            $rs['ret'] = 1;
            $rs['msg'] = I18n::get()->t('auth.notify.resetpasswd.success_resetpasswd');
            
            if (Setting::obtain('enable_subscribe_change_token_when_change_passwd') == true) {
                $user->clean_link();
            }

            // 禁止链接多次使用
            $token->expire_time = time();
            $token->save();
        }

        return $response->withJson($rs);
    }
}