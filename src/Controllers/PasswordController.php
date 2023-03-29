<?php

namespace App\Controllers;

use App\Models\{
    User,
    PasswordReset,
    Setting
};
use App\Utils\Hash;
use App\Services\Password;
use Slim\Http\Response;
use Slim\Http\ServerRequest;
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
    public function reset(ServerRequest $request, Response $response, $args)
    {
        $this->view()->display('password/reset.tpl');
        return $response;
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function handleReset(ServerRequest $request, Response $response, $args)
    {
        $email = strtolower($request->getParam('email'));
        $user  = User::where('email', $email)->first();
        if (is_null($user)) {
            return $response->withJson([
                'ret' => 0,
                'msg' => I18n::get()->t('email does not exist')
            ]);
        }
        if (Password::sendResetEmail($email)) {
            $msg = I18n::get()->t('email sending success');
        } else {
            $msg = I18n::get()->t('email sending failed');;
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
    public function token(ServerRequest $request, Response $response, $args)
    {
        $token = PasswordReset::where('token', $args['token'])->where('expire_time', '>', time())->orderBy('id', 'desc')->first();
        if (is_null($token)) return $response->withStatus(302)->withHeader('Location', '/password/reset');
        
        $this->view()->display('password/token.tpl');
        return $response;
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function handleToken(ServerRequest $request, Response $response, $args)
    {
        $tokenStr = $args['token'];
        $password = $request->getParam('password');
        $repassword = $request->getParam('repassword');

        // check token
        $token = PasswordReset::where('token', $tokenStr)->where('expire_time', '>', time())->orderBy('id', 'desc')->first();
        if (is_null($token)) {
            return $response->withJson([
                'ret' => 0,
                'msg' => I18n::get()->t('link is dead')
            ]);
        }
        /** @var PasswordReset $token */
        $user = $token->getUser();
        if (is_null($user)) {
            return $response->withJson([
                'ret' => 0,
                'msg' => I18n::get()->t('link is dead')
            ]);
        }

        // reset password
        $hashPassword    = Hash::passwordHash($password);
        $user->password      = $hashPassword;

        if (!$user->save()) {
            $rs['ret'] = 0;
            $rs['msg'] = I18n::get()->t('failed');
        } else {
            $rs['ret'] = 1;
            $rs['msg'] = I18n::get()->t('success');

            // 禁止链接多次使用
            $token->expire_time = time();
            $token->save();
        }

        return $response->withJson($rs);
    }
}