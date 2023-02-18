<?php

namespace App\Services;

use App\Models\Setting;

class Config
{
    // TODO: remove
    public static function get($key)
    {
        return $_ENV[$key];
    }

    public static function getPublicConfig()
    {
        $public_configs = Setting::getPublicConfig();

        // 鉴于还未完成配置的全面数据库化，先这么用着
        
        return [
            'appName'                 => $public_configs['website_name'],
            'version'                 => VERSION,
            'baseUrl'                 => $public_configs['website_url'],
            // 充值
            'stripe_min_recharge'     => $public_configs['stripe_min_recharge'],
            'stripe_max_recharge'     => $public_configs['stripe_max_recharge'],
            // 客服系统
            'live_chat'               => $public_configs['live_chat'],
            'tawk_id'                 => $public_configs['tawk_id'],
            'crisp_id'                => $public_configs['crisp_id'],
            'livechat_id'             => $public_configs['livechat_id'],
            'mylivechat_id'           => $public_configs['mylivechat_id'],
            // 验证码
            'captcha_provider'         => $public_configs['captcha_provider'],
            'enable_signup_captcha'    => $public_configs['enable_signup_captcha'],
            'enable_signin_captcha'    => $public_configs['enable_signin_captcha'],
            // 注册
            'register_mode'           => $public_configs['reg_mode'],
            'enable_email_verify'     => $public_configs['reg_email_verify'],
            // 邀请
            'invite_get_money'        => $public_configs['invitation_to_signup_credit_reward'],
            'invite_gift'             => $public_configs['invitation_to_signup_traffic_reward'],
            'rebate_ratio'            => $public_configs['rebate_ratio'],
            // 提现
            'enable_withdraw'        => $public_configs['enable_withdraw'],
            'withdraw_minimum_amount'   => $public_configs['withdraw_minimum_amount'],
            'withdraw_method'        => $public_configs['withdraw_method'],
            // 代理
            'enable_sales_agent'      => $public_configs['enable_sales_agent'],
            'purchase_sales_agent_price' => $public_configs['purchase_sales_agent_price'],
            'sales_agent_commission_ratio'  => $public_configs['sales_agent_commission_ratio'],
            'jump_delay'              => $_ENV['jump_delay'],
            //'enable_reg_im'           => $_ENV['enable_reg_im'],
            // tg
            'subscribe_log_keep_time' => $public_configs['subscribe_log_keep_time'],
            'enable_telegram_bot'     => $public_configs['enable_telegram_bot'],
            'telegram_bot_id'         => $public_configs['telegram_bot_id'],

            // 支付暂时使用办法
            'payment_system'          => $_ENV['payment_system'],
        ];
    }

    public static function getAllConfig()
    {
        global $_ENV;
        
    }

    public static function getDbConfig()
    {
        return [
            'driver'        => $_ENV['db_driver'],
            'host'          => $_ENV['db_host'],
            'unix_socket'   => $_ENV['db_socket'],
            'database'      => $_ENV['db_database'],
            'username'      => $_ENV['db_username'],
            'password'      => $_ENV['db_password'],
            'charset'       => $_ENV['db_charset'],
            'collation'     => $_ENV['db_collation'],
            'prefix'        => $_ENV['db_prefix'],
        ];
    }

    public static function getMuKey()
    {
        $muKeyList = array_key_exists('muKeyList', $_ENV) ? $_ENV['muKeyList'] : ['　'];
        return array_merge(explode(',', Setting::obtain('website_backend_token')), $muKeyList);
    }
}
