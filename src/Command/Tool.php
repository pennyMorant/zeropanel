<?php

namespace App\Command;
use tronovav\GeoIP2Update\Client;
use const BASE_PATH;
use App\Models\Setting;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;
use App\Utils\DatatablesHelper;

class Tool extends Command
{
    public $description = ''
        . '├─=: php xcat Tool [选项]' . PHP_EOL
        . '│ ├─ updateGeoIP             - 下载 IP 解析库' . PHP_EOL
        . '│ ├─ setTelegram             - 设置 Telegram 机器人' . PHP_EOL
        . '│ ├─ detectConfigs           - 检查数据库内新增的配置' . PHP_EOL
        . '│ ├─ resetAllSettings        - 使用默认值覆盖设置中心设置' . PHP_EOL
        . '│ ├─ exportAllSettings       - 导出所有设置' . PHP_EOL
        . '│ ├─ importAllSettings       - 导入所有设置' . PHP_EOL;

    public function boot()
    {
        if (count($this->argv) === 2) {
            echo $this->description;
        } else {
            $methodName = $this->argv[2];
            if (method_exists($this, $methodName)) {
                $this->$methodName();
            } else {
                echo '方法不存在.' . PHP_EOL;
            }
        }
    }

    /**
     * 设定 Telegram Bot
     *
     * @return void
     */
    public function setTelegram()
    {
        if (Setting::obtain('enable_telegram_bot') == true) {
            $WebhookUrl = (Setting::obtain('website_url') . '/telegram_callback?token=' . Setting::obtain('telegram_bot_request_token'));
            $telegram = new Api(Setting::obtain('telegram_bot_token'));
            $telegram->removeWebhook();
            if ($telegram->setWebhook(['url' => $WebhookUrl])) {
                echo ('New Bot @' . $telegram->getMe()->getUsername() . ' 设置成功！' . PHP_EOL);
            }
        }
    }

    /**
     * 下载 IP 库
     *
     * @return void
     */
    public function updateGeoIP()
    {
        if ($_ENV['maxmind_license_key'] !== '') {
            echo "正在更新 GeoLite2 数据库...\n";
            $client = new Client(array(
                'license_key' => $_ENV['maxmind_license_key'],
                'dir' => BASE_PATH . '/storage/',
                'editions' => array('GeoLite2-City'),
            ));
            $client->run();
        }
    }
    
    public function resetAllSettings()
    {
        $settings = Setting::all();

        foreach ($settings as $setting)
        {
            $setting->value = $setting->default;
            $setting->save();
        }

        echo '已使用默认值覆盖所有设置.' . PHP_EOL;
    }
    
    public function exportAllSettings()
    {
        $settings = Setting::all();
        foreach ($settings as $setting)
        {
            // 因为主键自增所以即便设置为 null 也会在导入时自动分配 id
            // 同时避免多位开发者 pull request 时 settings.json 文件 id 重复所可能导致的冲突
            $setting->id = null;
            // 避免开发者调试配置泄露
            $setting->value = $setting->default;
        }

        $json_settings = json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents('./config/settings.json', $json_settings);

        echo '已导出所有设置.' . PHP_EOL;
    }

    public function importAllSettings()
    {
        $db = new DatatablesHelper();

        $json_settings = file_get_contents('./config/settings.json');
        $settings      = json_decode($json_settings, true);
        $number        = count($settings);
        $counter       = '0';

        for ($i = 0; $i < $number; $i++)
        {
            $item = $settings[$i]['item'];

            if (is_null($db->query("SELECT id FROM config WHERE item = '$item'"))) {
                $new_item            = new Setting;
                $new_item->id        = null;
                $new_item->item      = $settings[$i]['item'];
                $new_item->value     = $settings[$i]['value'];
                $new_item->class     = $settings[$i]['class'];
                $new_item->is_public = $settings[$i]['is_public'];
                $new_item->type      = $settings[$i]['type'];
                $new_item->default   = $settings[$i]['default'];
                $new_item->mark      = $settings[$i]['mark'];
                $new_item->save();

                echo "添加新设置：$item" . PHP_EOL;
                $counter += 1;
            }
        }

        if ($counter != '0') {
            echo "总计添加了 $counter 条新设置." . PHP_EOL;
        } else {
            echo "没有任何新设置需要添加." . PHP_EOL;
        }
    }
}
