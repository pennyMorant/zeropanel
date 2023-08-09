<?php

namespace App\Controllers;

use App\Models\{
    User,
    Setting
};
use App\Utils\Hash;
use App\Models\Token;
use App\Services\Mail;
use Slim\Http\Response;
use Slim\Http\ServerRequest;
use Pkly\I18Next\I18n;

class PasswordController extends BaseController
{
    public function resetIndex(ServerRequest $request, Response $response, array $args)
    {
        $this->view()->display('password/reset.tpl');
        return $response;
    }

    public function handleReset(ServerRequest $request, Response $response, array $args)
    {
        $email = strtolower($request->getParsedBodyParam('email'));
        $user  = User::where('email', $email)->first();
        if (is_null($user)) {
            return $response->withJson([
                'ret' => 0,
                'msg' => I18n::get()->t('email does not exist')
            ]);
        }
        $token   = Token::createToken($user, 64, 3);
        $subject = Setting::obtain('website_name') . '重置密码';
        $url     = Setting::obtain('website_url') . '/password/token?token=' . $token;

        Mail::send(
            $user->email,
            $subject,
            'password/reset.tpl',
            [
                'url' => $url
            ],
            []
        );
        return $response->withJson([
            'ret'   =>  1,
            'msg'   =>  '验证邮件发送成功'
        ]);
    }

    public function tokenIndex(ServerRequest $request, Response $response, array $args)
    {
        $token = Token::where('token', $request->getQueryParam('token'))->where('expired_at', '>', time())->first();
        if (is_null($token)) return $response->withStatus(302)->withHeader('Location', '/password/reset');

        $this->view()
            ->assign('token', $request->getQueryParam('token'))
            ->display('password/token.tpl');
        return $response;
    }

    public function handleToken(ServerRequest $request, Response $response, array $args)
    {
        $tokenStr   = $request->getParsedBodyParam('token');
        $password   = $request->getParsedBodyParam('password');
        $repassword = $request->getParsedBodyParam('repassword');

        // check token
        $token = Token::where('token', $tokenStr)->where('expired_at', '>', time())->first();
        if (is_null($token)) {
            return $response->withJson([
                'ret' => 0,
                'msg' => I18n::get()->t('link is dead')
            ]);
        }

        $user = User::find($token->user_id);
        if (is_null($user)) {
            return $response->withJson([
                'ret' => 0,
                'msg' => I18n::get()->t('link is dead')
            ]);
        }

        // reset password
        $hashPassword        = Hash::passwordHash($password);
        $user->password      = $hashPassword;

        if (!$user->save()) {
            $rs['ret'] = 0;
            $rs['msg'] = I18n::get()->t('failed');
        } else {
            $rs['ret'] = 1;
            $rs['msg'] = I18n::get()->t('success');

            // 禁止链接多次使用
            $token->expired_at = time();
            $token->save();
        }

        return $response->withJson($rs);
    }
}