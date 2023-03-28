<?php

namespace App\Controllers;

use App\Services\{
    Auth,
    Config,
    Captcha
};
use App\Models\{
    Ann,
    Code,
    User,
    Payback,
    Setting,
    InviteCode,
    EmailVerify
};
use App\Utils\{
    Check,
    URL,
    Hash,
    Tools,
    Cookie,
    TelegramSessionManager
};
use Exception;
use voku\helper\AntiXSS;
use Slim\Http\Response;
use Slim\Http\ServerRequest;
use Pkly\I18Next\I18n;

/**
 *  HomeController
 */
class UserController extends BaseController
{
    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function index(ServerRequest $request, Response $response, $args)
    {       
        $code = InviteCode::where('user_id', $this->user->id)->first();
        if ($code == null) {
            $this->user->addInviteCode();
            $code = InviteCode::where('user_id', $this->user->id)->first();
        }
        $invite_url = Setting::obtain('website_url') . '/auth/signup?code=' . $code->code;
        $class_left_days = floor((strtotime($this->user->class_expire)-time())/86400)+1;

        $this->view()
            ->assign('class_left_days', $class_left_days)
            ->assign('anns', Ann::where('date', '>=', date('Y-m-d H:i:s', time() - 7 * 86400))->orderBy('date', 'desc')->get())
            ->assign('invite_url', $invite_url)
            ->registerClass('URL', URL::class)
            ->assign('subInfo', LinkController::getSubinfo($this->user, 0))
            ->display('user/index.tpl');
        return $response;
    }
    
    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function tutorial(ServerRequest $request, Response $response, $args)
    {
        $opts = $request->getQueryParams();
        if ($opts['os'] == 'faq') {
            return $this->view()->display('user/tutorial/faq.tpl');
        }
        $opts['os'] = str_replace(' ','',$opts['os']);
        $opts['client'] = str_replace(' ','',$opts['client']);
        if ($opts['os'] != '' && $opts['client'] != '') {
            $url = 'user/tutorial/'.$opts['os'].'/'.$opts['client'].'.tpl';
            $class_left_days = floor((strtotime($this->user->class_expire)-time())/86400)+1;
            $this->view()
                ->assign('subInfo', LinkController::getSubinfo($this->user, 0))
                ->assign('anns', Ann::where('date', '>=', date('Y-m-d H:i:s', time() - 7 * 86400))->orderBy('date', 'desc')->get())
                ->assign('class_left_days', $class_left_days)
                ->assign('user', $this->user)
                ->registerClass('URL', URL::class)
                ->display($url);
        }
        return $response;
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function profile(ServerRequest $request, Response $response, $args)
    {
        $bind_token = TelegramSessionManager::add_bind_session($this->user);

        $this->view()
            ->assign('user', $this->user)
            ->assign('anns', Ann::where('date', '>=', date('Y-m-d H:i:s', time() - 7 * 86400))->orderBy('date', 'desc')->get())
            ->assign('bind_token', $bind_token)
            ->assign('telegram_bot_id', Setting::obtain('telegram_bot_id'))
            ->registerClass('URL', URL::class)
            ->display('user/profile.tpl');
        return $response;
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function referral(ServerRequest $request, Response $response, $args)
    {
        $code = InviteCode::where('user_id', $this->user->id)->first();
        if ($code == null) {
            $this->user->addInviteCode();
            $code = InviteCode::where('user_id', $this->user->id)->first();
        }
        $referred_user = User::where('ref_by', $this->user->id)->count();
        $invite_url = Setting::obtain('website_url') . '/signup?ref=' . $code->code;
        $this->view()
            ->assign('code', $code)
            ->assign('anns', Ann::where('date', '>=', date('Y-m-d H:i:s', time() - 7 * 86400))->orderBy('date', 'desc')->get())
            ->assign('referred_user', $referred_user)
            ->assign('referral_url', $invite_url)
            ->display('user/referral.tpl');
        return $response;
    }
    
    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function enableNotify(ServerRequest $request, Response $response, $args)
    {
        $type = $request->getParam('notify_type');
       
        $user = $this->user;
        if ($type == 'telegram' && Setting::obtain('enable_telegram_bot') == false) {
            $res['ret'] = 0;
            $res['msg'] = '系统未启用Telegram Bot';
            return $response->withJson($res);
        } else if ($type == 'telegram' && $user->telegram_id == null) {
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


    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function updateProfile(ServerRequest $request, Response $response, $args)
    {
        $type = $args['type'];
        $user = $this->user;
        switch ($type) {
            case 'password':
                $current_password = $request->getParam('current_password');
                $new_password = $request->getParam('new_password');
                try {
                if (!Hash::checkPassword($user->password, $current_password)) {  
                    throw new \Exception(I18n::get()->t('passwd error'));
                }
                $hashPassword = Hash::passwordHash($new_password);
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
                $newemail = $request->getParam('newemail');
                $oldemail = $user->email;
                $otheruser = User::where('email', $newemail)->first();
                try {
                    
                    if (Setting::obtain('reg_email_verify')) {
                        $emailcode = $request->getParam('emailcode');
                        $mailcount = EmailVerify::where('email', '=', $newemail)->where('code', '=', $emailcode)->where('expire_in', '>', time())->first();
                        if ($mailcount == null) {

                            throw new \Exception(I18n::get()->t('email verification code error'));
                        }
                    }
                    
                    if ($newemail == '') {
                        throw new \Exception(I18n::get()->t('blank is not allowed'));
                    }
                    
                    $check_res = Check::isEmailLegal($newemail);
                    if ($check_res['ret'] == 0) {
                        throw new \Exception((string)$check_res);
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
                $user->clear_inviteCodes();
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

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function getUserTrafficUsage(ServerRequest $request, Response $response, $args)
    {   
        $res['unflowtraffic'] = $this->user->transfer_enable;
        $res['traffic'] = Tools::flowAutoShow($this->user->transfer_enable);
        $res['trafficInfo'] = array(
            'todayUsedTraffic' => $this->user->TodayusedTraffic(),
            'lastUsedTraffic' => $this->user->LastusedTraffic(),
            'unUsedTraffic' => $this->user->unusedTraffic(),
            'TodayusedTrafficPercent' => $this->user->TodayusedTrafficPercent(),
            'LastusedTrafficPercent'  => $this->user->LastusedTrafficPercent()
        );
        $res['ret'] = 1;
        return $response->withJson($res);
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function handleKill(ServerRequest $request, Response $response, $args)
    {
        $user = $this->user;

        $email = $user->email;

        $passwd = $request->getParam('passwd');
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

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function disable(ServerRequest $request, Response $response, $args)
    {
        $this->view()->display('user/disable.tpl');
        return $response;
    }
    
    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function record(ServerRequest $request, Response $response, $args)
    {
        $user = $this->user;
        $this->view()
            ->assign('anns', Ann::where('date', '>=', date('Y-m-d H:i:s', time() - 7 * 86400))->orderBy('date', 'desc')->get())
            ->display('user/record.tpl');
        return $response;
    }
    
    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function ban(ServerRequest $request, Response $response, $args)
    {
        $user = $this->user;
        $this->view()
            ->assign('anns', Ann::where('date', '>=', date('Y-m-d H:i:s', time() - 7 * 86400))->orderBy('date', 'desc')->get())
            ->display('user/ban.tpl');
        return $response;
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function logout(ServerRequest $request, Response $response, $args)
    {
        Auth::logout();
        return $response
            ->withStatus(302)
            ->withHeader('Location', '/');
    }
}
