<?php

namespace App\Controllers;

use App\Services\Auth;
use App\Services\Mail;
use App\Models\{
    User,
    Setting,
    InviteCode,
    Token,
    Knowledge
};
use App\Utils\Hash;
use voku\helper\AntiXSS;
use Slim\Http\Response;
use Slim\Http\ServerRequest;
use Pkly\I18Next\I18n;

class UserController extends BaseController
{
    public function index(ServerRequest $request, Response $response, array $args)
    {       
        $code = InviteCode::where('user_id', $this->user->id)->first();
        if (is_null($code)) {
            $this->user->addInviteCode();
            $code = InviteCode::where('user_id', $this->user->id)->first();
        }
        $invite_url = Setting::obtain('website_url') . '/auth/signup?code=' . $code->code;
        $sub_url = Setting::obtain('subscribe_address_url') . "/api/v1/client/subscribe?token={$this->user->subscription_token}";
        $androidClients = Knowledge::where('platform', 'android')->get();
        $iosClients = Knowledge::where('platform', 'ios')->get();
        $windowsClients = Knowledge::where('platform', 'windows')->get();
        $macosClients = Knowledge::where('platform', 'macos')->get();
        $this->view()
            ->assign('invite_url', $invite_url)
            ->assign('subInfo', $sub_url)
            ->assign('androidClients', $androidClients)
            ->assign('iosClients', $iosClients)
            ->assign('windowsClients', $windowsClients)
            ->assign('macosClients', $macosClients)
            ->display('user/index.tpl');
        return $response;
    }
    
    public function knowledge(ServerRequest $request, Response $response, array $args)
    {
        $opts           = $request->getQueryParams();
        $opts['os']     = str_replace(' ','',$opts['os']);
        $opts['client'] = str_replace(' ','',$opts['client']);
        $dir = dirname(__DIR__, 2)."/resources/views/zero/user/knowledge/{$opts['os']}/{$opts['client']}.tpl";
        if (!file_exists($dir)) {
            $template = file_get_contents(dirname(__DIR__, 2)."/resources/views/zero/user/knowledge/template.tpl");
            file_put_contents($dir, $template);
        }
        $knowledges = Knowledge::where('client', $opts['client'])->where('platform', $opts['os'])->get();
        $sub_url = Setting::obtain('subscribe_address_url') . "/api/v1/client/subscribe?token={$this->user->subscription_token}";
        if ($opts['os'] != '' && $opts['client'] != '') {
            $url = 'user/knowledge/'.$opts['os'].'/'.$opts['client'].'.tpl';
            $this->view()
                ->assign('subInfo', $sub_url)
                ->assign('knowledges', $knowledges)
                ->display($url);
        }
        return $response;
    }

    public function profile(ServerRequest $request, Response $response, array $args)
    {
        $tg_bind_token = Token::where('user_id', $this->user->id)->where('expired_at', '>', time())
                        ->where('type', 1)->value('token');
        if (is_null($tg_bind_token)) {
            $tg_bind_token = Token::createToken($this->user, 32, 1);
        }
        $this->view()
            ->assign('bind_token', $tg_bind_token)
            ->assign('telegram_bot_id', Setting::obtain('telegram_bot_id'))
            ->display('user/profile.tpl');
        return $response;
    }

    public function referral(ServerRequest $request, Response $response, array $args)
    {
        $code = InviteCode::where('user_id', $this->user->id)->first();
        if (is_null($code)) {
            $this->user->addInviteCode();
            $code = InviteCode::where('user_id', $this->user->id)->first();
        }
        $referred_user = User::where('ref_by', $this->user->id)->count();
        $invite_url    = Setting::obtain('website_url') . '/signup?ref=' . $code->code;
        $this->view()
            ->assign('code', $code)
            ->assign('referred_user', $referred_user)
            ->assign('referral_url', $invite_url)
            ->display('user/referral.tpl');
        return $response;
    }
    
    public function enableNotify(ServerRequest $request, Response $response, array $args)
    {
        $type = $request->getParsedBodyParam('notify_type');
       
        $user = $this->user;
        if ($type == 'telegram' && Setting::obtain('enable_telegram_bot') == false) {
            $res['ret'] = 0;
            $res['msg'] = '系统未启用Telegram Bot';
            return $response->withJson($res);
        } else if ($type == 'telegram' && is_null($user->telegram_id)) {
            $res['ret'] = 0;
            $res['msg'] = '您还未绑定telegram账户';
            return $response->withJson($res);
        }
        $user->notify_type = $type;
        $user->save();
        $res['ret'] = 1;
        $res['msg'] = '成功';
        return $response->withJson($res);
    }

