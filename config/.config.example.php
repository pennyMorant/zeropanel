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

//Bot 设置--------------------------------------------------------------------------------------------
# Telegram BOT 其他选项
$_ENV['allow_to_join_new_groups'] = true;         //允许 Bot 加入下方配置之外的群组
$_ENV['group_id_allowed_to_join'] = [];           //允许加入的群组 ID，格式为 PHP 数组
$_ENV['enable_not_admin_reply'] = true;         //非管理员操作管理员功能是否回复
$_ENV['not_admin_reply_msg'] = '!';          //非管理员操作管理员功能的回复内容
$_ENV['no_user_found'] = '!';          //管理员操作时，找不到用户的回复
$_ENV['no_search_value_provided'] = '!';          //管理员操作时，没有提供用户搜索值的回复
$_ENV['data_method_not_found'] = '!';          //管理员操作时，修改数据的字段没有找到的回复
$_ENV['delete_message_time'] = 180;          //在以下时间后删除用户命令触发的 bot 回复，单位：秒，删除时间可能会因为定时任务而有差异，为 0 代表不开启此功能
$_ENV['delete_admin_message_time'] = 86400;        //在以下时间后删除管理命令触发的 bot 回复，单位：秒，删除时间可能会因为定时任务而有差异，为 0 代表不开启此功能
$_ENV['enable_delete_user_cmd'] = false;        //自动删除群组中用户发送的命令，使用 delete_message_time 配置的时间，删除时间可能会因为定时任务而有差异
$_ENV['help_any_command'] = false;        //允许任意未知的命令触发 /help 的回复

$_ENV['remark_user_search_email'] = ['邮箱'];                     //用户搜索字段 email 的别名，可多个，格式为 PHP 数组
$_ENV['remark_user_search_port'] = ['端口'];                     //用户搜索字段 port 的别名，可多个，格式为 PHP 数组

$_ENV['remark_user_option_is_admin'] = ['管理员'];                   //用户搜索字段 is_admin 的别名，可多个，格式为 PHP 数组
$_ENV['remark_user_option_enable'] = ['用户启用'];                  //用户搜索字段 enable 的别名，可多个，格式为 PHP 数组
$_ENV['remark_user_option_money'] = ['金钱', '余额'];             //用户搜索字段 money 的别名，可多个，格式为 PHP 数组
$_ENV['remark_user_option_port'] = ['端口'];                     //用户搜索字段 port 的别名，可多个，格式为 PHP 数组
$_ENV['remark_user_option_transfer_enable'] = ['流量'];                     //用户搜索字段 transfer_enable 的别名，可多个，格式为 PHP 数组
$_ENV['remark_user_option_passwd'] = ['连接密码'];                 //用户搜索字段 passwd 的别名，可多个，格式为 PHP 数组
$_ENV['remark_user_option_method'] = ['加密'];                     //用户搜索字段 method 的别名，可多个，格式为 PHP 数组
$_ENV['remark_user_option_protocol'] = ['协议'];                     //用户搜索字段 protocol 的别名，可多个，格式为 PHP 数组
$_ENV['remark_user_option_protocol_param'] = ['协参', '协议参数'];         //用户搜索字段 protocol_param 的别名，可多个，格式为 PHP 数组
$_ENV['remark_user_option_obfs'] = ['混淆'];                     //用户搜索字段 obfs 的别名，可多个，格式为 PHP 数组
$_ENV['remark_user_option_obfs_param'] = ['混参', '混淆参数'];         //用户搜索字段 obfs_param 的别名，可多个，格式为 PHP 数组
$_ENV['remark_user_option_node_group'] = ['用户组', '用户分组'];       //用户搜索字段 node_group 的别名，可多个，格式为 PHP 数组
$_ENV['remark_user_option_class'] = ['等级'];                     //用户搜索字段 class 的别名，可多个，格式为 PHP 数组
$_ENV['remark_user_option_class_expire'] = ['等级过期时间'];             //用户搜索字段 class_expire 的别名，可多个，格式为 PHP 数组

$_ENV['remark_user_option_node_speedlimit'] = ['限速'];                    //用户搜索字段 node_speedlimit 的别名，可多个，格式为 PHP 数组
$_ENV['remark_user_option_node_connector'] = ['连接数', '客户端'];         //用户搜索字段 node_connector 的别名，可多个，格式为 PHP 数组

$_ENV['enable_user_email_group_show'] = false;                      //开启在群组搜寻用户信息时显示用户完整邮箱，关闭则会对邮箱中间内容打码，如 g****@gmail.com
$_ENV['user_not_bind_reply'] = '您未绑定本站账号，您可以进入网站的 **资料编辑**，在右下方绑定您的账号.';                      //未绑定账户的回复
$_ENV['telegram_general_pricing'] = '产品介绍.';                  //面向游客的产品介绍
$_ENV['telegram_general_terms'] = '服务条款.';                  //面向游客的服务条款
//--------------------------------------------------------------------------------------------------------------------------------------------------------------

$_ENV['payment_system'] = 'zeropay';
//--------------------------------------------------------------------------------------------------------------------------------------

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
$_ENV['pwdMethod'] = 'argoni';               //密码加密 可选 md5, sha256, bcrypt, argon2i, argon2id（argon2i需要至少php7.2）
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
$_ENV['money_from_admin'] = false;            //是否开启管理员修改用户余额时创建充值记录

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
