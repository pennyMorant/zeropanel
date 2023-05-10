<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{$config["appName"]} 系统设置</title>
        <link href="/theme/zero/assets/css/zero.css" rel="stylesheet" type="text/css"/>
        <meta charset="UTF-8" />
        <meta name="renderer" content="webkit" />
        <meta name="description" content="Updates and statistics" />
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="format-detection" content="telephone=no,email=no" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />

        <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" />
        <meta http-equiv="Cache-Control" content="no-siteapp" />
        <meta http-equiv="pragma" content="no-cache">
        <meta http-equiv="Cache-Control" content="no-cache, must-revalidate">
        <meta http-equiv="expires" content="0">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
        <link href="/theme/zero/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
        <link href="/theme/zero/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
        <link href="/theme/zero/assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
        <link href="https://cdn.jsdelivr.net/npm/jsoneditor/dist/jsoneditor.min.css" rel="stylesheet" type="text/css">
        <script src="https://cdn.jsdelivr.net/npm/jsoneditor/dist/jsoneditor.min.js"></script>
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
                                                    <div class="card card-bordered mb-5">
                                                        <div class="card-header">
                                                            <div class="card-title fw-bold">基础配置</div>
                                                            <div class="card-toolbar">
                                                                <button class="btn btn-light-primary btn-sm" onclick="updateAdminConfigSettings('website')">
                                                                    <i class="bi bi-save"></i>保存配置
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row g-5">
                                                                <div class="col-xxl-6">
                                                                    <label class="form-label">网站地址</label>
                                                                    <input class="form-control mb-5" id="website_url" name="website_url" type="text" placeholder="网站地址" value="{$settings['website_url']}" />
                                                                    <label class="form-label">网站名称</label>
                                                                    <input class="form-control mb-5" id="website_name" name="website_name" type="text" placeholder="网站名称" value="{$settings['website_name']}" />
                                                                    <label class="form-label">LANDING INDEX</label>
                                                                    <input class="form-control mb-5" data-bs-toggle="tooltip" title="不懂请保持默认" id="website_landing_index" name="website_landing_index" type="text" placeholder="" value="{$settings['website_landing_index']}" />
                                                                    <label class="form-label">自定义管理页面路径</label>
                                                                    <input class="form-control mb-5" id="website_admin_path" name="website_admin_path" type="text" placeholder="管理页面路径" value="{$settings['website_admin_path']}" />
                                                                </div>
                                                                <div class="col-xxl-6">
                                                                    <label class="form-label">安全TOKEN</label>
                                                                    <input class="form-control mb-5" data-bs-toggle="tooltip" title="随意填写,尽可能的复杂" id="website_security_token" name="website_security_token" type="text" placeholder="TOKEN" value="{$settings['website_security_token']}" />
                                                                    <label class="form-label">后端TOKEN</label>
                                                                    <input class="form-control mb-5" data-bs-toggle="tooltip" title="请输入安全的密钥" id="website_backend_token" name="website_backend_token" type="text" placeholder="token" value="{$settings['website_backend_token']}" />
                                                                    <label class="form-label">登陆页背景图片</label>
                                                                    <input class="form-control mb-5" data-bs-toggle="tooltip" title="不更改保持默认" id="website_auth_background_image" name="website_auth_background_image" type="text" placeholder="" value="{$settings['website_auth_background_image']}" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row g-5">
                                                        <div class="col-xxl-6">
                                                            <div class="card card-bordered">
                                                                <div class="card-header">
                                                                    <div class="card-title fw-bold">权限组自定义</div>
                                                                    <div class="card-toolbar">
                                                                        <button class="btn btn-light-primary btn-sm" onclick="updateAdminConfigSettings('permission_group')">
                                                                            <i class="bi bi-save"></i>保存配置
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body">                                                                  
                                                                    <label class="form-label">开启权限组自定义</label>
                                                                    <select class="form-select mb-5" id="enable_permission_group" data-control="select2" data-hide-search="true">
                                                                        <option value="0">关闭</option>
                                                                        <option value="1">开启</option>
                                                                    </select>
                                                                    <label class="form-label">权限组名称设置</label>
                                                                    <div id="permission_group_detail"></div>                                                                   
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
                                                                    <div class="card-title fw-bold">邮件配置</div>
                                                                    <div class="card-toolbar">
                                                                        <button class="btn btn-light-primary btn-sm" type="button" onclick="updateAdminConfigSettings('mail')">
                                                                            <i class="bi bi-save"></i>保存配置
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <label class="form-label">邮件服务商</label>
                                                                    <select class="form-select" id="mail_driver" data-control="select2" data-hide-search="true">
                                                                        <option value="none">none</option>
                                                                        <option value="mailgun">mailgun</option>
                                                                        <option value="sendgrid">sendgrid</option>
                                                                        <option value="ses">ses</option>
                                                                        <option value="smtp">smtp</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="card card-bordered">
                                                                <div class="card-header">
                                                                    <div class="card-title fw-bold">邮件备份</div>
                                                                    <div class="card-toolbar">
                                                                        <button class="btn btn-light-primary btn-sm" type="button" onclick="updateAdminConfigSettings('backup')">
                                                                            <i class="bi bi-save"></i>保存配置
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <label class="form-label">接收备份的邮箱</label>
                                                                    <input class="form-control mb-5" id="auto_backup_email" value="{$settings['auto_backup_email']}" type="text" placeholder="邮箱" />
                                                                    <label class="form-label">备份的压缩密码</label>
                                                                    <input class="form-control mb-5" id="auto_backup_password" value="{$settings['auto_backup_password']}" type="text" placeholder="密码" />
                                                                    <label class="form-label">备份成功推送TG消息</label>
                                                                    <select class="form-select mb-5" id="auto_backup_notify" data-control="select2" data-hide-search="true">
                                                                        <option value="0">关闭</option>
                                                                        <option value="1">开启</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xxl-6">
                                                            <div class="card card-bordered mb-5">
                                                                <div class="card-header">
                                                                    <div class="card-title fw-bold">邮件测试</div>
                                                                    <div class="card-toolbar">
                                                                        <button class="btn btn-light-primary btn-sm" onclick="sendTestEmail()">
                                                                            <i class="bi bi-send"></i>测试
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <label class="form-label">账号</label>
                                                                    <input class="form-control" id="test_email_address" value="" type="text" placeholder="账号" />
                                                                </div>
                                                            </div>
                                                            <div class="card card-bordered">
                                                                <div class="card-header">
                                                                    <div class="card-title fw-bold">SENDGRID 配置</div>
                                                                    <div class="card-toolbar">
                                                                        <button class="btn btn-light-primary btn-sm" type="button" onclick="updateAdminConfigSettings('sendgrid')">
                                                                        <i class="bi bi-save"></i>保存配置
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <label class="form-label">密钥</label>
                                                                    <input class="form-control mb-5" id="sendgrid_key" value="{$settings['sendgrid_key']}" type="text" placeholder="密钥" />
                                                                    <label class="form-label">发信邮箱</label>
                                                                    <input class="form-control mb-5" id="sendgrid_sender" value="{$settings['sendgrid_sender']}" type="text" placeholder="邮箱" />
                                                                    <label class="form-label">发信名称</label>
                                                                    <input class="form-control mb-5" id="sendgrid_name" value="{$settings['sendgrid_name']}" type="text" placeholder="发信名称" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="separator border-primary my-10"></div>
                                                    <div class="row g-5">
                                                        <div class="col-xxl-6">
                                                            <div class="card card-bordered">
                                                                <div class="card-header">
                                                                    <div class="card-title fw-bold">SMTP 配置</div>
                                                                    <div class="card-toolbar">
                                                                        <button class="btn btn-light-primary btn-sm" type="button" onclick="updateAdminConfigSettings('smtp')">
                                                                        <i class="bi bi-save"></i>保存配置
                                                                        </button>
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
                                                                        <option value="465">465</option>
                                                                        <option value="587">587</option>
                                                                        <option value="2525">2525</option>
                                                                        <option value="25">25</option>
                                                                    </select>
                                                                    <label class="form-label">SMTP 发信名称</label>
                                                                    <input class="form-control mb-5" id="smtp_name" value="{$settings['smtp_name']}" type="text" />
                                                                    <label class="form-label">SMTP 发信地址</label>
                                                                    <input class="form-control mb-5" id="smtp_sender" value="{$settings['smtp_sender']}" type="text" />
                                                                    <label class="form-label">是否使用 TLS/SSL 发信</label>
                                                                    <select id="smtp_ssl" class="form-select mb-5" data-control="select2" data-hide-search="true">
                                                                        <option value="1">开启</option>
                                                                        <option value="0">关闭</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xxl-6">
                                                            <div class="card card-bordered mb-5">
                                                                <div class="card-header">
                                                                    <div class="card-title fw-bold">MAILGUN 配置</div>
                                                                    <div class="card-toolbar">
                                                                        <button class="btn btn-light-primary btn-sm" type="button" onclick="updateAdminConfigSettings('mailgun')">
                                                                        <i class="bi bi-save"></i>保存配置
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <label class="form-label">密钥</label>
                                                                    <input class="form-control mb-5" id="mailgun_key" value="{$settings['mailgun_key']}" type="text" placeholder="密钥">
                                                                    <label class="form-label">域名</label>
                                                                    <input class="form-control mb-5" id="mailgun_domain" value="{$settings['mailgun_domain']}" type="text" placeholder="域名">
                                                                    <label class="form-label">发信名称</label>
                                                                    <input class="form-control mb-5" id="mailgun_sender" value="{$settings['mailgun_sender']}" type="text" placeholder="发信名称">
                                                                </div>
                                                            </div>
                                                            <div class="card card-bordered">
                                                                <div class="card-header">
                                                                    <div class="card-title fw-bold">SES 配置</div>
                                                                    <div class="card-toolbar">
                                                                        <button class="btn btn-light-primary btn-sm" type="button" onclick="updateAdminConfigSettings('ses')">
                                                                        <i class="bi bi-save"></i>保存配置
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <label class="form-label">密钥 ID</label>
                                                                    <input class="form-control mb-5" id="aws_access_key_id" value="{$settings['aws_access_key_id']}" type="text" placeholder="密钥 ID" />
                                                                    <label class="form-label">密钥 KEY</label>
                                                                    <input class="form-control mb-5" id="aws_secret_access_key" value="{$settings['aws_secret_access_key']}" type="text" placeholder="密钥 KEY" />
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
                                                                    <div class="card-title fw-bold">Telegram 配置</div>
                                                                    <div class="card-toolbar">
                                                                        <button class="btn btn-light-primary btn-sm" type="button" onclick="updateAdminConfigSettings('telegram')">
                                                                        <i class="bi bi-save"></i>保存配置
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <label class="form-label">群组 ID</label>
                                                                    <input class="form-control mb-5" id="telegram_group_id" value="{$settings['telegram_group_id']}" type="text" placeholder="ID" />
                                                                    <label class="form-label">群组地址</label>
                                                                    <input class="form-control mb-5" id="telegram_group_url" value="{$settings['telegram_group_url']}" type="text" placeholder="地址" />
                                                                    <label class="form-label">频道账号</label>
                                                                    <input class="form-control mb-5" id="telegram_channel_id" value="{$settings['telegram_channel_id']}" type="text" placeholder="账号" />
                                                                    <label class="form-label">ADMIN ID</label>
                                                                    <select class="form-select" id="telegram_admin_id" data-control="select2" data-close-on-select="true" data-placeholder="选择管理员" data-allow-clear="true" multiple="multiple">
                                                                        <option></option>
                                                                        {foreach $adminUsers as $adminUser}
                                                                            <option value={$adminUser->telegram_id}>{$adminUser->email}</option>
                                                                        {/foreach}
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xxl-6">
                                                            <div class="card card-bordered">
                                                                <div class="card-header">
                                                                    <div class="card-title fw-bold">Telegram BOT</div>
                                                                    <div class="card-toolbar">
                                                                        <button class="btn btn-light-primary btn-sm" type="button" onclick="updateAdminConfigSettings('telegram_bot')">
                                                                        <i class="bi bi-save"></i>保存配置
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <label class="form-label">启用BOT</label>
                                                                    <select class="form-select mb-5" id="enable_telegram_bot" data-control="select2" data-hide-search="true">
                                                                        <option value="0" {if $settings['enable_telegram_bot'] == false} selected{/if}>关闭</option>
                                                                        <option value="1" {if $settings['enable_telegram_bot'] == true} selected{/if}>开启</option>
                                                                    </select>
                                                                    <label class="form-label">BOT TOKEN</label>
                                                                    <input class="form-control mb-5" id="telegram_bot_token" value="{$settings['telegram_bot_token']}" type="text" placeholder="TOKEN" />
                                                                    <label class="form-label">BOT ID</label>
                                                                    <input class="form-control mb-5" id="telegram_bot_id" value="{$settings['telegram_bot_id']}" type="text" placeholder="BOT ID" />
                                                                    <label class="form-label">请求 TOKEN</label>
                                                                    <input class="form-control mb-5" id="telegram_bot_request_token" value="{$settings['telegram_bot_request_token']}" type="text" placeholder="TOKEN" />
                                                                    <label class="form-label">BOT 推送充值消息</label>
                                                                    <select class="form-select mb-5" id="enable_push_top_up_message" data-control="select2" data-hide-search="true">
                                                                        <option value="0">关闭</option>
                                                                        <option value="1">开启</option>
                                                                    </select>
                                                                    <label class="form-label">BOT 推送工单消息</label>
                                                                    <select class="form-select mb-5" id="enable_push_ticket_message" data-control="select2" data-hide-search="true">
                                                                        <option value="0">关闭</option>
                                                                        <option value="1">开启</option>
                                                                    </select>
                                                                    <label class="form-label">BOT 推送系统运行情况</label>
                                                                    <select class="form-select mb-5" id="enable_push_system_report" data-control="select2" data-hide-search="true">
                                                                        <option value="0">关闭</option>
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
                                                            <div class="card-title fw-bold">订阅配置</div>
                                                            <div class="card-toolbar">
                                                                <button class="btn btn-light-primary btn-sm" type="button" onclick="updateAdminConfigSettings('subscribe')">
                                                                <i class="bi bi-save"></i>保存配置
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row g-5">
                                                                <div class="col-xxl-6">
                                                                    <label class="form-label">订阅地址</label>
                                                                    <input class="form-control mb-5" id="subscribe_address_url" value="{$settings['subscribe_address_url']}" type="text" placeholder="订阅地址" />
                                                                    <label class="form-label">订阅显示流量和时间</label>
                                                                    <select class="form-select mb-5" id="enable_subscribe_extend" data-control="select2" data-hide-search="true">
                                                                        <option value="0" {if $settings['enable_subscribe_extend'] == false}selected{/if}>关闭</option>
                                                                        <option value="1" {if $settings['enable_subscribe_extend'] == true}selected{/if}>开启</option>
                                                                    </select>
                                                                    <label class="form-label">订阅显示emoji</label>
                                                                    <select class="form-select mb-5" id="enable_subscribe_emoji" data-control="select2" data-hide-search="true">
                                                                        <option value="0" {if $settings['enable_subscribe_emoji'] == false}selected{/if}>关闭</option>
                                                                        <option value="1" {if $settings['enable_subscribe_emoji'] == true}selected{/if}>开启</option>
                                                                    </select>
                                                                    <label class="form-label">订阅日志记录</label>
                                                                    <select class="form-select mb-5" id="enable_subscribe_log" data-control="select2" data-hide-search="true">
                                                                        <option value="0" {if $settings['enable_subscribe_log'] == false}selected{/if}>关闭</option>
                                                                        <option value="1" {if $settings['enable_subscribe_log'] == true}selected{/if}>开启</option>
                                                                    </select>
                                                                    <label class="form-label">订阅日志保留时间</label>
                                                                    <input class="form-control" id="subscribe_log_keep_time" value="{$settings['subscribe_log_keep_time']}" type="text" placeholder="保留时间" />
                                                                </div>
                                                                <div class="col-xxl-6">
                                                                    <label class="form-label">订阅营销信息</label>
                                                                    <input class="form-control mb-5" id="subscribe_diy_message" value="{$settings['subscribe_diy_message']}" type="text" placeholder="营销信息" />
                                                                    <label class="form-label">CLASH 默认配置</label>
                                                                    <input class="form-control mb-5" id="subscribe_clash_default_profile" value="{$settings['subscribe_clash_default_profile']}" type="text" placeholder="默认配置" />
                                                                    <label class="form-label">SURGE 默认配置</label>
                                                                    <input class="form-control mb-5" id="subscribe_surge_default_profile" value="{$settings['subscribe_surge_default_profile']}" type="text" placeholder="默认配置" />
                                                                    <label class="form-label">SURFBOARD 默认配置</label>
                                                                    <input class="form-control mb-5" id="subscribe_surfboard_default_profile" value="{$settings['subscribe_surfboard_default_profile']}" type="text" placeholder="默认配置" />
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
                                                                    <div class="card-title d-flex flex-column">
                                                                        <span class="fw-bold">货币配置</span>
                                                                        <span class="text-gray-400 pt-1 fw-semibold fs-6">API申请地址: https://app.abstractapi.com</span>
                                                                    </div>
                                                                    <div class="card-toolbar">
                                                                        <button class="btn btn-light-primary btn-sm" type="button" onclick="updateAdminConfigSettings('currency')">
                                                                        <i class="bi bi-save"></i>保存配置
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <label class="form-label">开启其他货币(不开启默认为CNY)</label>
                                                                    <select class="form-select mb-5" id="enable_currency" data-control="select2" data-hide-search="true"> 
                                                                        <option value="0" {if $settings['enable_currency'] == false}selected{/if}>关闭</option>
                                                                        <option value="1" {if $settings['enable_currency'] == true}selected{/if}>开启</option>
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
                                                                    <input class="form-control mb-5" id="currency_exchange_rate" value="{$settings['currency_exchange_rate']}" type="text" placeholder="货币汇率" />
                                                                    <label class="form-label">汇率 API KEY</label>
                                                                    <input class="form-control mb-5" id="currency_exchange_rate_api_key" value="{$settings['currency_exchange_rate_api_key']}" type="text" placeholder="API KEY" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xxl-6">
                                                            <div class="card card-bordered">
                                                                <div class="card-header">
                                                                    <div class="card-title fw-bold">提现配置</div>
                                                                    <div class="card-toolbar">
                                                                        <button class="btn btn-light-primary btn-sm" type="button" onclick="updateAdminConfigSettings('withdraw')">
                                                                        <i class="bi bi-save"></i>保存配置
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <label class="form-label">开启提现</label>
                                                                    <select class="form-select mb-5" id="enable_withdraw" data-control="select2" data-hide-search="true">
                                                                        <option value="0">关闭</option>
                                                                        <option value="1">开启</option>
                                                                    </select>
                                                                    <label class="form-label">提现方式</label>
                                                                    <select class="form-select mb-5" id="withdraw_method">
                                                                        <option value="USDT" data-kt-select2-image="/theme/zero/assets/media/payment_logo/tether.svg">USDT</option>
                                                                    </select>
                                                                    <label class="form-label">最低提现金额</label>
                                                                    <input class="form-control mb-5" id="withdraw_minimum_amount" value="{$settings['withdraw_minimum_amount']}" type="text" placeholder="最低金额" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="zero_admin_nav_account" role="tabpanel" aria-labelledby="zero_admin_nav_account_tab" tabindex="0">
                                                    <div class="card card-bordered mb-5">
                                                        <div class="card-header">
                                                            <div class="card-title fw-bold">注册配置</div>
                                                            <div class="card-toolbar">
                                                                <button class="btn btn-light-primary btn-sm" type="button" onclick="updateAdminConfigSettings('register')">
                                                                <i class="bi bi-save"></i>保存配置
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row g-5">
                                                                <div class="col-xxl-6">
                                                                    <label class="form-label">注册模式</label>
                                                                    <select class="form-select mb-5" id="reg_mode" data-control="select2" data-hide-search="true">
                                                                        <option value="close">关闭注册</option>
                                                                        <option value="open">开启注册</option>
                                                                        <option value="invite">仅限邀请注册</option>
                                                                    </select>
                                                                    <label class="form-label">默认等级</label>
                                                                    <input class="form-control mb-5" id="signup_default_class" value="{$settings['signup_default_class']}" type="text" placeholder="默认等级" />
                                                                    <label class="form-label">默认等级时长</label>
                                                                    <div class="input-group mb-5">
                                                                        <input class="form-control" id="signup_default_class_time" value="{$settings['signup_default_class_time']}" type="text" placeholder="等级时长" />
                                                                        <span class="input-group-text">天</span>
                                                                    </div>
                                                                    <label class="form-label">验证邮箱</label>
                                                                    <select class="form-select mb-5" id="verify_email" data-control="select2" data-hide-search="true">
                                                                        <option value="close">关闭</option>
                                                                        <option value="open">开启</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-xxl-6">
                                                                    <label class="form-label">默认流量</label>
                                                                    <div class="input-group mb-5">
                                                                        <input class="form-control" id="signup_default_traffic" value="{$settings['signup_default_traffic']}" type="text" placeholder="默认流量" />
                                                                        <span class="input-group-text">GB</span>
                                                                    </div>
                                                                    <label class="form-label">默认IP限制</label>
                                                                    <div class="input-group mb-5">
                                                                        <input class="form-control" id="signup_default_ip_limit" value="{$settings['signup_default_ip_limit']}" type="text" placeholder="IP限制" />
                                                                        <span class="input-group-text">个</span>
                                                                    </div>
                                                                    <label class="form-label">默认速度限制</label>
                                                                    <div class="input-group mb-5">
                                                                        <input class="form-control" id="signup_default_speed_limit" value="{$settings['signup_default_speed_limit']}" type="text" placeholder="速度限制" />
                                                                        <span class="input-group-text">Mbps</span>
                                                                    </div>
                                                                    <label class="form-label">限制邮箱后缀</label>
                                                                    <select class="form-select" id="limit_email_suffix" data-control="select2" data-placeholder="指定邮箱后缀" data-allow-clear="true" multiple="multiple">
                                                                        <option></option>                              
                                                                        <option value="qq.com">@qq.com</option>
                                                                        <option value="163.com">@163.com</option>
                                                                        <option value="gmail.com">@gmail.com</option>
                                                                        <option value="outlook.com">@outlook.com</option>
                                                                        <option value="yahoo.com">@yahoo.com</option>
                                                                        <option value="live.com">@live.com</option>
                                                                        <option value="hotmail.com">@hotmail.com</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card card-bordered mb-5">
                                                        <div class="card-header">
                                                            <div class="card-title fw-bold">验证配置</div>
                                                            <div class="card-toolbar">
                                                                <button class="btn btn-light-primary btn-sm" type="button" onclick="updateAdminConfigSettings('captcha')">
                                                                <i class="bi bi-save"></i>保存配置
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row -g-5">
                                                                <div class="col-xxl-6">
                                                                    <label class="form-label">验证码提供商</label>
                                                                    <select id="captcha_provider" class="form-select mb-5" data-control="select2" data-hide-search="true">
                                                                        <option value="turnstile">Turnstile</option>
                                                                    </select>
                                                                    <label class="form-label">注册验证码</label>
                                                                    <select id="enable_signup_captcha" class="form-select mb-5" data-control="select2" data-hide-search="true">
                                                                        <option value="0">关闭</option>
                                                                        <option value="1">开启</option>
                                                                    </select>
                                                                    <label class="form-label">登录验证码</label>
                                                                    <select id="enable_signin_captcha" class="form-select" data-control="select2" data-hide-search="true">
                                                                        <option value="0">关闭</option>
                                                                        <option value="1">开启</option>
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
                                                            <div class="card-title fw-bold">客服配置</div>
                                                            <div class="card-toolbar">
                                                                <button class="btn btn-light-primary btn-sm" type="button" onclick="updateAdminConfigSettings('live_chat')">
                                                                <i class="bi bi-save"></i>保存配置
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row g-5">
                                                                <div class="col-xxl-6">
                                                                    <label class="form-label">网页客服系统</label>
                                                                    <select id="live_chat" class="form-control mb-5" data-control="select2" data-hide-search="true">
                                                                        <option value="none">不启用</option>
                                                                        <option value="tawk">Tawk</option>
                                                                        <option value="crisp">Crisp</option>
                                                                        <option value="livechat">LiveChat</option>
                                                                        <option value="mylivechat">MyLiveChat</option>
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
                                                            <div class="card-title fw-bold">模式配置</div>
                                                            <div class="card-toolbar">
                                                                <button class="btn btn-light-primary btn-sm" type="button" onclick="updateAdminConfigSettings('invite')">
                                                                <i class="bi bi-save"></i>保存配置
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row g-5">
                                                                <div class="col-xxl-6">
                                                                    <label class="form-label">邀请模式</label>
                                                                    <select id="invitation_mode" class="form-select mb-5" data-control="select2" data-hide-search="true">
                                                                        <option value="registration_only">仅使用邀请注册功能，不返利</option>
                                                                        <option value="after_topup">使用邀请注册功能，并在被邀请用户充值时返利</option>
                                                                        <option value="after_purchase">使用邀请注册功能，并在被邀请用户购买时返利</option>
                                                                    </select>
                                                                    <label class="form-label">返利模式</label>
                                                                    <select id="invite_rebate_mode" class="form-select mb-5" data-control="select2" data-hide-search="true">
                                                                        <option value="continued">持续返利</option>
                                                                        <option value="limit_frequency">限制邀请人能从被邀请人身上获得的总返利次数</option>
                                                                        <option value="limit_amount">限制邀请人能从被邀请人身上获得的总返利金额</option>
                                                                        <option value="limit_time_range">限制邀请人能从被邀请人身上获得返利的时间范围</option>
                                                                    </select>
                                                                    <label class="form-label">返利比例</label>
                                                                    <div class="input-group mb-5">
                                                                        <input class="form-control" id="rebate_ratio" value="{$settings['rebate_ratio']}" type="text">
                                                                        <span class="input-group-text">%</span>
                                                                    </div>
                                                                    <label class="form-label">返利时间范围限制</label>
                                                                    <div class="input-group">
                                                                        <input class="form-control" id="rebate_time_range_limit" value="{$settings['rebate_time_range_limit']}" type="text">
                                                                        <span class="input-group-text">天</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xxl-6">
                                                                    <label class="form-label">返利总次数限制</label>
                                                                    <input class="form-control mb-5" id="rebate_frequency_limit" value="{$settings['rebate_frequency_limit']}">
                                                                    <label class="form-label">返利总金额限制</label>
                                                                    <input class="form-control mb-5" id="rebate_amount_limit" value="{$settings['rebate_amount_limit']}">
                                                                    <label class="form-label">若有人使用现存用户的邀请链接注册，被邀请人所能获得的余额奖励</label>
                                                                    <input class="form-control mb-5" id="invitation_to_signup_credit_reward" value="{$settings['invitation_to_signup_credit_reward']}">
                                                                    <label class="form-label">若有人使用现存用户的邀请链接注册，邀请人所能获得的流量奖励</label>
                                                                    <div class="input-group">
                                                                        <input class="form-control" id="invitation_to_signup_traffic_reward" value="{$settings['invitation_to_signup_traffic_reward']}">
                                                                        <span class="input-group-text">GB</span>
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
                        {include file='admin/footer.tpl'}
                    </div>
                </div>
            </div>
        </div>
        {include file='admin/script.tpl'}
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
    
            $('#currency_unit').select2({
                templateSelection: optionFormatCountry,
                templateResult: optionFormatCountry
            });
        </script>
        <script>
            $('#limit_email_suffix').val({$settings['limit_email_suffix']}).trigger('change');
            $('#telegram_admin_id').val({$settings['telegram_admin_id']}).trigger('change');
            $('#enable_permission_group').val("{$settings['enable_permission_group']}").trigger('change');
            $('#mail_driver').val("{$settings['mail_driver']}").trigger('change');
            $('#auto_backup_notify').val("{$settings['auto_backup_notify']}").trigger('change');
            $('#smtp_port').val("{$settings['smtp_port']}").trigger('change');
            $('#smtp_ssl').val("{$settings['smtp_ssl']}").trigger('change');
            $('#enable_telegram_bot').val("{$settings['enable_telegram_bot']}").trigger('change');
            $('#enable_push_top_up_message').val("{$settings['enable_push_top_up_message']}").trigger('change');
            $('#enable_push_ticket_message').val("{$settings['enable_push_ticket_message']}").trigger('change');
            $('#enable_push_system_report').val("{$settings['enable_push_system_report']}").trigger('change');
            $('#enable_subscribe_extend').val("{$settings['enable_subscribe_extend']}").trigger('change');
            $('#enable_subscribe_emoji').val("{$settings['enable_subscribe_emoji']}").trigger('change');
            $('#enable_subscribe_log').val("{$settings['enable_subscribe_log']}").trigger('change');
            $('#currency_unit').val("{$settings['currency_unit']}").trigger('change');
            $('#enable_withdraw').val("{$settings['enable_withdraw']}").trigger('change');
            $('#reg_mode').val("{$settings['reg_mode']}").trigger('change');
            $('#verify_email').val("{$settings['verify_email']}").trigger('change');
            $('#captcha_provider').val("{$settings['captcha_provider']}").trigger('change');
            $('#enable_signup_captcha').val("{$settings['enable_signup_captcha']}").trigger('change');
            $('#enable_signin_captcha').val("{$settings['enable_signin_captcha']}").trigger('change');
            $('#live_chat').val("{$settings['live_chat']}").trigger('change');
            $('#invitation_mode').val("{$settings['invitation_mode']}").trigger('change');
            $('#invite_rebate_mode').val("{$settings['invite_rebate_mode']}").trigger('change');
        </script>
        <script>
            const container = document.getElementById('permission_group_detail');
            var options = {
                mode: 'text',
                modes: ['code', 'form', 'text', 'tree', 'view', 'preview'], // allowed modes
                onModeChange: function (newMode, oldMode) {
                    console.log('Mode switched from', oldMode, 'to', newMode)
                }
            };
            const permission_group_editor = new JSONEditor(container, options);
            permission_group_editor.set({$settings['permission_group_detail']})
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
            $('#withdraw_method').select2({
                placeholder: "Select coin",
                minimumResultsForSearch: Infinity,
                templateSelection: optionFormatCommission,
                templateResult: optionFormatCommission
            });
        </script>
        <script>
            function updateAdminConfigSettings(type){
                switch (type){
                    // website
                    case 'website':
                        $.ajax({
                            type: 'POST',
                            url: location.pathname,
                            dataType: "json",
                            data: {
                                class: type,
                                website_url: $('#website_url').val(),
                                website_name: $('#website_name').val(),
                                website_landing_index: $('#website_landing_index').val(),
                                website_security_token: $('#website_security_token').val(),
                                website_request_token: $('#website_request_token').val(),
                                website_backend_token: $('#website_backend_token').val(),
                                website_auth_background_image: $('#website_auth_background_image').val(),
                                website_admin_path: $('#website_admin_path').val()
                            },
                            success: function(data){
                                if (data.ret === 1){
                                    getResult(data.msg, '', 'success');
                                    setTimeout(() => window.location.href = '/'+$("#website_admin_path").val()+'/setting', 1000);
                                }else{
                                    getResult(data.msg, '', 'error');
                                }
                            }
                        });
                        break;
                    case 'permission_group':
                        $.ajax({
                            type: 'POST',
                            url: location.pathname,
                            dataType: "json",
                            data: {
                                class: type,
                                enable_permission_group: $('#enable_permission_group').val(),
                                permission_group_detail: permission_group_editor.get()
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
                            type: 'POST',
                            url: location.pathname,
                            dataType: "json",
                            data: {
                                class: type,
                                mail_driver: $('#mail_driver').val()
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
                            type: 'POST',
                            url: location.pathname,
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
                    case 'sendgrid':
                        $.ajax({
                            type: 'POST',
                            url: location.pathname,
                            dataType: "json",
                            data: {
                                class: type,
                                sendgrid_key: $('#sendgrid_key').val(),
                                sendgrid_sender: $('#sendgrid_sender').val(),
                                sendgrid_name: $('#sendgrid_name').val()
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
                    case 'smtp':
                        $.ajax({
                            type: 'POST',
                            url: location.pathname,
                            dataType: "json",
                            data: {
                                class: type,
                                smtp_host: $('#smtp_host').val(),
                                smtp_username: $('#smtp_username').val(),
                                smtp_password: $('#smtp_password').val(),
                                smtp_port: $('#smtp_port').val(),
                                smtp_name: $('#smtp_name').val(),
                                smtp_sender: $('#smtp_sender').val(),
                                smtp_ssl: $('#smtp_ssl').val()
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
                    case 'mailgun':
                        $.ajax({
                            type: 'POST',
                            url: location.pathname,
                            dataType: "json",
                            data: {
                                class: type,
                                mailgun_key: $('#mailgun_key').val(),
                                mailgun_domain: $('#mailgun_domain').val(),
                                mailgun_sender: $('#smtp_password').val()
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
                    case 'ses':
                        $.ajax({
                            type: 'POST',
                            url: location.pathname,
                            dataType: "json",
                            data: {
                                class: type,
                                aws_access_key_id: $('#aws_access_key_id').val(),
                                aws_secret_access_key: $('#aws_secret_access_key').val()
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
                    case 'telegram':
                        $.ajax({
                            type: 'POST',
                            url: location.pathname,
                            dataType: "json",
                            data: {
                                class: type,
                                telegram_group_id: $('#telegram_group_id').val(),
                                telegram_group_url: $('#telegram_group_url').val(),
                                telegram_channel_id: $('#telegram_channel_id').val(),
                                telegram_admin_id: ($('#telegram_admin_id').val().length === 0) ? [""] : $('#telegram_admin_id').val(),
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
                    case 'telegram_bot':
                        $.ajax({
                            type: 'POST',
                            url: location.pathname,
                            dataType: "json",
                            data: {
                                class: type,
                                enable_telegram_bot: $('#enable_telegram_bot').val(),
                                telegram_bot_token: $('#telegram_bot_token').val(),
                                telegram_bot_id: $('#telegram_bot_id').val(),
                                telegram_bot_request_token: $('#telegram_bot_request_token').val(),
                                enable_push_top_up_message: $('#enable_push_top_up_message').val(),
                                enable_push_ticket_message: $('#enable_push_ticket_message').val(),
                                enable_push_system_report: $('#enable_push_system_report').val(),
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
                    case 'subscribe':
                        $.ajax({
                            type: 'POST',
                            url: location.pathname,
                            dataType: "json",
                            data: {
                                class: type,
                                subscribe_address_url: $('#subscribe_address_url').val(),
                                enable_subscribe_extend: $('#enable_subscribe_extend').val(),
                                enable_subscribe_emoji: $('#enable_subscribe_emoji').val(),
                                enable_subscribe_log: $('#enable_subscribe_log').val(),
                                subscribe_log_keep_time: $('#subscribe_log_keep_time').val(),
                                subscribe_diy_message: $('#subscribe_diy_message').val(),
                                subscribe_clash_default_profile: $('#subscribe_clash_default_profile').val(),
                                subscribe_surge_default_profile: $('#subscribe_surge_default_profile').val(),
                                subscribe_surfboard_default_profile: $('#subscribe_surfboard_default_profile').val(),
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
                    case 'currency':
                        $.ajax({
                            type: 'POST',
                            url: location.pathname,
                            dataType: "json",
                            data: {
                                class: type,
                                enable_currency: $('#enable_currency').val(),
                                currency_unit: $('#currency_unit').val(),
                                currency_exchange_rate: $('#currency_exchange_rate').val(),
                                currency_exchange_rate_api_key: $('#currency_exchange_rate_api_key').val()
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
                    case 'withdraw':
                        $.ajax({
                            type: 'POST',
                            url: location.pathname,
                            dataType: "json",
                            data: {
                                class: type,
                                enable_withdraw: $('#enable_withdraw').val(),
                                withdraw_method: $('#withdraw_method').val(),
                                withdraw_minimum_amount: $('#withdraw_minimum_amount').val()
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
                    case 'register':
                        $.ajax({
                            type: 'POST',
                            url: location.pathname,
                            dataType: "json",
                            data: {
                                class: type,
                                reg_mode: $('#reg_mode').val(),
                                signup_default_class: $('#signup_default_class').val(),
                                signup_default_class_time: $('#signup_default_class_time').val(),
                                signup_default_traffic: $('#signup_default_traffic').val(),
                                signup_default_ip_limit: $('#signup_default_ip_limit').val(),
                                signup_default_speed_limit: $('#signup_default_speed_limit').val(),
                                verify_email: $('#verify_email').val(),
                                limit_email_suffix: ($('#limit_email_suffix').val().length === 0) ? [""] : $('#limit_email_suffix').val(),
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
                    case 'captcha':
                        $.ajax({
                            type: 'POST',
                            url: location.pathname,
                            dataType: "json",
                            data: {
                                class: type,
                                captcha_provider: $('#captcha_provider').val(),
                                enable_signup_captcha: $('#enable_signup_captcha').val(),
                                enable_signin_captcha: $('#enable_signin_captcha').val(),
                                turnstile_sitekey: $('#turnstile_sitekey').val(),
                                turnstile_secret: $('#turnstile_secret').val(),
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
                    case 'live_chat':
                        $.ajax({
                            type: 'POST',
                            url: location.pathname,
                            dataType: "json",
                            data: {
                                class: type,
                                live_chat: $('#live_chat').val(),
                                tawk_id: $('#tawk_id').val(),
                                crisp_id: $('#crisp_id').val(),
                                livechat_id: $('#livechat_id').val(),
                                mylivechat_id: $('#mylivechat_id').val()
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
                    case 'invite':
                        $.ajax({
                            type: 'POST',
                            url: location.pathname,
                            dataType: "json",
                            data: {
                                class: type,
                                invitation_mode: $('#invitation_mode').val(),
                                invite_rebate_mode: $('#invite_rebate_mode').val(),
                                rebate_ratio: $('#rebate_ratio').val(),
                                rebate_time_range_limit: $('#rebate_time_range_limit').val(),
                                rebate_frequency_limit: $('#rebate_frequency_limit').val(),
                                rebate_amount_limit: $('#rebate_amount_limit').val(),
                                invitation_to_signup_credit_reward: $('#invitation_to_signup_credit_reward').val(),
                                invitation_to_signup_traffic_reward: $('#invitation_to_signup_traffic_reward').val(),
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
        <script>
            function sendTestEmail(){
                $.ajax({
                    type: 'POST',
                    url: location.pathname+'/email',
                    dataType: 'json',
                    data: {
                        email_address: $('#test_email_address').val()
                    },
                    success: function(data){
                        if (data.ret === 1) {
                            getResult(data.msg, '', 'success');
                        } else {
                            getResult(data.msg, '', 'error');
                        }
                    }
                });
            }
        </script>
    </body>
</html>