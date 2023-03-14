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
        if (Setting::obtain('enable_push_system_report') == true) {
            $messagetext = str_replace(
                array(
                    '%lastday_total%',
                    '%getAliveNodes%'
                ),
                array(
                    Tools::flowAutoShow($sts->getRawTodayTrafficUsage()),
                    $sts->getAliveNodes(),
                ),
                Setting::obtain('diy_system_report_telegram_notify_content')
            );                
            Telegram::PushToAdmin($messagetext);                         
        }
    }
}
