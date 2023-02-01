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
use Ramsey\Uuid\Uuid;
use Slim\Http\{
    Request,
    Response
};
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
    public function index($request, $response, $args)
    {       
        $code = InviteCode::where('user_id', $this->user->id)->first();
        if ($code == null) {
            $this->user->addInviteCode();
            $code = InviteCode::where('user_id', $this->user->id)->first();
        }
        $invite_url = Setting::obtain('website_general_url') . '/auth/signup?code=' . $code->code;
        $class_left_days = floor((strtotime($this->user->class_expire)-time())/86400)+1;
        $this->view()
            ->assign('sub_token', $this->user->getSublink())
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
    public function tutorial($request, $response, $args)
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
    public function profile($request, $response, $args)
    {
        $bind_token = TelegramSessionManager::add_bind_session($this->user);
        $config_service = new Config();

        $this->view()
            ->assign('user', $this->user)
            ->assign('sub_token', $this->user->getSublink())
            ->assign('bind_token', $bind_token)
            ->assign('telegram_bot_id', Setting::obtain('telegram_bot_id'))
            ->assign('config_service', $config_service)
            ->registerClass('URL', URL::class)
            ->display('user/profile.tpl');
        return $response;
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function referral($request, $response, $args)
    {
        $code = InviteCode::where('user_id', $this->user->id)->first();
        if ($code == null) {
            $this->user->addInviteCode();
            $code = InviteCode::where('user_id', $this->user->id)->first();
        }
        $referred_user = User::where('ref_by', $this->user->id)->count();
        $invite_url = Setting::obtain('website_general_url') . '/signup?ref=' . $code->code;
        $this->view()
            ->assign('code', $code)
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
    public function enableNotify($request, $response, $args)
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
    public function updatePassword($request, $response, $args)
    {
        $current_password = $request->getParam('current_password');
        $new_password = $request->getParam('new_password');
        $user = $this->user;
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

        if (Setting::obtain('enable_subscribe_change_token_when_change_passwd') == true) {
            $user->clean_link();
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
    public function updateEmail($request, $response, $args)
    {
        $user = $this->user;
        $newemail = $request->getParam('newemail');
        $oldemail = $user->email;
        $otheruser = User::where('email', $newemail)->first();
        try {
            if (Setting::obtain('enable_change_email_user_general') != true) {           
                throw new \Exception(I18n::get()->t('no changes allowed'));          
            }
            
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
            
            if ($otheruser != null) {
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

        return $response->withJson([
            'res' => 1,
            'msg' => I18n::get()->t('success')
        ]);
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function resetUUID($request, $response, $args)
    {
        $user = $this->user;
        $current_timestamp = time();
        $new_uuid = Uuid::uuid5(Uuid::NAMESPACE_DNS, $user->email . '|' . $current_timestamp);
        
        $user->uuid = $new_uuid;
        $user->save();
        $res['ret'] = 1;
        $res['msg'] = I18n::get()->t('success');
        return $response->withJson($res);
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function resetPasswd($request, $response, $args)
    {
        $user = $this->user;
        $passwd = Tools::genRandomChar(16);
        $user->passwd = $passwd;
        $user->save();
        $res['ret'] = 1;
        $res['msg'] = I18n::get()->t('success');
        return $response->withJson($res);
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function unbindTelegram($request, $response, $args)
    {
        $user = $this->user;
        $user->unbindTelegram();
        return $response->withStatus(302)->withHeader('Location', '/user/profile');
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function resetSubLink($request, $response, $args) {
        $user = $this->user;
        $user->clean_link();
        $res['ret'] = 1;
        $res['msg'] = I18n::get()->t('success');
        return $response->withJson($res);
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function resetReferralCode($request, $response, $args)
    {
        $user = $this->user;
        $user->clear_inviteCodes();
        $res['ret'] = 1;
        $res['msg'] = I18n::get()->t('success');
        return $response->withJson($res);
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function getUserTrafficUsage($request, $response, $args)
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
    public function handleKill($request, $response, $args)
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

        if (Setting::obtain('enable_delete_account_user_general') == true) {
            Auth::logout();
            $user->kill_user();
            $res['ret'] = 1;
            $res['msg'] = I18n::get()->t('success');
        } else {
            $res['ret'] = 0;
            $res['msg'] = I18n::get()->t('not allowed');
        }
        return $response->withJson($res);
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function disable($request, $response, $args)
    {
        $this->view()->display('user/disable.tpl');
        return $response;
    }
    
    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function record($request, $response, $args)
    {
        $user = $this->user;
        $this->view()->display('user/record.tpl');
        return $response;
    }
    
    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function ban($request, $response, $args)
    {
        $user = $this->user;
        $this->view()->display('user/ban.tpl');
        return $response;
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function logout($request, $response, $args)
    {
        Auth::logout();
        return $response
            ->withStatus(302)
            ->withHeader('Location', '/');
    }
}