    public function updateProfile(ServerRequest $request, Response $response, array $args)
    {
        $type = $args['type'];
        $user = $this->user;
        switch ($type) {
            case 'password':
                $current_password = $request->getParsedBodyParam('current_password');
                $new_password     = $request->getParsedBodyParam('new_password');
                try {
                    if (!Hash::checkPassword($user->password, $current_password)) {  
                        throw new \Exception(I18n::get()->t('passwd error'));
                    }
                    $hashPassword   = Hash::passwordHash($new_password);
                    $user->password = $hashPassword;
                    $user->save();
                } catch (\Exception $e) {
                    return $response->withJson([
                        'ret' => 0,
                            //'msg' => $e->getFile() . $e->getLine() . $e->getMessage(),
                        'msg' => $e->getMessage(),
                    ]);
                }
                break;
            case 'email':
                $newemail  = $request->getParsedBodyParam('newemail');
                $oldemail  = $user->email;
                $otheruser = User::where('email', $newemail)->first();
                try {                 
                    if (empty($newemail)) {
                        throw new \Exception(I18n::get()->t('blank is not allowed'));
                    }
                    
                    if (!is_null($otheruser)) {
                        throw new \Exception(I18n::get()->t('email has been registered'));
                    }
                    
                    if ($newemail == $oldemail) {
                        throw new \Exception(I18n::get()->t('can not be the same as the current email'));
                    }
                } catch (\Exception $e) {
                    return $response->withJson([
                        'ret' => 0,
                        'msg' => $e->getMessage(),
                    ]);
                }
                $antiXss = new AntiXSS();
                $user->email = $antiXss->xss_clean($newemail);
                $user->save();
                break;
            case 'uuid':
                $current_timestamp = time();
                $user->uuid = $user->createUUID($current_timestamp);
                $user->save();
                break;
            case 'passwd':               
                $user->passwd = $user->createShadowsocksPasswd();
                $user->save();
                break;
            case 'sub_token':
                $user->subscription_token = $user->createSubToken();
                $user->save();
                break;
            case 'referral_code':
                $user->clearInviteCode();
                break;
            case 'unbind_telegram':
                $user->unbindTelegram();
                break;
            default:
                return 0;
                break;
        }

        return $response->withJson([
            'ret' => 1,
            'msg' => I18n::get()->t('success')
        ]);
    }

    public function handleKill(ServerRequest $request, Response $response, array $args)
    {
        $user = $this->user;
        $passwd = $request->getParsedBodyParam('passwd');
        // check passwd
        $res = array();
        if (!Hash::checkPassword($user->password, $passwd)) {
            $res['ret'] = 0;
            $res['msg'] = I18n::get()->t('passwd error');
            return $response->withJson($res);
        }

        Auth::logout();
        $user->deleteUser();
        $res['ret'] = 1;
        $res['msg'] = I18n::get()->t('success');
        return $response->withJson($res);
    }

    
    public function record(ServerRequest $request, Response $response, array $args)
    {
        $this->view()
            ->display('user/record.tpl');
        return $response;
    }
    
    public function ban(ServerRequest $request, Response $response, array $args)
    {
        $this->view()
            ->display('user/ban.tpl');
        return $response;
    }

    public function logout(ServerRequest $request, Response $response, array $args)
    {
        Auth::logout();
        return $response
            ->withStatus(302)
            ->withHeader('Location', '/');
    }

    public function verifyEmail(ServerRequest $request, Response $response, array $args)
    {
        $action = $args['action'];
        switch ($action) {
            case 'send':    
                $user   = $this->user;       
                $token   = Token::createToken($user, 64, 3);
                $subject = Setting::obtain('website_name') . '邮箱验证';
                $url     = Setting::obtain('website_url') . '/user/verify/email/check?token=' . $token;

                Mail::send(
                    $user->email,
                    $subject,
                    'auth/verify.tpl',
                    [
                        'url' => $url
                    ],
                    []
                );
                return $response->withJson([
                    'ret'   =>  1,
                    'msg'   =>  '验证邮件发送成功'
                ]);
                break;
            case 'check':
                if ($this->user && $this->user->verified == 1) {
                    return $response->withHeader('Location', '/user/dashboard');
                }
                $token_str = $request->getQueryParam('token');
                $token = Token::where('token', $token_str)->where('type', 3)->where('expired_at', '>', time())->orderBy('expired_at', 'desc')->first();
                if (is_null($token)) {
                    $this->view()
                        ->assign('verification_result', 'false')
                        ->display('user/verify.tpl');
                    return $response;
                }
                $user = User::find($token->user_id);
                $user->verified = 1;
                $user->save();
                $this->view()
                    ->assign('verification_result', 'true')
                    ->display('user/verify.tpl');
                return $response;
                break;
        }
    }
}
