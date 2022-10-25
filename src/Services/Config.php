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
            'appName'                 => $public_configs['website_general_name'],
            'version'                 => VERSION,
            'baseUrl'                 => $public_configs['website_general_url'],
            // 充值
            'stripe_min_recharge'     => $public_configs['stripe_min_recharge'],
            'stripe_max_recharge'     => $public_configs['stripe_max_recharge'],
            // 个性化
            'user_center_bg'          => $public_configs['user_center_bg'],
            'admin_center_bg'         => $public_configs['admin_center_bg'],
            'user_center_bg_addr'     => $public_configs['user_center_bg_addr'],
            'admin_center_bg_addr'    => $public_configs['admin_center_bg_addr'],
            // 客服系统
            'live_chat'               => $public_configs['live_chat'],
            'tawk_id'                 => $public_configs['tawk_id'],
            'crisp_id'                => $public_configs['crisp_id'],
            'livechat_id'             => $public_configs['livechat_id'],
            'mylivechat_id'           => $public_configs['mylivechat_id'],
            // 联系方式
            'enable_admin_contact'    => $public_configs['enable_admin_contact'],
            'admin_contact1'          => $public_configs['admin_contact1'],
            'admin_contact2'          => $public_configs['admin_contact2'],
            'admin_contact3'          => $public_configs['admin_contact3'],
            // 验证码
            'captcha_provider'        => $public_configs['captcha_provider'],
            'enable_reg_captcha'      => $public_configs['enable_reg_captcha'],
            'enable_login_captcha'    => $public_configs['enable_login_captcha'],
            'enable_checkin_captcha'  => $public_configs['enable_checkin_captcha'],
            // 注册
            'register_mode'           => $public_configs['reg_mode'],
            'enable_email_verify'     => $public_configs['reg_email_verify'],
            // 邀请
            'invite_get_money'        => $public_configs['invitation_to_register_balance_reward'],
            'invite_gift'             => $public_configs['invitation_to_register_traffic_reward'],
            'rebate_ratio'            => $public_configs['rebate_ratio'],
            // 提现
            'enable_withdraw'        => $public_configs['enable_withdraw'],
            'withdraw_less_amount'   => $public_configs['withdraw_less_amount'],
            'withdraw_method'        => $public_configs['withdraw_method'],
            // 闪购
            'enable_flash_sell'      => $public_configs['enable_flash_sell'],
            'flash_sell_product_id'  => $public_configs['flash_sell_product_id'],
            'flash_sell_product_name' => $public_configs['flash_sell_product_name'],
            'flash_sell_start_time'  => $public_configs['flash_sell_start_time'], 
            // 代理
            'enable_sales_agent'      => $public_configs['enable_sales_agent'],
            'purchase_sales_agent_price' => $public_configs['purchase_sales_agent_price'],
            'sales_agent_commission_ratio'  => $public_configs['sales_agent_commission_ratio'],
            // 签到
            'enable_checkin'          => $public_configs['enable_user_checkin'],
            'jump_delay'              => $_ENV['jump_delay'],
            //'enable_reg_im'           => $_ENV['enable_reg_im'],
            // tg
            'subscribe_log_save_days' => $public_configs['subscribe_log_save_days'],
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

    public static function getSupportParam($type)
    {
        switch ($type) {
            case 'obfs':
                $list = array(
                    'plain',
                    'http_simple',
                    'http_simple_compatible',
                    'http_post',
                    'http_post_compatible',
                    'tls1.2_ticket_auth',
                    'tls1.2_ticket_auth_compatible',
                    'tls1.2_ticket_fastauth',
                    'tls1.2_ticket_fastauth_compatible',
                    'simple_obfs_http',
                    'simple_obfs_http_compatible',
                    'simple_obfs_tls',
                    'simple_obfs_tls_compatible'
                );
                return $list;
            case 'protocol':
                $list = array(
                    'origin',
                    'verify_deflate',
                    'auth_sha1_v4',
                    'auth_sha1_v4_compatible',
                    'auth_aes128_sha1',
                    'auth_aes128_md5',
                    'auth_chain_a',
                    'auth_chain_b',
                    'auth_chain_c',
                    'auth_chain_d',
                    'auth_chain_e',
                    'auth_chain_f'
                );
                return $list;
            case 'allow_none_protocol':
                $list = array(
                    'auth_chain_a',
                    'auth_chain_b',
                    'auth_chain_c',
                    'auth_chain_d',
                    'auth_chain_e',
                    'auth_chain_f'
                );
                return $list;
            case 'ss_aead_method':
                $list = array(
                    'aes-128-gcm',
                    'aes-192-gcm',
                    'aes-256-gcm',
                    'chacha20-ietf-poly1305',
                    'xchacha20-ietf-poly1305'
                );
                return $list;
            case 'ss_obfs':
                $list = array(
                    'simple_obfs_http',
                    'simple_obfs_http_compatible',
                    'simple_obfs_tls',
                    'simple_obfs_tls_compatible'
                );
                return $list;
            default:
                $list = array(
                    'rc4-md5',
                    'rc4-md5-6',
                    'aes-128-cfb',
                    'aes-192-cfb',
                    'aes-256-cfb',
                    'aes-128-ctr',
                    'aes-192-ctr',
                    'aes-256-ctr',
                    'camellia-128-cfb',
                    'camellia-192-cfb',
                    'camellia-256-cfb',
                    'bf-cfb',
                    'cast5-cfb',
                    'des-cfb',
                    'des-ede3-cfb',
                    'idea-cfb',
                    'rc2-cfb',
                    'seed-cfb',
                    'salsa20',
                    'chacha20',
                    'xsalsa20',
                    'chacha20-ietf',
                    'aes-128-gcm',
                    'aes-192-gcm',
                    'none',
                    'aes-256-gcm',
                    'chacha20-ietf-poly1305',
                    'xchacha20-ietf-poly1305'
                );
                return $list;
        }
    }
}
