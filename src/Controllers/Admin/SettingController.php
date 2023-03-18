<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminController;
use App\Models\{
    Setting
};
use Slim\Http\Response;
use Slim\Http\ServerRequest;
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
    public function index(ServerRequest $request, Response $response, $args)
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
            ->registerClass('Setting', Setting::class)
            ->assign('settings', $config)
            ->display('admin/setting.tpl');
        return $response;
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function save(ServerRequest $request, Response $response, $args)
    {
        $class = $request->getParam('class');

        switch ($class) {
            case 'website':
                $list = array('website_url', 'website_name', 'website_landing_index', 'website_security_token','website_backend_token');
                break;          
            // 支付
            case 'payment_gateway':
                $list = array ('alipay_payment', 'wechatpay_payment', 'cryptopay_payment');
                break;
            case 'f2f_pay':
                $list = array('f2f_pay_app_id', 'f2f_pay_pid', 'f2f_pay_public_key', 'f2f_pay_private_key', 'f2f_pay_notify_url');
                break;
            case 'vmqpay':
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
                $list = array('smtp_host', 'smtp_username', 'smtp_password', 'smtp_port', 'smtp_name', 'smtp_sender', 'smtp_ssl');
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
            case 'captcha':
                $list = array('captcha_provider', 'enable_signup_captcha', 'enable_signin_captcha', 'turnstile_sitekey', 'turnstile_secret');
                break;
            // 备份
            case 'backup':
                $list = array('auto_backup_email', 'auto_backup_password', 'auto_backup_notify');
                break;
            // 客户服务
            case 'live_chat':
                $list = array('live_chat', 'tawk_id', 'crisp_id', 'livechat_id', 'mylivechat_id');
                break;          
            // 注册设置
            case 'register':
                $list = array('reg_mode', 'reg_email_verify', 'email_verify_ttl', 'email_verify_ip_limit', 'signup_default_traffic', 'signup_default_class', 'signup_default_class_time', 'signup_default_ip_limit', 'signup_default_speed_limit');
                break;
            // 邀请设置
            case 'invite':
                $list = array('invitation_to_signup_credit_reward', 'invitation_to_signup_traffic_reward', 'invitation_mode', 'invite_rebate_mode', 'rebate_ratio', 'rebate_frequency_limit', 'rebate_amount_limit', 'rebate_time_range_limit');
                break;
            // 提现设置
            case 'withdraw':
                $list = array('enable_withdraw', 'withdraw_minimum_amount', 'withdraw_method');
                break;
            // 货币设置
            case 'currency':
                $list = array('enable_currency', 'currency_unit','currency_exchange_rate', 'currency_exchange_rate_api_key');
                break;
            // 代理设置
            case 'sales_agent':
                $list = array('enable_sales_agent', 'purchase_sales_agent_price', 'sales_agent_commission_ratio');
                break;
            case 'telegram':
                $list = array('telegram_admin_id', 'telegram_group_id', 'telegram_group_url', 'telegram_channel_id');
                break;
            case 'telegram_bot':
                $list = array('enable_push_top_up_message', 'enable_push_ticket_message', 'enable_push_system_report', 'enable_telegram_bot', 'telegram_bot_token', 'telegram_bot_id', 'telegram_bot_request_token');
                break;
            case 'telegram_notify_content';
                $list = array('diy_system_report_telegram_notify_content', 'diy_system_clean_database_report_telegram_notify_content', 'diy_system_node_offline_report_telegram_notify_content', 'diy_system_node_online_report_telegram_notify_content');
                break;
            case 'subscribe';
                $list = array('subscribe_address_url', 'enable_subscribe_emoji', 'enable_subscribe_extend', 'enable_subscribe_log', 'subscribe_log_keep_time', 'subscribe_diy_message', 'subscribe_clash_default_profile', 'subscribe_surge_default_profile', 'subscribe_surfboard_default_profile');
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
    public function test(ServerRequest $request, Response $response, $args)
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
    public function payment(ServerRequest $request, Response $response, $args)
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