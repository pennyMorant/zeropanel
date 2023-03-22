<?php

namespace App\Services;

use Smarty;
use App\Utils;
use App\Models\Setting;
use Pkly\I18Next\I18n;

class View
{
    public static $connection;
    public static $beginTime;

    public static function getSmarty()
    {
        $smarty = new smarty(); //实例化smarty

        $user = Auth::getUser();

        if ($user->isLogin) {
            $theme = $user->theme;
        } else {
            $theme = $_ENV['theme'];
        }
        if (Setting::obtain('enable_permission_group') == true) {
            $permission_group = json_decode(Setting::obtain('permission_group_detail'), true);
        } else {
            $permission_group = [
                0   =>  'LV-0',
                1   =>  'LV-1', 
                2   =>  'LV-2', 
                3   =>  'LV-3', 
                4   =>  'LV-4', 
                5   =>  'LV-5', 
                6   =>  'LV-6', 
                7   =>  'LV-7',
                8   =>  'LV-8', 
                9   =>  'LV-9', 
                10  =>  'LV-10',
            ];
        } 
        $user_permission = isset($permission_group[$user->class]) ? $permission_group[$user->class] : "unknown";
        $smarty->settemplatedir(BASE_PATH . '/resources/views/' . $theme . '/'); //设置模板文件存放目录
        $smarty->setcompiledir(BASE_PATH . '/storage/framework/smarty/compile/'); //设置生成文件存放目录
        $smarty->setcachedir(BASE_PATH . '/storage/framework/smarty/cache/'); //设置缓存文件存放目录
        //$smarty->auto_literal = true;
        // add config

        $smarty->assign('config', Config::getPublicConfig());
        $smarty->assign('zeroconfig', ZeroConfig::getPublicSetting());
        $smarty->assign('trans', I18n::get());
        $smarty->assign('user', $user);
        $smarty->assign('user_permission', $user_permission);

        if (self::$connection) {
            $smarty->assign('queryLog', self::$connection->connection('default')->getQueryLog());
            $optTime = microtime(true) - self::$beginTime;
            $smarty->assign('optTime', $optTime * 1000);
        }

        return $smarty;
    }
}
