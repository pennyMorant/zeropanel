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
    EmailVerify,
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
            ->assign('getUniversalSub', SubController::getUniversalSub($this->user))
            ->display('user/index.tpl');
        return $response;
    }

    public function isHTTPS()
    {
        define('HTTPS', false);
        if (defined('HTTPS') && HTTPS) {
            return true;
        }
        if (!isset($_SERVER)) {
            return false;
        }
        if (!isset($_SERVER['HTTPS'])) {
            return false;
        }
        if ($_SERVER['HTTPS'] === 1) {  //Apache
            return true;
        }

        if ($_SERVER['HTTPS'] === 'on') { //IIS
            return true;
        }

        if ($_SERVER['SERVER_PORT'] == 443) { //其他
            return true;
        }
        return false;
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
    public function sys($request, $response, $args)
    {
        $this->view()->assign('ana', '')->display('user/sys.tpl');
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
            
            throw new \Exception(I18n::get()->t('user.profile.notify.passwd.error_current_passwd'));
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
            'msg' => I18n::get()->t('user.profile.notify.passwd.success')
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
                throw new \Exception(I18n::get()->t('user.profile.notify.email.error_not_allowed'));          
            }
            
            if (Setting::obtain('reg_email_verify')) {
                $emailcode = $request->getParam('emailcode');
                $mailcount = EmailVerify::where('email', '=', $newemail)->where('code', '=', $emailcode)->where('expire_in', '>', time())->first();
                if ($mailcount == null) {

                    throw new \Exception(I18n::get()->t('user.profile.notify.email.error_invalid_email_code'));
                }
            }
            
            if ($newemail == '') {
                throw new \Exception(I18n::get()->t('user.profile.notify.email.error_empty_email'));
            }
            
            $check_res = Check::isEmailLegal($newemail);
            if ($check_res['ret'] == 0) {
                throw new \Exception((string)$check_res);
            }
            
            if ($otheruser != null) {
                throw new \Exception(I18n::get()->t('user.profile.notify.email.error_used_email'));
            }
            
            if ($newemail == $oldemail) {
                throw new \Exception(I18n::get()->t('user.profile.notify.email.error_same_email'));
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
            'msg' => I18n::get()->t('user.profile.notify.email.success')
        ]);
    }
    
    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function updateUserName($request, $response, $args)
    {
        $newusername = $request->getParam('newusername');
        $regname = '#[^\x{4e00}-\x{9fa5}A-Za-z0-9]#u';
        $user = $this->user;
        try {
            if (Setting::obtain('enable_change_username_user_general') != true) {
                throw new \Exception(I18n::get()->t('user.profile.notify.name.error_not_allowed'));

            }
            if ($newusername==''){
                throw new \Exception(I18n::get()->t('user.profile.notify.name.error_empty_name'));
            }
            if (preg_match($regname,$newusername)){
                throw new \Exception(I18n::get()->t('user.profile.notify.name.error_not_allowed_symbol'));
            }
            if (strlen($newusername) > 30) {
                throw new \Exception(I18n::get()->t('user.profile.notify.name.error_long_name'));
            }
        } catch (\Exception $e) {
            return $response->withJson([
                'ret' => 0,
                'msg' => $e->getMessage(),
            ]);
        }
        $antiXss = new AntiXSS();
        $user->name = $antiXss->xss_clean($newusername);
        $user->save();

        return $response->withJson([
            'ret' => 1,
            'msg' => I18n::get()->t('user.profile.notify.name.success')
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
        $res['msg'] = I18n::get()->t('user.profile.notify.sub.success');
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
        $res['msg'] = I18n::get()->t('user.profile.notify.sub.success');
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
        $res['msg'] = I18n::get()->t('user.profile.notify.sub.success');
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
        $res['msg'] = '重置成功';
        return $response->withJson($res);
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function doCheckIn($request, $response, $args)
    {   
        if (Setting::obtain('enable_user_checkin') == false) {
            $res['ret'] = 0;
            $res['msg'] = I18n::get()->t('user.profile.notify.checkin.error_not_allowed');
            return $response->withJson($res);
        }

        if (strtotime($this->user->expire_in) < time()) {
            $res['ret'] = 0;
            $res['msg'] = I18n::get()->t('user.profile.notify.checkin.error_expired');
            return $response->withJson($res);
        }

        $checkin = $this->user->checkin();
        if ($checkin['ok'] === false) {
            $res['ret'] = 0;
            $res['msg'] = $checkin['msg'];
            return $response->withJson($res);
        }

        $res['msg'] = $checkin['msg'];
        $res['unflowtraffic'] = $this->user->transfer_enable;
        $res['traffic'] = Tools::flowAutoShow($this->user->transfer_enable);
        $res['trafficInfo'] = array(
            'todayUsedTraffic' => $this->user->TodayusedTraffic(),
            'lastUsedTraffic' => $this->user->LastusedTraffic(),
            'unUsedTraffic' => $this->user->unusedTraffic(),
        );
        $res['ret'] = 1;
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
            $res['msg'] = I18n::get()->t('user.profile.notify.delete.error_passwd');
            return $response->withJson($res);
        }

        if (Setting::obtain('enable_delete_account_user_general') == true) {
            Auth::logout();
            $user->kill_user();
            $res['ret'] = 1;
            $res['msg'] = I18n::get()->t('user.profile.notify.delete.success');
        } else {
            $res['ret'] = 0;
            $res['msg'] = I18n::get()->t('user.profile.notify.delete.error_not_allowed');
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
     *
     * @param Request    $request
     * @param Response   $response
     * @param array      $args
     */
    public function SharedAccount($request, $response, $args)
    {
        $class_left_days = floor((strtotime($this->user->class_expire)-time())/86400)+1;
        $this->view()
            ->assign('class_left_days', $class_left_days)
            ->display('user/shared_account.tpl');
        return $response;
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function backToAdmin($request, $response, $args)
    {
        $userid = Cookie::get('uid');
        $adminid = Cookie::get('old_uid');
        $user = User::find($userid);
        $admin = User::find($adminid);

        if (!$admin->is_admin || !$user) {
            Cookie::set([
                'uid' => null,
                'email' => null,
                'key' => null,
                'ip' => null,
                'expire_in' => null,
                'old_uid' => null,
                'old_email' => null,
                'old_key' => null,
                'old_ip' => null,
                'old_expire_in' => null,
                'old_local' => null
            ], time() - 1000);
        }
        $expire_in = Cookie::get('old_expire_in');
        $local = Cookie::get('old_local');
        Cookie::set([
            'uid' => Cookie::get('old_uid'),
            'email' => Cookie::get('old_email'),
            'key' => Cookie::get('old_key'),
            'ip' => Cookie::get('old_ip'),
            'expire_in' => $expire_in,
            'old_uid' => null,
            'old_email' => null,
            'old_key' => null,
            'old_ip' => null,
            'old_expire_in' => null,
            'old_local' => null
        ], $expire_in);
        return $response->withStatus(302)->withHeader('Location', $local);
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
