<?php

namespace App\Models;

use voku\helper\AntiXSS;

class UserSubscribeLog extends Model
{
    protected $connection = 'default';

    protected $table = 'user_subscribe_log';

    /**
     * 用户
     */
    public function user(): ?User
    {
        return User::find($this->user_id);
    }

    /**
     * 记录订阅日志
     *
     * @param User   $user 用户
     * @param string $type 订阅类型
     * @param string $ua   UA
     *
     * @return void
     */
    public static function addSubscribeLog($user, $type, $ua)
    {
        $log                     = new UserSubscribeLog();
        $log->user_id            = $user->id;
        $log->email              = $user->email;
        $log->subscribe_type     = $type;
        $log->request_ip         = $_SERVER['REMOTE_ADDR'];
        $log->created_at         = date('Y-m-d H:i:s');
        $antiXss                 = new AntiXSS();
        $log->request_user_agent = $antiXss->xss_clean($ua);
        $log->save();
    }
}