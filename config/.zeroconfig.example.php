<?php

# 主页订阅框显示哪些订阅     可选: v2ray, surge, clash, surfboard, kitsunebi, shadowrocket, quantumult, quantumultx, v2rayvless, shadwosocks
$_ZC['index_sub'] = [
    'v2rayn',
    'surge',
    'clash',
    'surfboard',
    'quantumult',
    'quantumultx',
    'shadowrocket',
    'anxray',
    'ss',
];


/**
 *    Windows 客户端  --------------------------------------------------------------------------------------------
 */
$_ZC['client_windows'] = [
    'clash' => array(           // 一个array为一个客户端, 可以自行增加或删除
        'name'  => 'Clash',      // 客户端名称
        'img'   => '/theme/zero/assets/media/app_logo/clash_logo.png',        // 图标, 使用png透明文件
        'url'   => '/user/tutorial?os=Windows&client=clash',      // 安装教程的url地址
        'down'  => '/clients/clash-windows.exe',              // 教程页里的客户端下载地址
    ),
    'v2rayn' => array(
        'name'  => 'V2rayN',
        'img'   => '/theme/zero/assets/media/app_logo/v2rayng_logo.png',
        'url'   => '/user/tutorial?os=Windows&client=v2rayn',
        'down'  => '/clients/v2rayN-Core.zip'
    ),
    'qv2ray' => array(
        'name'  => 'Qv2ray',
        'img'   => '/theme/zero/assets/media/app_logo/qv2ray_logo.png',
        'url'   => '/user/tutorial?os=Windows&client=qv2ray',
        'down'  => 'clients/Qv2ray.dmg'
    ),
    'netch' => array(
        'name'  => 'Netch',
        'img'   => '/theme/zero/assets/media/app_logo/clash_logo.png',
        'url'   => '/user/tutorial?os=Windows&client=netch',
        'vs'    => 'v1.1.0.1',
    ),
];

/**
 *    Android 客户端  --------------------------------------------------------------------------------------------
 */
$_ZC['client_android'] = [
    'clash' => array(
        'name'  => 'Clash',
        'img'   => '/theme/zero/assets/media/app_logo/clash_logo.png',
        'url'   => '/user/tutorial?os=Android&client=clash',
        'down'  => '/clients/clash.apk',
        'vs'    => 'v2.4.9',
    ),
    'surfboard' => array (
        'name' => 'Surfboard',
        'img' => '/theme/zero/assets/media/app_logo/surfboard_logo.png',
        'url' => '/user/tutorial?os=Android&client=surfboard',
        'down' => '/clients/surfboard.apk'
    ),
    'v2rayng' => array (
        'name' => 'V2rayNG',
        'img' => '/theme/zero/assets/media/app_logo/v2rayng_logo.png',
        'url' => '/user/tutorial?os=Android&client=v2rayng',
        'down' => '/clients/v2rayng.apk'
    ),
    'sagernet' => array (
        'name' => 'SagerNet',
        'img' => '/theme/zero/assets/media/app_logo/sagernet_logo.png',
        'url' => '/user/tutorial?os=Android&client=sagernet',
        'down' => '/clients/sagernet.apk'
    ),
];


/**
 *    Apple Mac客户端  --------------------------------------------------------------------------------------------
 */
$_ZC['client_macos'] = [
    'clashxpro' => array(
        'name'  => 'ClashX Pro',
        'img'   => '/theme/zero/assets/media/app_logo/clash_logo.png',
        'url'   => '/user/tutorial?os=MacOS&client=clashxpro',
        'down'  => '/clients/clashx-pro.dmg',
    ),
    'clash' => array(
        'name'  => 'Clash',
        'img'   => '/theme/zero/assets/media/app_logo/clash_logo.png',
        'url'   => '/user/tutorial?os=MacOS&client=clash',
        'down'  => '/clients/clash-windows.dmg'
    ),
    'qv2ray' => array(
        'name'  => 'Qv2ray',
        'img'   => '/theme/zero/assets/media/app_logo/qv2ray_logo.png',
        'url'   => '/user/tutorial?os=MacOS&client=qv2ray',
        'down'  => 'clients/Qv2ray.dmg'
    ),
    'surge' => array(
        'name'  => 'Surge',
        'img'   => '/theme/zero/assets/media/app_logo/surge_logo.png',
        'url'   => '/user/tutorial?os=MacOS&client=surge',
        'down'    => 'https://apps.apple.com/us/app/surge-4/id1442620678'
    ),
];

/**
 *    Apple iOS客户端  --------------------------------------------------------------------------------------------
 */
$_ZC['client_ios'] = [
    'shadowrocket' => array(
        'name'  => 'Shadowrocket',
        'img'   => '/theme/zero/assets/media/app_logo/shadowrocket_logo.png',
        'url'   => '/user/tutorial?os=iOS&client=shadowrocket',
        'conf'  => 'https://h2y.github.io/Shadowrocket-ADBlock-Rules/sr_cnip_ad.conf',
        'down'  => 'https://apps.apple.com/us/app/shadowrocket/id932747118'
    ),
    'quantumult' => array(
        'name'  => 'Quantumult',
        'img'   => '/theme/zero/assets/media/app_logo/quantumult_logo.png',
        'url'   => '/user/tutorial?os=iOS&client=quantumult',
        'down'  => 'https://apps.apple.com/us/app/quantumult/id1252015438'
    ),
    'quantumultX' => array(
        'name'  => 'QuantumultX',
        'img'   => '/theme/zero/assets/media/app_logo/quantumultx_logo.png',
        'url'   => '/user/tutorial?os=iOS&client=quantumultx',
        'down'    => 'https://apps.apple.com/us/app/quantumult-x/id1443988620'
    ),
    'surge' => array(
        'name'  => 'Surge',
        'img'   => '/theme/zero/assets/media/app_logo/surge_logo.png',
        'url'   => '/user/tutorial?os=iOS&client=surge',
        'down'    => 'https://apps.apple.com/us/app/surge-4/id1442620678'
    ),
];
