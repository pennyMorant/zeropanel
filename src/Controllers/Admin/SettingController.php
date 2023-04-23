<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminController;
use App\Models\Setting;
use Slim\Http\Response;
use Slim\Http\ServerRequest;
use App\Services\Mail;
use Exception;

class SettingController extends AdminController
{
    public function index(ServerRequest $request, Response $response, array $args)
    {
        $config = [];
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

    public function save(ServerRequest $request, Response $response, array $args): Response
    {
        $class = $request->getParsedBodyParam('class');

        switch ($class) {
            case 'website':
                $list = [
                    'website_url', 
                    'website_name', 
                    'website_landing_index', 
                    'website_security_token',
                    'website_backend_token', 
                    'website_auth_background_image', 
                    'website_admin_path'
                ];
                break;
            case 'permission_group':
                $list = [
                    'enable_permission_group', 
                    'permission_group_detail'
                ];
                break;         
            // 邮件
            case 'mail':
                $list = [
                    'mail_driver'
                ];
                break;
            case 'smtp':
                $list = [
                    'smtp_host', 
                    'smtp_username', 
                    'smtp_password', 
                    'smtp_port', 
                    'smtp_name', 
                    'smtp_sender', 
                    'smtp_ssl'
                ];
                break;
            case 'mailgun':
                $list = [
                    'mailgun_key', 
                    'mailgun_domain', 
                    'mailgun_sender'
                ];
                break;
            case 'sendgrid':
                $list = [
                    'sendgrid_key', 
                    'sendgrid_sender', 
                    'sendgrid_name'
                ];
                break;
            case 'ses':
                $list = [
                    'aws_access_key_id', 
                    'aws_secret_access_key'
                ];
                break;
            // 验证码
            case 'captcha':
                $list = [
                    'captcha_provider',
                    'enable_signup_captcha', 
                    'enable_signin_captcha', 
                    'turnstile_sitekey', 
                    'turnstile_secret'
                ];
                break;
            // 备份
            case 'backup':
                $list = [
                    'auto_backup_email', 
                    'auto_backup_password', 
                    'auto_backup_notify'
                ];
                break;
            // 客户服务
            case 'live_chat':
                $list = [
                    'live_chat', 
                    'tawk_id', 
                    'crisp_id', 
                    'livechat_id', 
                    'mylivechat_id'
                ];
                break;          
            // 注册设置
            case 'register':
                $list = [
                    'reg_mode', 
                    'signup_default_traffic', 
                    'signup_default_class', 
                    'signup_default_class_time', 
                    'signup_default_ip_limit', 
                    'signup_default_speed_limit',
                    'verify_email'
                ];
                break;
            // 邀请设置
            case 'invite':
                $list = [
                    'invitation_to_signup_credit_reward', 
                    'invitation_to_signup_traffic_reward', 
                    'invitation_mode', 
                    'invite_rebate_mode', 
                    'rebate_ratio', 
                    'rebate_frequency_limit', 
                    'rebate_amount_limit', 
                    'rebate_time_range_limit'
                ];
                break;
            // 提现设置
            case 'withdraw':
                $list = [
                    'enable_withdraw', 
                    'withdraw_minimum_amount', 
                    'withdraw_method'
                ];
                break;
            // 货币设置
            case 'currency':
                $list = [
                    'enable_currency', 
                    'currency_unit',
                    'currency_exchange_rate', 
                    'currency_exchange_rate_api_key'
                ];
                break;
            // 代理设置
            case 'sales_agent':
                $list = [
                    'enable_sales_agent', 
                    'purchase_sales_agent_price', 
                    'sales_agent_commission_ratio'
                ];
                break;
            case 'telegram':
                $list = [
                    'telegram_admin_id', 
                    'telegram_group_id', 
                    'telegram_group_url', 
                    'telegram_channel_id'
                ];
                break;
            case 'telegram_bot':
                $list = [
                    'enable_push_top_up_message', 
                    'enable_push_ticket_message', 
                    'enable_push_system_report', 
                    'enable_telegram_bot', 
                    'telegram_bot_token', 
                    'telegram_bot_id', 
                    'telegram_bot_request_token'
                ];
                break;
            case 'telegram_notify_content';
                $list = [
                    'diy_system_report_telegram_notify_content', 
                    'diy_system_clean_database_report_telegram_notify_content', 
                    'diy_system_node_offline_report_telegram_notify_content', 
                    'diy_system_node_online_report_telegram_notify_content'
                ];
                break;
            case 'subscribe';
                $list = [
                    'subscribe_address_url', 
                    'enable_subscribe_emoji', 
                    'enable_subscribe_extend', 
                    'enable_subscribe_log', 
                    'subscribe_log_keep_time', 
                    'subscribe_diy_message', 
                    'subscribe_clash_default_profile', 
                    'subscribe_surge_default_profile', 
                    'subscribe_surfboard_default_profile'
                ];
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
    public function test(ServerRequest $request, Response $response, array $args)
    {
        $to = $request->getParam('email_address');

        try {
            Mail::send(
                $to,
                '测试邮件',
                'auth/test.tpl',
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
}