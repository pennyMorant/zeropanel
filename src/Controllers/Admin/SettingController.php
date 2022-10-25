<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminController;
use App\Models\{
    Setting
};
use Slim\Http\{
    Request,
    Response
};
use App\Services\{
    Mail
};
use Exception;

class SettingController extends AdminController
{
    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function index($request, $response, $args)
    {
        $config = array();
        $settings = Setting::get(['item', 'value', 'type']);
        
        foreach ($settings as $setting)
        {
        	if ($setting->type === 'bool') {
                $config[$setting->item] = (bool) $setting->value;
            } else if ($setting->type == 'array') {
                $config[$setting->item] = $setting->value;
            } else {
                $config[$setting->item] = (string) $setting->value;
            }
        }

        $this->view()
            //->registerClass('Setting', Setting::class)
            ->assign('settings', $config)
            ->assign('payment_gateways', self::return_gateways_list())
            ->display('admin/setting.tpl');
        return $response;
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function save($request, $response, $args)
    {
        $class = $request->getParam('class');

        switch ($class) {
            case 'website_general':
                $list = array('website_general_url', 'website_general_name', 'website_general_landing_index');
                break;
            case 'website_security':
                $list = array('website_security_token');
                break;
            case 'website_backend':
                $list = array('website_backend_token');
                break;
            case 'user_general':
                $list = array('user_general_free_user_reset_day', 'user_general_free_user_reset_traffic', 'user_general_class_expire_reset_traffic', 'enable_reset_traffic_when_purchase_user_general', 'enable_add_times_when_purchase_user_general', 'enable_change_username_user_general', 'enable_change_email_user_general', 'enable_delete_account_user_general');
                break;
            case 'user_checkin':
                $list = array('enable_user_checkin', 'user_checkin_get_traffic_value');
                break;
            case 'user_notify':
                $list = array('enable_insufficient_traffic_user_notify');
                break;
            // 支付
            case 'payment_gateway':
                $list = array ('alipay_payment', 'wechatpay_payment', 'cryptopay_payment');
                break;
            case 'f2f_pay':
                $list = array('f2f_pay_app_id', 'f2f_pay_pid', 'f2f_pay_public_key', 'f2f_pay_private_key', 'f2f_pay_notify_url');
                break;
            case 'vmq_pay':
                $list = array('vmq_gateway', 'vmq_key');
                break;
            case 'payjs_pay':
                $list = array('payjs_mchid', 'payjs_key');
                break;
            case 'theadpay':
                $list = array('theadpay_url', 'theadpay_mchid', 'theadpay_key');
                break;
            case 'paytaro':
                $list = array('paytaro_app_id', 'paytaro_app_secret');
                break;
            case 'paybeaver':
                $list = array('paybeaver_app_id', 'paybeaver_app_secret');
                break;
            case 'tronapipay':
                $list = array('tronapipay_public_key', 'tronapipay_private_key');
                break;
            case 'paymentwall':
                $list = array('pmw_publickey', 'pmw_privatekey', 'pmw_widget', 'pmw_height');
                break;
            case 'stripe':
                $list = array('stripe_card', 'stripe_currency', 'stripe_pk', 'stripe_sk', 'stripe_webhook_key', 'stripe_min_recharge', 'stripe_max_recharge');
                break;
            case 'epay':
                $list = array('epay_url', 'epay_pid', 'epay_key');
                break;
            // 邮件
            case 'mail':
                $list = array('mail_driver');
                break;
            case 'smtp':
                $list = array('smtp_host', 'smtp_username', 'smtp_password', 'smtp_port', 'smtp_name', 'smtp_sender', 'smtp_ssl', 'smtp_bbc');
                break;
            case 'mailgun':
                $list = array('mailgun_key', 'mailgun_domain', 'mailgun_sender');
                break;
            case 'sendgrid':
                $list = array('sendgrid_key', 'sendgrid_sender', 'sendgrid_name');
                break;
            case 'ses':
                $list = array('aws_access_key_id', 'aws_secret_access_key');
                break;
            // 验证码
            case 'verify_code':
                $list = array('captcha_provider', 'enable_reg_captcha', 'enable_login_captcha', 'enable_checkin_captcha');
                break;
            case 'verify_code_recaptcha':
                $list = array('recaptcha_sitekey', 'recaptcha_secret');
                break;
            case 'verify_code_geetest':
                $list = array('geetest_id', 'geetest_key');
                break;
            // 备份
            case 'email_backup':
                $list = array('auto_backup_email', 'auto_backup_password', 'auto_backup_notify');
                break;
            // 客户服务
            case 'admin_contact':
                $list = array('enable_admin_contact', 'admin_contact1', 'admin_contact2', 'admin_contact3');
                break;
            case 'web_customer_service_system':
                $list = array('live_chat', 'tawk_id', 'crisp_id', 'livechat_id', 'mylivechat_id');
                break;
            // 个性化
            case 'background_image':
                $list = array('user_center_bg', 'admin_center_bg', 'user_center_bg_addr', 'admin_center_bg_addr');
                break;
            // 注册设置
            case 'register':
                $list = array('reg_mode', 'reg_email_verify', 'email_verify_ttl', 'email_verify_ip_limit');
                break;
            case 'register_default_value':
                $list = array('sign_up_for_free_traffic', 'sign_up_for_free_time', 'sign_up_for_class', 'sign_up_for_class_time', 'sign_up_for_invitation_codes', 'connection_device_limit', 'connection_rate_limit', 'sign_up_for_method', 'sign_up_for_protocol', 'sign_up_for_protocol_param', 'sign_up_for_obfs', 'sign_up_for_obfs_param', 'sign_up_for_daily_report');
                break;
            // 邀请设置
            case 'invitation_reward':
                $list = array('invitation_to_register_balance_reward', 'invitation_to_register_traffic_reward');
                break;
            // 返利设置
            case 'rebate_mode':
                $list = array('invitation_mode', 'invite_rebate_mode', 'rebate_ratio', 'rebate_frequency_limit', 'rebate_amount_limit', 'rebate_time_range_limit');
                break;
            // 提现设置
            case 'withdraw':
                $list = array('enable_withdraw', 'withdraw_less_amount', 'withdraw_method');
                break;
            // 闪购设置
            case 'flash_sell':
                $list = array('enable_flash_sell', 'flash_sell_product_id', 'flash_sell_product_name', 'flash_sell_start_time');
                break;
            // 货币设置
            case 'currency':
                $list = array('enable_currency', 'setting_currency','currency_exchange_rate', 'currency_exchange_rate_api_key');
                break;
            // 代理设置
            case 'sales_agent':
                $list = array('enable_sales_agent', 'purchase_sales_agent_price', 'sales_agent_commission_ratio');
                break;
            case 'telegram_general':
                $list = array('telegram_general_admin_id', 'telegram_general_group_id', 'telegram_general_channel_id');
                break;
            case 'telegram_bot':
                $list = array('enable_telegram_bot', 'enable_new_telegram_bot', 'enable_telegram_bot_group_quiet', 'enable_telegram_bot_menu_show_join_group', 'telegram_bot_token', 'telegram_bot_id', 'telegram_bot_request_token');
                break;
            case 'telegram_notify':
                $list = array('enable_sell_telegram_notify', 'enable_ticket_telegram_notify', 'enable_welcome_message_telegram_notify', 'enable_finance_report_telegram_notify', 'enable_system_report_telegram_notify', 'enable_system_clean_database_report_telegram_notify', 'enable_system_node_offline_report_telegram_notify', 'enable_system_node_online_report_telegram_notify');
                break;
            case 'telegram_notify_content';
                $list = array('diy_system_report_telegram_notify_content', 'diy_system_clean_database_report_telegram_notify_content', 'diy_system_node_offline_report_telegram_notify_content', 'diy_system_node_online_report_telegram_notify_content');
                break;
            case 'subscribe_general';
                $list = array('enable_subscribe', 'subscribe_address_url', 'enable_subscribe_emoji', 'enable_subscribe_extend', 'enable_subscribe_change_token_when_change_passwd', 'enable_subscribe_log', 'subscribe_log_save_days', 'subscribe_diy_message', 'subscribe_clash_default_profile', 'subscribe_surge_default_profile', 'subscribe_surfboard_default_profile');
                break;
        }

        foreach ($list as $item)
        {
            $setting = Setting::where('item', '=', $item)->first();

            if ($setting->type == 'array') {               
                $setting->value = json_encode($request->getParam($item));
            } else {
                $setting->value = $request->getParam($item);
            }
            if(!$setting->save()) {
                return $response->withJson([
                    'ret' => 0,
                    'msg' => "保存 $item 时出错"
                ]);
            }
        }

        return $response->withJson([
            'ret' => 1,
            'msg' => "保存成功"
        ]);
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function test($request, $response, $args)
    {
        $to = $request->getParam('recipient');

        try {
            Mail::send(
                $to,
                '测试邮件',
                'news/welcome.tpl',
                [],
                []
            );
        } catch (Exception $e) {
            return $response->withJson([
                'ret' => 0,
                'msg' => '测试邮件发送失败'
            ]);
        }
        return $response->withJson([
            'ret' => 1,
            'msg' => '测试邮件发送成功'
        ]);
    }

    public function return_gateways_list()
    {
        $payment_gateways = array(
            // 网关名 网关代号
            "Paytaro" => "paytaro",
            "Paybeaver" => "paybeaver",
            "TronapiPay" => 'tronapipay',
            "当面付" => "f2fpay",
            "PayJs" => "payjs",
            "PaymentWall" => "paymentwall",
            "TheadPay" => "theadpay",
            "Stripe" => "stripe",
            "V免签" => "vmqpay",
            "易支付" => "epay"
        );

        return $payment_gateways;
    }


    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function payment($request, $response, $args)
    {
        $gateway_in_use = array();
        $payment_gateways = self::return_gateways_list();
        foreach ($payment_gateways as $key => $value)
        {
            $payment_switch = $request->getParam("$value");
            if ($payment_switch == '1') {
                array_push($gateway_in_use, $value);
            }
        }

        $gateway = Setting::where('item', '=', 'payment_gateway')->first();
        $gateway->value = json_encode($gateway_in_use);
        $gateway->save();

        return $response->withJson([
            'ret' => 1,
            'msg' => "保存成功"
        ]);
    }
}