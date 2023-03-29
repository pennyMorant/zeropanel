<?php

//注释里请勿使用英文方括号、分号、单引号，否则迁移Config时会出错

//config迁移附注（由开发者填写本次config迁移后需要注意的地方，站长勿动）
//如需换行，直接换行即可，无需换行符
$_ENV['debug'] = false;
//数据库设置-----------------------------------------------------------------------------------------------------
// db_host|db_socket 二选一，若设置 db_socket 则 db_host 会被忽略，不用请留空。若数据库在本机上推荐用 db_socket
// db_host 例: localhost（可解析的主机名）, 127.0.0.1（IP 地址）, 10.0.0.2:4406（含端口)
// db_socket 例：/var/run/mysqld/mysqld.sock（需使用绝对地址）
$_ENV['db_driver'] = 'mysql';
$_ENV['db_host'] = '';
$_ENV['db_socket'] = '/run/mysqld/mysqld.sock';
$_ENV['db_database'] = '';           //数据库名
$_ENV['db_username'] = '';              //数据库用户名
$_ENV['db_password'] = '';           //用户名对应的密码
#高级
$_ENV['db_charset'] = 'utf8mb4';
$_ENV['db_collation'] = 'utf8mb4_unicode_ci';
$_ENV['db_prefix'] = '';
//----------------------------------------------------------------------------------------------------------

//邮件设置--------------------------------------------------------------------------------------------
$_ENV['sendPageLimit'] = 50;          //发信分页 解决大站发公告超时问题
$_ENV['email_queue'] = true;        //如题，自动计划任务邮件使用队列 需要每分钟执行 php xcat Job SendMail
$_ENV['sendemail'] = false;           // 是否发送各类通知邮件
$_ENV['mail_filter']        = 0;            //0: 关闭; 1: 白名单模式; 2; 黑名单模式;
$_ENV['mail_filter_list']   = array("qq.com", "vip.qq.com", "foxmail.com");
//------------------------------------------------------------------------------------------------------------------------

//审计自动封禁设置--------------------------------------------------------------------------------------------
$_ENV['enable_auto_detect_ban'] = false;       // 审计自动封禁开关
$_ENV['auto_detect_ban_numProcess'] = 300;         // 单次计划任务中审计记录的处理数量
$_ENV['auto_detect_ban_allow_admin'] = true;        // 管理员不受审计限制
$_ENV['auto_detect_ban_allow_users'] = [];          // 审计封禁的例外用户 ID

// 审计封禁判断类型：
//   - 1 = 仁慈模式，每触碰多少次封禁一次
//   - 2 = 疯狂模式，累计触碰次数按阶梯进行不同时长的封禁
$_ENV['auto_detect_ban_type'] = 1;
$_ENV['auto_detect_ban_number'] = 30;             // 仁慈模式每次执行封禁所需的触发次数
$_ENV['auto_detect_ban_time'] = 60;             // 仁慈模式每次封禁的时长 (分钟)

// 疯狂模式阶梯
// key 为触发次数
//   - type：可选 time 按时间 或 kill 删号
//   - time：时间，单位分钟
$_ENV['auto_detect_ban'] = [
    100 => [
        'type' => 'time',
        'time' => 120
    ],
    300 => [
        'type' => 'time',
        'time' => 720
    ],
    600 => [
        'type' => 'time',
        'time' => 4320
    ],
    1000 => [
        'type' => 'kill',
        'time' => 0
    ]
];
//--------------------------------------------------------------------------------------------------------------------------

#后台商品列表 销量统计
$_ENV['sales_period'] = 30;             //统计指定周期内的销量，值为【expire/任意大于0的整数】

//--------------------------------------------------------------------------------------------------------------------------------------

#离线检测
$_ENV['enable_detect_offline'] = true;
//--------------------------------------------------------------------------------------------------------------------------------------
//以下所有均为高级设置（一般用不上，不用改---------------------------------------------------------------------

// 主站是否提供 WebAPI
// - 为了安全性，推荐使用 WebAPI 模式对接节点并关闭公网数据库连接。
// - 如果您全部节点使用数据库连接或者拥有独立的 WebAPI 站点或 Seed，则可设为 false。
$_ENV['WebAPI'] = true;

#杂项
$_ENV['authDriver'] = 'cookie';            //不能更改此项
$_ENV['pwdMethod'] = 'argon2id';               //密码加密 可选 md5, sha256, bcrypt, argon2i, argon2id（argon2i需要至少php7.2）
$_ENV['salt'] = '';                  //推荐配合 md5/sha256， bcrypt/argon2i/argon2id 会忽略此项

$_ENV['sessionDriver']          = 'cookie';            //可选: cookie
$_ENV['cacheDriver']            = 'cookie';            //可选: cookie
$_ENV['tokenDriver']            = 'db';                //可选: db


$_ENV['rememberMeDuration'] = 7;           //登录时记住账号时长天数

$_ENV['timeZone'] = 'PRC';                 //PRC 天朝时间  UTC 格林时间
$_ENV['theme'] = 'zero';            //默认主题
$_ENV['jump_delay'] = 1200;                  //跳转延时，单位ms，不建议太长

$_ENV['checkNodeIp'] = false;                 //是否webapi验证节点ip
$_ENV['muKeyList'] = [];                   //多 key 列表
$_ENV['keep_connect'] = false;               // 流量耗尽用户限速至 1Mbps

#aws
$_ENV['aws_access_key_id'] = '';
$_ENV['aws_secret_access_key'] = '';

#Cloudflare
$_ENV['cloudflare_enable'] = false;         //是否开启 Cloudflare 解析
$_ENV['cloudflare_email'] = '';            //Cloudflare 邮箱地址
$_ENV['cloudflare_key'] = '';            //Cloudflare API Key
$_ENV['cloudflare_name'] = '';            //域名

#在套了CDN之后获取用户真实ip，如果您不知道这是什么，请不要乱动
$_ENV['cdn_forwarded_ip'] = array('HTTP_X_FORWARDED_FOR', 'HTTP_ALI_CDN_REAL_IP', 'X-Real-IP', 'True-Client-Ip');
foreach ($_ENV['cdn_forwarded_ip'] as $cdn_forwarded_ip) {
    if (isset($_SERVER[$cdn_forwarded_ip])) {
        $list = explode(',', $_SERVER[$cdn_forwarded_ip]);
        $_SERVER['REMOTE_ADDR'] = $list[0];
        break;
    }
}

// ClientDownload 命令解决 API 访问频率高而被限制使用的 Github access token
$_ENV['github_access_token'] = '';

$_ENV['php_user_group'] = 'www:www';
