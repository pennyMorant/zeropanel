<?php

namespace App\Command;
use App\Models\Setting;
use Telegram\Bot\Api;

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
        $json_settings = file_get_contents('./config/settings.json');
        $settings = json_decode($json_settings, true);
        $config = [];
        $add_counter = 0;
        $del_counter = 0;

        // 检查新增
        foreach ($settings as $item) {
            $config[]  = $item['item'];
            $item_name = $item['item'];
            $query     = Setting::where('item', '=', $item['item'])->first();

            if ($query === null) {
                $new_item            = new Setting();
                $new_item->id        = null;
                $new_item->item      = $item['item'];
                $new_item->value     = $item['value'];
                $new_item->class     = $item['class'];
                $new_item->is_public = $item['is_public'];
                $new_item->type      = $item['type'];
                $new_item->default   = $item['default'];
                $new_item->mark      = $item['mark'];
                $new_item->save();

                echo "添加新数据库设置：{$item_name}" . PHP_EOL;
                $add_counter += 1;
            }
        }
          // 检查移除
        $db_settings = Setting::all();
        foreach ($db_settings as $db_setting) {
            if (! in_array($db_setting->item, $config)) {
                $db_setting->delete();
                $del_counter += 1;
            }
        }

        if ($add_counter !== 0) {
            echo "总计添加了 {$add_counter} 项新数据库设置" . PHP_EOL;
        } else {
            echo '没有任何新数据库设置项需要添加' . PHP_EOL;
        }
        if ($del_counter !== 0) {
            echo "总计移除了 {$del_counter} 项数据库设置" . PHP_EOL;
        }
    }
}
