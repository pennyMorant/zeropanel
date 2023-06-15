<?php
/**
 * Created by PhpStorm.
 * User: Indexyz
 * Date: 11/9/2018
 * Time: 9:15 PM
 */

namespace App\Utils;

use Smarty;
use App\Services\Config;
use App\Models\Setting;


class ConfRender
{
    public static function getTemplateRender()
    {
        $smarty = new smarty();

        $smarty->settemplatedir(BASE_PATH . '/resources/conf/');
        $smarty->setcompiledir(BASE_PATH . '/storage/framework/smarty/compile/');
        $smarty->setcachedir(BASE_PATH . '/storage/framework/smarty/cache/');
        $smarty->assign('config', Config::getPublicConfig());
        $smarty->assign('subUrl', Setting::obtain('subscribe_address_url'));
        $smarty->registerClass('config', Config::class);
        return $smarty;
    }
}
