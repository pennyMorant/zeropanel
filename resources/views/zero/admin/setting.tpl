<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{$config["appName"]} Dashboard</title>
        <link href="/theme/zero/assets/css/zero.css" rel="stylesheet" type="text/css"/>
        <meta charset="UTF-8" />
        <meta name="renderer" content="webkit" />
        <meta name="description" content="Updates and statistics" />
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="format-detection" content="telephone=no,email=no" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta name="theme-color" content="#3B5598" />
        <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" />
        <meta http-equiv="Cache-Control" content="no-siteapp" />
        <meta http-equiv="pragma" content="no-cache">
        <meta http-equiv="Cache-Control" content="no-cache, must-revalidate">
        <meta http-equiv="expires" content="0">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
        <link href="/theme/zero/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
        <link href="/theme/zero/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
        <link href="/theme/zero/assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
        <link href="/favicon.png" rel="shortcut icon">
        <link href="/apple-touch-icon.png" rel="apple-touch-icon">
    </head>
	{include file ='admin/menu.tpl'}
                    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                        <div class="d-flex flex-column flex-column-fluid mt-10">
                            <div id="kt_app_content" class="app-content flex-column-fluid">
                                <div id="kt_app_content_container" class="app-container container-xxl">
                                    <div class="card">
                                        <div class="card-body">
                                            <nav>
                                                <div class="nav nav-tabs mb-10" id="nav-tab" role="tablist">
                                                  <button class="nav-link active fw-bolder fs-3" id="zero_admin_nav_website_tab" data-bs-toggle="tab" data-bs-target="#zero_admin_nav_website" type="button" role="tab" aria-controls="zero_admin_nav_website" aria-selected="true">基础</button>
                                                  <button class="nav-link fw-bolder fs-3" id="zero_admin_nav_payment_tab" data-bs-toggle="tab" data-bs-target="#zero_admin_nav_payment" type="button" role="tab" aria-controls="zero_admin_nav_payment" aria-selected="false">支付</button>
                                                  <button class="nav-link fw-bolder fs-3" id="zero_admin_nav_email_tab" data-bs-toggle="tab" data-bs-target="#zero_admin_nav_email" type="button" role="tab" aria-controls="zero_admin_nav_email" aria-selected="false">邮件</button>
                                                  <button class="nav-link fw-bolder fs-3" id="zero_admin_nav_tg_tab" data-bs-toggle="tab" data-bs-target="#zero_admin_nav_tg" type="button" role="tab" aria-controls="zero_admin_nav_tg" aria-selected="false">Telegram</button>
                                                  <button class="nav-link fw-bolder fs-3" id="zero_admin_nav_sub_tab" data-bs-toggle="tab" data-bs-target="#zero_admin_nav_sub" type="button" role="tab" aria-controls="zero_admin_nav_sub" aria-selected="false">订阅</button>
                                                  <button class="nav-link fw-bolder fs-3" id="zero_admin_nav_sell_tab" data-bs-toggle="tab" data-bs-target="#zero_admin_nav_sell" type="button" role="tab" aria-controls="zero_admin_nav_sell" aria-selected="false">销售</button>
                                                  <button class="nav-link fw-bolder fs-3" id="zero_admin_nav_account_tab" data-bs-toggle="tab" data-bs-target="#zero_admin_nav_account" type="button" role="tab" aria-controls="zero_admin_nav_account" aria-selected="false">账户</button>
                                                  <button class="nav-link fw-bolder fs-3" id="zero_admin_nav_referral_tab" data-bs-toggle="tab" data-bs-target="#zero_admin_nav_referral" type="button" role="tab" aria-controls="zero_admin_nav_referral" aria-selected="false">推荐</button>
                                                </div>
                                            </nav>
                                            <div class="tab-content" id="nav-tabContent">
                                                <div class="tab-pane fade show active" id="zero_admin_nav_website" role="tabpanel" aria-labelledby="zero_admin_nav_website_tab" tabindex="0">
                                                    <div class="card card-bordered">
                                                        <div class="card-header">
                                                            <div class="card-title">基础配置</div>
                                                            <div class="card-toolbar">
                                                                <button class="btn btn-light-primary btn-sm">保存配置</button>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row g-5">
                                                                <div class="col-xxl-6">
                                                                    <label class="form-label">网站地址</label>
                                                                    <input class="form-control mb-5" id="website_url" name="website_url" type="text" placeholder="网站地址" value="" />
                                                                    <label class="form-label">网站名称</label>
                                                                    <input class="form-control mb-5" id="website_name" name="website_name" type="text" placeholder="网站名称" value="" />
                                                                    <label class="form-label">LANDING INDEX</label>
                                                                    <input class="form-control mb-5" id="website_landing_index" name="website_landing_index" type="text" placeholder="" value="" />
                                                                </div>
                                                                <div class="col-xxl-6">
                                                                    <label class="form-label">安全TOKEN</label>
                                                                    <input class="form-control mb-5" id="website_security_token" name="website_security_token" type="text" placeholder="TOKEN" value="" />
                                                                    <label class="form-label">后端TOKEN</label>
                                                                    <input class="form-control mb-5" id="website_backend_token" name="website_backend_token" type="text" placeholder="token" value="" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="zero_admin_nav_payment" role="tabpanel" aria-labelledby="zero_admin_nav_payment_tab" tabindex="0">
                                                    <div class="card card-bordered">
                                                        <div class="card-header">
                                                            <div class="card-title">支付配置</div>
                                                            <div class="card-toolbar">
                                                                <button class="btn btn-light-primary btn-sm" type="button">保存配置</button>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row g-5">
                                                                <div class="col-xxl-6">
                                                                    <label class="form-label">支付宝</label>
                                                                    <select class="form-select mb-5" id="alipay_payment" data-control="select2" data-hide-search="true">
                                                                        <option value="">关闭</option>
                                                                        <option value="paybeaver">海狸支付</option>
                                                                        <option value="paytaro">Paytaro</option>
                                                                        <option value="stripe">Stripe</option>
                                                                        <option value="easypay">易支付</option>
                                                                    </select>
                                                                    <label class="form-label">微信</label>
                                                                    <select class="form-select mb-5" id="wechatpay_payment" data-control="select2" data-hide-search="true">
                                                                        <option value="">关闭</option>
                                                                        <option value="paybeaver">海狸支付</option>
                                                                        <option value="paytaro">Paytaro</option>
                                                                        <option value="stripe">Stripe</option>
                                                                        <option value="easypay">易支付</option>
                                                                    </select>
                                                                    
                                                                </div>
                                                                <div class="col-xxl-6">
                                                                    <label class="form-label">虚拟币</label>
                                                                    <select class="form-select mb-5" id="cryptopay_payment" data-control="select2" data-hide-search="true">
                                                                        <option value="">关闭</option>
                                                                        <option value="tronapipay">TronapiPay</option>
                                                                    </select>
                                                                    <label class="form-label">QQ钱包</label>
                                                                    <select class="form-select mb-5" id="qqpay_payment" data-control="select2" data-hide-search="true">
                                                                        <option value="">关闭</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                        </div>                                                      
                                                    </div>
                                                    <div class="separator border-primary my-10"></div>
                                                    <div class="row g-5">
                                                        <div class="col-xxl-6">
                                                            <div class="card card-bordered mb-5">
                                                                <div class="card-header">
                                                                    <div class="card-title">海狸支付</div>
                                                                    <div class="card-toolbar">
                                                                        <button class="btn btn-light-primary btn-sm fw-bold" type="button">保存配置</button>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="card-body">
                                                                    <label class="form-label">账号</label>
                                                                    <input class="form-control mb-5" id="paybeaver_app_id" value="" type="text" placeholder="账号">
                                                                    <label class="form-label">密钥</label>
                                                                    <input class="form-control mb-5" id="paybeaver_app_secret" value="" type="text" placeholder="密钥">
                                                                </div>
                                                            </div>
                                                            <div class="card card-bordered mb-5">
                                                                <div class="card-header">
                                                                    <div class="card-title">PayTaro</div>
                                                                    <div class="card-toolbar">
                                                                        <button class="btn btn-light-primary btn-sm fw-bold" type="button">保存配置</button>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <label class="form-label">账号</label>
                                                                    <input class="form-control mb-5" id="paytaro_app_id" value="" type="text" placeholder="账号">
                                                                    <label class="form-label">密钥</label>
                                                                    <input class="form-control mb-5" id="paytaro_app_secret" value="" type="text" placeholder="密钥">
                                                                </div>
                                                            </div>
                                                            <div class="card card-bordered">
                                                                <div class="card-header">
                                                                    <div class="card-title">TronapiPay</div>
                                                                    <div class="card-toolbar">
                                                                        <button class="btn btn-light-primary btn-sm fw-bold" type="button">保存配置</button>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <label class="form-label">账号</label>
                                                                    <input class="form-control mb-5" id="tronapipay_public_key" value="" type="text" placeholder="账号">
                                                                    <label class="form-label">密钥</label>
                                                                    <input class="form-control mb-5" id="tronapipay_private_key" value="" type="text" placeholder="密钥">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xxl-6">
                                                            <div class="card card-bordered mb-5">
                                                                <div class="card-header">
                                                                    <div class="card-title">Stripe</div>
                                                                    <div class="card-toolbar">
                                                                        <button class="btn btn-light-primary btn-sm fw-bold" type="button">保存配置</button>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <label class="form-label">账号</label>
                                                                    <input class="form-control mb-5" placeholder="账号">
                                                                    <label class="form-label">密钥</label>
                                                                    <input class="form-control mb-5" placeholder="密钥">
                                                                </div>
                                                            </div>
                                                            <div class="card card-bordered">
                                                                <div class="card-header">
                                                                    <div class="card-title">易支付</div>
                                                                    <div class="card-toolbar">
                                                                        <button class="btn btn-light-primary btn-sm fw-bold" type="button">保存配置</button>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <label class="form-label">URL</label>
                                                                    <input class="form-control mb-5" id="epay_url" value="" type="text" placeholder="URL">
                                                                    <label class="form-label">账号</label>
                                                                    <input class="form-control mb-5" id="epay_pid" value="" type="text" placeholder="账号">
                                                                    <label class="form-label">密钥</label>
                                                                    <input class="form-control mb-5" id="epay_key" value="" type="text" placeholder="密钥">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="zero_admin_nav_email" role="tabpanel" aria-labelledby="zero_admin_nav_email_tab" tabindex="0">
                                                    <div class="row g-5">
                                                        <div class="col-xxl-6">                                                   
                                                            <div class="card card-bordered mb-5">
                                                                <div class="card-header">
                                                                    <div class="card-title">邮件配置</div>
                                                                    <div class="card-toolbar">
                                                                        <button class="btn btn-light-primary btn-sm">保存配置</button>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <label class="form-label">邮件服务商</label>
                                                                    <select class="form-select" id="mail_driver" data-control="select2" data-hide-search="true">
                                                                        <option value="none" {if $settings['mail_driver'] == "none"}selected{/if}>none</option>
                                                                        <option value="mailgun" {if $settings['mail_driver'] == "mailgun"}selected{/if}>mailgun</option>
                                                                        <option value="sendgrid" {if $settings['mail_driver'] == "sendgrid"}selected{/if}>sendgrid</option>
                                                                        <option value="ses" {if $settings['mail_driver'] == "ses"}selected{/if}>ses</option>
                                                                        <option value="smtp" {if $settings['mail_driver'] == "smtp"}selected{/if}>smtp</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="card card-bordered">
                                                                <div class="card-header">
                                                                    <div class="card-title">邮件备份</div>
                                                                    <div class="card-toolbar">
                                                                        <button class="btn btn-light-primary btn-sm">保存配置</button>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <label class="form-label">接收备份的邮箱</label>
                                                                    <input class="form-control" id="auto_backup_email" value="" type="text" placeholder="邮箱" />
                                                                    <label class="form-label">备份的压缩密码</label>
                                                                    <input class="form-control" id="auto_backup_password" value="" type="text" placeholder="密码" />
                                                                    <label class="form-label">备份成功推送TG消息</label>
                                                                    <select class="form-select" id="auto_backup_notify" data-control="select2" data-hide-search="true">
                                                                        <option value="0" {if $settings['auto_backup_notify'] == "0"}selected{/if}>关闭</option>
                                                                        <option value="1" {if $settings['auto_backup_notify'] == "1"}selected{/if}>开启</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xxl-6">
                                                            <div class="card card-bordered mb-5">
                                                                <div class="card-header">
                                                                    <div class="card-title">邮件测试</div>
                                                                    <div class="card-toolbar">
                                                                        <button class="btn btn-light-primary btn-sm">测试</button>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <label class="form-label">账号</label>
                                                                    <input class="form-control" id="email_send_test" value="" type="text" placeholder="账号" />
                                                                </div>
                                                            </div>
                                                            <div class="card card-bordered">
                                                                <div class="card-header">
                                                                    <div class="card-title">SENDGRID 配置</div>
                                                                    <div class="card-toolbar">
                                                                        <button class="btn btn-light-primary btn-sm">保存配置</button>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <label class="form-label">密钥</label>
                                                                    <input class="form-control" id="sendgrid_key" value="" type="text" placeholder="密钥" />
                                                                    <label class="form-label">发信邮箱</label>
                                                                    <input class="form-control" id="sendgrid_sender" value="" type="text" placeholder="邮箱" />
                                                                    <label class="form-label">发信名称</label>
                                                                    <input class="form-control" id="sendgrid_name" value="" type="text" placeholder="发信名称" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="separator border-primary my-10"></div>
                                                    <div class="row g-5">
                                                        <div class="col-xxl-6">
                                                            <div class="card card-bordered">
                                                                <div class="card-header">
                                                                    <div class="card-title">SMTP 配置</div>
                                                                    <div class="card-toolbar">
                                                                        <button class="btn btn-light-primary btn-sm" type="button">保存配置</button>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <label class="form-label">SMTP 主机地址</label>
                                                                    <input class="form-control mb-5" id="smtp_host" value="{$settings['smtp_host']}" type="text" />
                                                                    <label class="form-label">SMTP 账户名</label>
                                                                    <input class="form-control mb-5" id="smtp_username" value="{$settings['smtp_username']}" type="text" />
                                                                    <label class="form-label">SMTP 账户密码</label>
                                                                    <input class="form-control mb-5" id="smtp_password" value="{$settings['smtp_password']}" type="text" />
                                                                    <label class="form-label">SMTP 端口</label>
                                                                    <select class="form-select mb-5" id="smtp_port" data-control="select2" data-hide-search="true">
                                                                        <option value="465" {if $settings['smtp_port'] == "465"}selected{/if}>465</option>
                                                                        <option value="587" {if $settings['smtp_port'] == "587"}selected{/if}>587</option>
                                                                        <option value="2525" {if $settings['smtp_port'] == "2525"}selected{/if}>2525</option>
                                                                        <option value="25" {if $settings['smtp_port'] == "25"}selected{/if}>25</option>
                                                                    </select>
                                                                    <label class="form-label">SMTP 发信名称</label>
                                                                    <input class="form-control mb-5" id="smtp_sender" value="{$settings['smtp_sender']}" type="text" />
                                                                    <label class="form-label">是否使用 TLS/SSL 发信</label>
                                                                    <select id="smtp_ssl" class="form-select mb-5" data-control="select2" data-hide-search="true">
                                                                        <option value="1" {if $settings['smtp_ssl'] == true}selected{/if}>开启</option>
                                                                        <option value="0" {if $settings['smtp_ssl'] == false}selected{/if}>关闭</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xxl-6">
                                                            <div class="card card-bordered mb-5">
                                                                <div class="card-header">
                                                                    <div class="card-title">MAILGUN 配置</div>
                                                                    <div class="card-toolbar">
                                                                        <button class="btn btn-light-primary btn-sm">保存配置</button>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <label class="form-label">密钥</label>
                                                                    <input class="form-control" id="mailgun_key" value="" type="text" placeholder="密钥" />
                                                                    <label class="form-label">域名</label>
                                                                    <input class="form-control" id="mailgun_domain" value="" type="text" placeholder="域名" />
                                                                    <label class="form-label">发信名称</label>
                                                                    <input class="form-control" id="mailgun_sender" value="" type="text" placeholder="发信名称" />
                                                                </div>
                                                            </div>
                                                            <div class="card card-bordered">
                                                                <div class="card-header">
                                                                    <div class="card-title">SES 配置</div>
                                                                    <div class="card-toolbar">
                                                                        <button class="btn btn-light-primary btn-sm">保存配置</button>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <label class="form-label">密钥 ID</label>
                                                                    <input class="form-control" id="aws_access_key_id" value="" type="text" placeholder="密钥 ID" />
                                                                    <label class="form-label">密钥 KEY</label>
                                                                    <input class="form-control" id="aws_secret_access_key" value="" type="text" placeholder="密钥 KEY" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="zero_admin_nav_tg" role="tabpanel" aria-labelledby="zero_admin_nav_tg_tab" tabindex="0">
                                                    <div class="row g-5">
                                                        <div class="col-xxl-6">
                                                            <div class="card card-bordered">
                                                                <div class="card-header">
                                                                    <div class="card-title">Telegram 配置</div>
                                                                    <div class="card-toolbar">
                                                                        <button class="btn btn-light-primary btn-sm">保存配置</button>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <label class="form-label">群组 ID</label>
                                                                    <input class="form-control mb-5" id="telegram_group_id" value="" type="text" placeholder="ID" />
                                                                    <label class="form-label">群组账号</label>
                                                                    <input class="form-control mb-5" id="telegram_group_account" value="" type="text" placeholder="账号" />
                                                                    <label class="form-label">频道账号</label>
                                                                    <input class="form-control mb-5" id="telegram_channel_id" value="" type="text" placeholder="账号" />
                                                                    <label class="form-label">ADMIN ID</label>
                                                                    <input class="form-control" id="telegram_admin_id" value="" type="text" placeholder="ID" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xxl-6">
                                                            <div class="card card-bordered">
                                                                <div class="card-header">
                                                                    <div class="card-title">Telegram BOT</div>
                                                                    <div class="card-toolbar">
                                                                        <button class="btn btn-light-primary btn-sm">保存配置</button>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <label class="form-label">启用BOT</label>
                                                                    <select class="form-select mb-5" id="enable_telegram_bot" data-control="select2" data-hide-search="true">
                                                                        <option value="0" {if $settings['enable_telegram_bot'] == false}selected{/if}>关闭</option>
                                                                        <option value="1" {if $settings['enable_telegram_bot'] == true}selected{/if}>开启</option>
                                                                    </select>
                                                                    <label class="form-label">BOT TOKEN</label>
                                                                    <input class="form-control mb-5" id="telegram_bot_toekn" value="" type="text" placeholder="TOKEN" />
                                                                    <label class="form-label">BOT ID</label>
                                                                    <input class="form-control mb-5" id="telegram_bot_id" value="" type="text" placeholder="BOT ID" />
                                                                    <label class="form-label">请求 TOKEN</label>
                                                                    <input class="form-control mb-5" id="telegram_bot_request_token" value="" type="text" placeholder="TOKEN" />
                                                                    <label class="form-label">BOT 推送充值消息</label>
                                                                    <select class="form-select mb-5" id="enable_push_top_up_message" data-control="select2" data-hide-search="true">
                                                                        <option value="0" >关闭</option>
                                                                        <option value="1">开启</option>
                                                                    </select>
                                                                    <label class="form-label">BOT 推送工单消息</label>
                                                                    <select class="form-select mb-5" id="enable_push_ticket_message" data-control="select2" data-hide-search="true">
                                                                        <option value="0" >关闭</option>
                                                                        <option value="1">开启</option>
                                                                    </select>
                                                                    <label class="form-label">BOT 推送系统运行情况</label>
                                                                    <select class="form-select mb-5" id="enable_push_system_report" data-control="select2" data-hide-search="true">
                                                                        <option value="0" >关闭</option>
                                                                        <option value="1">开启</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="zero_admin_nav_sub" role="tabpanel" aria-labelledby="zero_admin_nav_sub_tab" tabindex="0">                                                   
                                                    <div class="card card-bordered">
                                                        <div class="card-header">
                                                            <div class="card-title">订阅配置</div>
                                                            <div class="card-toolbar">
                                                                <button class="btn btn-light-primary btn-sm">保存配置</button>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row g-5">
                                                                <div class="col-xxl-6">
                                                                    <label class="form-label">订阅地址</label>
                                                                    <input class="form-control mb-5" id="subscribe_address_url" value="" type="text" placeholder="订阅地址" />
                                                                    <label class="form-label">订阅显示流量和时间</label>
                                                                    <select class="form-select mb-5" id="enable_subscribe_extend" data-control="select2" data-hide-search="true">
                                                                        <option value="0" >关闭</option>
                                                                        <option value="1">开启</option>
                                                                    </select>
                                                                    <label class="form-label">订阅显示emoji</label>
                                                                    <select class="form-select mb-5" id="enable_subscribe_emoji" data-control="select2" data-hide-search="true">
                                                                        <option value="0" >关闭</option>
                                                                        <option value="1">开启</option>
                                                                    </select>
                                                                    <label class="form-label">订阅日志记录</label>
                                                                    <select class="form-select mb-5" id="enable_subscribe_log" data-control="select2" data-hide-search="true">
                                                                        <option value="0" >关闭</option>
                                                                        <option value="1">开启</option>
                                                                    </select>
                                                                    <label class="form-label">订阅日志保留时间</label>
                                                                    <input class="form-control" id="subscribe_log_keep_time" value="" type="text" placeholder="保留时间" />
                                                                </div>
                                                                <div class="col-xxl-6">
                                                                    <label class="form-label">订阅营销信息</label>
                                                                    <input class="form-control mb-5" id="subscribe_diy_message" value="" type="text" placeholder="营销信息" />
                                                                    <label class="form-label">CLASH 默认配置</label>
                                                                    <input class="form-control mb-5" id="subscribe_clash_default_profile" value="" type="text" placeholder="默认配置" />
                                                                    <label class="form-label">SURGE 默认配置</label>
                                                                    <input class="form-control mb-5" id="subscribe_surge_default_profile" value="" type="text" placeholder="默认配置" />
                                                                    <label class="form-label">SURFBOARD 默认配置</label>
                                                                    <input class="form-control mb-5" id="subscribe_surfboard_default_profile" value="" type="text" placeholder="默认配置" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>                                                      
                                                </div>
                                                <div class="tab-pane fade" id="zero_admin_nav_sell" role="tabpanel" aria-labelledby="zero_admin_nav_sell_tab" tabindex="0">
                                                    <div class="row g-5">
                                                        <div class="col-xxl-6">
                                                            <div class="card card-bordered">
                                                                <div class="card-header">
                                                                    <div class="card-title">货币配置</div>
                                                                    <div class="card-toolbar">
                                                                        <button class="btn btn-light-primary btn-sm" type="button">保存配置</button>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <label class="form-label">开启其他货币</label>
                                                                    <select class="form-select mb-5" id="enable_currency" data-control="select2" data-hide-search="true"> 
                                                                        <option value="0" >关闭</option>
                                                                        <option value="1">开启</option>
                                                                    </select>
                                                                    <label class="form-label">货币单位</label>
                                                                    <select class="form-select mb-5" id="currency_unit">
                                                                        <option value="USD" data-kt-select2-country="/theme/zero/assets/media/flags/united-states.svg">USD</option>
                                                                        <option value="GBP" data-kt-select2-country="/theme/zero/assets/media/flags/united-kingdom.svg">GBP</option>
                                                                        <option value="CAD" data-kt-select2-country="/theme/zero/assets/media/flags/canada.svg">CAD</option>
                                                                        <option value="HKD" data-kt-select2-country="/theme/zero/assets/media/flags/hong-kong.svg">HKD</option>
                                                                        <option value="JPY" data-kt-select2-country="/theme/zero/assets/media/flags/japan.svg">JPY</option>
                                                                        <option value="SGD" data-kt-select2-country="/theme/zero/assets/media/flags/singapore.svg">SGD</option>
                                                                        <option value="EUR" data-kt-select2-country="/theme/zero/assets/media/flags/european-union.svg">EUR</option>
                                                                    </select>
                                                                    <label class="form-label">货币汇率</label>
                                                                    <input class="form-control mb-5" id="currency_exchange_rate" value="" type="text" placeholder="货币汇率" disabled />
                                                                    <label class="form-label">汇率 API KEY</label>
                                                                    <input class="form-control mb-5" id="currency_exchange_rate_api_key" value="" type="text" placeholder="API KEY" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xxl-6">
                                                            <div class="card card-bordered">
                                                                <div class="card-header">
                                                                    <div class="card-title">提现配置</div>
                                                                    <div class="card-toolbar">
                                                                        <button class="btn btn-light-primary btn-sm" type="button">保存配置</button>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <label class="form-label">开启提现</label>
                                                                    <select class="form-select mb-5" id="enable_withdraw" data-control="select2" data-hide-search="true">
                                                                        <option value="0" >关闭</option>
                                                                        <option value="1">开启</option>
                                                                    </select>
                                                                    <label class="form-label">提现方式</label>
                                                                    <select class="form-select mb-5" id="withdraw_method">
                                                                        <option value="USD" data-kt-select2-image="/tether.svg">USDT</option>
                                                                    </select>
                                                                    <label class="form-label">最低提现金额</label>
                                                                    <input class="form-control mb-5" id="withdraw_minimum_amount" value="" type="text" placeholder="最低金额" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="zero_admin_nav_account" role="tabpanel" aria-labelledby="zero_admin_nav_account_tab" tabindex="0">
                                                    <div class="card card-bordered mb-5">
                                                        <div class="card-header">
                                                            <div class="card-title">注册配置</div>
                                                            <div class="card-toolbar">
                                                                <button class="btn btn-light-primary btn-sm" type="button">保存配置</button>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row g-5">
                                                                <div class="col-xxl-6">
                                                                    <label class="form-label">注册模式</label>
                                                                    <select class="form-select mb-5" id="reg_mode" data-control="select2" data-hide-search="true">
                                                                        <option value="0" >关闭注册</option>
                                                                        <option value="1">开启注册</option>
                                                                        <option value="3">仅限邀请注册</option>
                                                                    </select>
                                                                    <label class="form-label">注册邮箱验证</label>
                                                                    <select class="form-select mb-5" id="reg_email_verify" data-control="select2" data-hide-search="true">
                                                                        <option value="0" >关闭</option>
                                                                        <option value="1">开启</option>
                                                                    </select>
                                                                    <label class="form-label">邮箱验证码有效时间(秒)</label>
                                                                    <input class="form-control mb-5" id="email_verify_ttl" value="" type="text" placeholder="有效时间" />
                                                                    <label class="form-label">验证码有效期内单个ip可请求的发信次数</label>
                                                                    <input class="form-control" id="email_verify_ip_limit" value="" type="text" placeholder="发信次数" />
                                                                </div>
                                                                <div class="col-xxl-6">
                                                                    <label class="form-label">默认等级</label>
                                                                    <input class="form-control mb-5" id="signup_default_class" value="0" type="text" placeholder="默认等级" />
                                                                    <label class="form-label">默认等级时长</label>
                                                                    <input class="form-control mb-5" id="signup_default_class_time" value="0" type="text" placeholder="等级时长" />
                                                                    <label class="form-label">默认流量</label>
                                                                    <input class="form-control mb-5" id="signup_default_traffic" value="0" type="text" placeholder="默认流量" />
                                                                    <label class="form-label">默认IP限制</label>
                                                                    <input class="form-control mb-5" id="signup_default_ip_limit" value="0" type="text" placeholder="IP限制" />
                                                                    <label class="form-label">默认速度限制</label>
                                                                    <input class="form-control" id="signup_default_speed_limit" value="0" type="text" placeholder="速度限制" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card card-bordered mb-5">
                                                        <div class="card-header">
                                                            <div class="card-title">验证配置</div>
                                                            <div class="card-toolbar">
                                                                <button class="btn btn-light-primary btn-sm" type="button">保存配置</button>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row -g-5">
                                                                <div class="col-xxl-6">
                                                                    <label class="form-label">验证码提供商</label>
                                                                    <select id="captcha_provider" class="form-select mb-5" data-control="select2" data-hide-search="true">
                                                                        <option value="turnstile" {if $settings['captcha_provider'] == "turnstile"}selected{/if}>Turnstile</option>
                                                                    </select>
                                                                    <label class="form-label">注册验证码</label>
                                                                    <select id="enable_signup_captcha" class="form-select mb-5" data-control="select2" data-hide-search="true">
                                                                        <option value="0" {if $settings['enable_signup_captcha'] == false}selected{/if}>关闭</option>
                                                                        <option value="1" {if $settings['enable_signup_captcha'] == true}selected{/if}>开启</option>
                                                                    </select>
                                                                    <label class="form-label">登录验证码</label>
                                                                    <select id="enable_signin_captcha" class="form-select" data-control="select2" data-hide-search="true">
                                                                        <option value="0" {if $settings['enable_signin_captcha'] == false}selected{/if}>关闭</option>
                                                                        <option value="1" {if $settings['enable_signin_captcha'] == true}selected{/if}>开启</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-xxl-6">
                                                                    <label class="form-label">Turnstile Site Key</label>
                                                                    <input class="form-select mb-5" id="turnstile_sitekey" value="{$settings['turnstile_sitekey']}">
                                                                    <label class="form-label">Turnstile Secret</label>
                                                                    <input class="form-select mb-5" id="turnstile_secret" value="{$settings['turnstile_secret']}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card card-bordered">
                                                        <div class="card-header">
                                                            <div class="card-title">客服配置</div>
                                                            <div class="card-toolbar">
                                                                <button class="btn btn-light-primary btn-sm" type="button">保存配置</button>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row g-5">
                                                                <div class="col-xxl-6">
                                                                    <label class="form-label">网页客服系统</label>
                                                                    <select id="live_chat" class="form-control mb-5" data-control="select2" data-hide-search="true">
                                                                        <option value="none" {if $settings['live_chat'] == "none"}selected{/if}>不启用</option>
                                                                        <option value="tawk" {if $settings['live_chat'] == "tawk"}selected{/if}>Tawk</option>
                                                                        <option value="crisp" {if $settings['live_chat'] == "crisp"}selected{/if}>Crisp</option>
                                                                        <option value="livechat" {if $settings['live_chat'] == "livechat"}selected{/if}>LiveChat</option>
                                                                        <option value="mylivechat" {if $settings['live_chat'] == "mylivechat"}selected{/if}>MyLiveChat</option>
                                                                    </select>
                                                                    <label class="form-label">Tawk</label>
                                                                    <input class="form-control mb-5" id="tawk_id" value="{$settings['tawk_id']}">
                                                                    <label class="form-label">Crisp</label>
                                                                    <input class="form-control" id="crisp_id" value="{$settings['crisp_id']}">
                                                                </div>
                                                                <div class="col-xxl-6">
                                                                    <label class="form-label">LiveChat</label>
                                                                    <input class="form-control mb-5" id="livechat_id" value="{$settings['livechat_id']}">
                                                                    <label class="form-label">MyLiveChat</label>
                                                                    <input class="form-control mb-5" id="mylivechat_id" value="{$settings['mylivechat_id']}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="zero_admin_nav_referral" role="tabpanel" aria-labelledby="zero_admin_nav_referral_tab" tabindex="0">
                                                    <div class="card card-bordered">
                                                        <div class="card-header">
                                                            <div class="card-title">模式配置</div>
                                                            <div class="card-toolbar">
                                                                <button class="btn btn-light-primary btn-sm" type="button">保存配置</button>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row g-5">
                                                                <div class="col-xxl-6">
                                                                    <label class="form-label">邀请模式</label>
                                                                    <select id="invitation_mode" class="form-select mb-5" data-control="select2" data-hide-search="true">
                                                                        <option value="registration_only" {if $settings['invitation_mode'] == 'registration_only'}selected{/if}>
                                                                        仅使用邀请注册功能，不返利</option>
                                                                        <option value="after_topup" {if $settings['invitation_mode'] == 'after_topup'}selected{/if}>
                                                                        使用邀请注册功能，并在被邀请用户充值时返利</option>
                                                                        <option value="after_purchase" {if $settings['invitation_mode'] == 'after_purchase'}selected{/if}>
                                                                        使用邀请注册功能，并在被邀请用户购买时返利</option>
                                                                    </select>
                                                                    <label class="form-label">返利模式</label>
                                                                    <select id="invite_rebate_mode" class="form-select mb-5" data-control="select2" data-hide-search="true">
                                                                        <option value="continued" {if $settings['invite_rebate_mode'] == 'continued'}selected{/if}>
                                                                        持续返利</option>
                                                                        <option value="limit_frequency" {if $settings['invite_rebate_mode'] == 'limit_frequency'}selected{/if}>
                                                                        限制邀请人能从被邀请人身上获得的总返利次数</option>
                                                                        <option value="limit_amount" {if $settings['invite_rebate_mode'] == 'limit_amount'}selected{/if}>
                                                                        限制邀请人能从被邀请人身上获得的总返利金额</option>
                                                                        <option value="limit_time_range" {if $settings['invite_rebate_mode'] == 'limit_time_range'}selected{/if}>
                                                                        限制邀请人能从被邀请人身上获得返利的时间范围</option>
                                                                    </select>
                                                                    <label class="form-label">返利比例。10 元商品反 2 元就填 0.2</label>
                                                                    <input class="form-control mb-5" id="rebate_ratio" value="{$settings['rebate_ratio']}" type="text">
                                                                    <label class="form-label">返利时间范围限制（单位：天）</label>
                                                                    <input class="form-control mb-5" id="rebate_time_range_limit" value="{$settings['rebate_time_range_limit']}" type="text">
                                                                </div>
                                                                <div class="col-xxl-6">
                                                                    <label class="form-label">返利总次数限制</label>
                                                                    <input class="form-control mb-5" id="rebate_frequency_limit" value="{$settings['rebate_frequency_limit']}">
                                                                    <label class="form-label">返利总金额限制</label>
                                                                    <input class="form-control mb-5" id="rebate_amount_limit" value="{$settings['rebate_amount_limit']}">
                                                                    <label class="form-label">若有人使用现存用户的邀请链接注册，被邀请人所能获得的余额奖励</label>
                                                                    <input class="form-control mb-5" id="invitation_to_signup_credit_reward" value="{$settings['invitation_to_signup_credit_reward']}">
                                                                    <label class="form-label">若有人使用现存用户的邀请链接注册，邀请人所能获得的流量奖励（单位：GB）</label>
                                                                    <input class="form-control mb-5" id="invitation_to_signup_traffic_reward" value="{$settings['invitation_to_signup_traffic_reward']}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>  
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {include file='admin/script.tpl'}
    </body>
    <script>
        // Format options
        var optionFormatCountry = function(item) {
            if ( !item.id ) {
                return item.text;
            }

            var span = document.createElement('span');
            var imgUrl = item.element.getAttribute('data-kt-select2-country');
            var template = '';

            template += '<img src="' + imgUrl + '" class="rounded-circle h-20px me-2" alt="image"/>';
            template += item.text;

            span.innerHTML = template;

            return $(span);
        }

        // Init Select2 --- more info: https://select2.org/
        $('#zero_currency_unit').select2({
            templateSelection: optionFormatCountry,
            templateResult: optionFormatCountry
        });
    </script>
                                                            
    <script>
        var optionFormatCommission = function(item) {
            if ( !item.id ) {
                return item.text;
            }

            var span = document.createElement('span');
            var template = '';

            template += '<img src="' + item.element.getAttribute('data-kt-select2-image') + '" class="rounded-circle h-20px me-2" alt="image"/>';
            template += item.text;

            span.innerHTML = template;

            return $(span);
        }

        // Init Select2 --- more info: https://select2.org/
        $('#zero_commission_withdrwa_method').select2({
            placeholder: "Select coin",
            minimumResultsForSearch: Infinity,
            templateSelection: optionFormatCommission,
            templateResult: optionFormatCommission
        });
    </script>
    <script>
        function updateConfigSettings(type){
            switch (type){
                // website
                case 'website':
                    $.ajax({
                        type: 'PUT',
                        url: '/admin/settings',
                        dataType: "json",
                        data: {
                            class: type,
                            website_url: $('#website_url').val(),
                            website_name: $('#website_name').val(),
                            website_landing_index: $('#website_landing_index').val(),
                            website_security_token: $('#website_security_token').val(),
                            website_request_token: $('#website_request_token').val()
                        },
                        success: function(data){
                            if (data.ret === 1){
                                getResult(data.msg, '', 'success');
                            }else{
                                getResult(data.msg, '', 'error');
                            }
                        }
                    });
                    break;
                case 'payment_gateway':
                    $.ajax({
                        type: 'PUT',
                        url: '/admin/settings',
                        dataType: "json",
                        data: {
                            class: type,
                            alipay_payment: $('#alipay_payment').val(),
                            wechatpay_payment: $('#wechatpay_payment').val(),
                            cryptopay_payment: $('#cryptopay_payment').val()
                        },
                        success: function(data){
                            if (data.ret === 1){
                                getResult(data.msg, '', 'success');
                            }else{
                                getResult(data.msg, '', 'error');
                            }
                        }
                    });
                    break;
                case 'paybeaver':
                    $.ajax({
                        type: 'PUT',
                        url: '/admin/settings',
                        dataType: "json",
                        data: {
                            class: type,
                            paybeaver_app_id: $('#paybeaver_app_id').val(),
                            paybeaver_app_secret: $('#paybeaver_app_secret').val()
                        },
                        success: function(data){
                            if (data.ret === 1){
                                getResult(data.msg, '', 'success');
                            }else{
                                getResult(data.msg, '', 'error');
                            }
                        }
                    });
                    break;
                case 'paytaro':
                    $.ajax({
                        type: 'PUT',
                        url: '/admin/settings',
                        dataType: "json",
                        data: {
                            class: type,
                            paytaro_app_id: $('#paytaro_app_id').val(),
                            paytaro_app_secret: $('#paytaro_app_secret').val()
                        },
                        success: function(data){
                            if (data.ret === 1){
                                getResult(data.msg, '', 'success');
                            }else{
                                getResult(data.msg, '', 'error');
                            }
                        }
                    });
                    break;
                case 'tronapipay':
                    $.ajax({
                        type: 'PUT',
                        url: '/admin/settings',
                        dataType: "json",
                        data: {
                            class: type,
                            tronapipay_public_key: $('#tronapipay_public_key').val(),
                            tronapipay_private_key: $('#tronapipay_private_key').val()
                        },
                        success: function(data){
                            if (data.ret === 1){
                                getResult(data.msg, '', 'success');
                            }else{
                                getResult(data.msg, '', 'error');
                            }
                        }
                    });
                    break;
                case 'epay':
                    $.ajax({
                        type: 'PUT',
                        url: '/admin/settings',
                        dataType: "json",
                        data: {
                            class: type,
                            epay_url: $('#epay_url').val(),
                            epay_pid: $('#epay_pid').val(),
                            epay_key: $('#epay_key').val()
                        },
                        success: function(data){
                            if (data.ret === 1){
                                getResult(data.msg, '', 'success');
                            }else{
                                getResult(data.msg, '', 'error');
                            }
                        }
                    });
                    break;
                case 'mail':
                    $.ajax({
                        type: 'PUT',
                        url: '/admin/settings',
                        dataType: "json",
                        data: {
                            class: type,
                            mail_drive: $('#mail_driver').val()
                        },
                        success: function(data){
                            if (data.ret === 1){
                                getResult(data.msg, '', 'success');
                            }else{
                                getResult(data.msg, '', 'error');
                            }
                        }
                    });
                    break;
                case 'backup':
                    $.ajax({
                        type: 'PUT',
                        url: '/admin/settings',
                        dataType: "json",
                        data: {
                            class: type,
                            auto_backup_email: $('#auto_backup_email').val(),
                            auto_backup_password: $('#auto_backup_password').val(),
                            auto_backup_notify: $('#auto_backup_notify').val()
                        },
                        success: function(data){
                            if (data.ret === 1){
                                getResult(data.msg, '', 'success');
                            }else{
                                getResult(data.msg, '', 'error');
                            }
                        }
                    });
                    break;
                case 'default':
                    getResult('请求错误', '', 'error');
                    break;
                
            }
        }
    </script>
</html>