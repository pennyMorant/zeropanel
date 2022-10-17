<?php

namespace App\Command;

use App\Models\{
    User,
    Ann,
    Setting
};
use App\Utils\Telegram;
use App\Utils\Tools;
use App\Services\Analytics;

class SendDiaryMail extends Command
{
    public $description = '├─=: php xcat SendDiaryMail  - 每日流量报告' . PHP_EOL;

    public function boot()
    {
        $sts = new Analytics();
        if (Setting::obtain('enable_system_report_telegram_notify') == true) {
            $sendAdmins = (array)json_decode(Setting::obtain('telegram_general_admin_id'));
            foreach ($sendAdmins as $sendAdmin) {
                $admin_telegram_id = User::where('id', $sendAdmin)->where('is_admin', '1')->value('telegram_id');
                $messagetext = str_replace(
                    array(
                        '%getTodayCheckinUser%',
                        '%lastday_total%',
                        '%getAliveNodes%'
                    ),
                    array(
                        $sts->getTodayCheckinUser(),
                        Tools::flowAutoShow($sts->getRawTodayTrafficUsage()),
                        $sts->getAliveNodes(),
                    ),
                    Setting::obtain('diy_system_report_telegram_notify_content')
                );                
                Telegram::PushToAdmin($messagetext, $admin_telegram_id);               
            }           
        }
    }
}
