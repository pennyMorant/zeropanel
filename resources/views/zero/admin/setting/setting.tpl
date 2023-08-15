<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{$config["website_name"]} 系统设置</title>
        
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
                                                {include file='admin/setting/basic.tpl'}
                                                {include file='admin/setting/email.tpl'}
                                                {include file='admin/setting/telegram.tpl'}   
                                                {include file='admin/setting/subscribe.tpl'}    
                                                {include file='admin/setting/sell.tpl'}   
                                                {include file='admin/setting/signup.tpl'}    
                                                {include file='admin/setting/referral.tpl'}  
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
                                subscribe_diy_message: $('#subscribe_diy_message').val()
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