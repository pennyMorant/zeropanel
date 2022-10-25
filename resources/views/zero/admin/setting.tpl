{include file='admin/main.tpl'}

<main class="content">
    <div class="content-header ui-content-header">
        <div class="container">
            <h1 class="content-heading">设置中心</h1>
        </div>
    </div>

    <div class="container">
        <div class="col-xx-12 col-sm-12">
            <div class="card quickadd">
                <div class="card-main">
                    <div class="card-inner">
                        <nav class="tab-nav margin-top-no">
                            <ul class="nav nav-list">
                                <li class="active">
                                    <a data-toggle="tab" href="#general_settings"><i class="icon icon-lg">display_settings</i>&nbsp;通用设置</a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#user_settings"><i class="icon icon-lg">account_circle</i>&nbsp;用户</a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#payment_settings"><i class="icon icon-lg">payment</i>&nbsp;支付</a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#mail_settings"><i class="icon icon-lg">email</i>&nbsp;邮件</a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#customer_service_system_settings"><i class="icon icon-lg">message</i>&nbsp;客服</a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#verification_code_settings"><i class="icon icon-lg">verified_user</i>&nbsp;验证</a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#personalise_settings"><i class="icon icon-lg">color_lens</i>&nbsp;个性化</a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#registration_settings"><i class="icon icon-lg">group_add</i>&nbsp;注册</a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#invitation_settings"><i class="icon icon-lg">loyalty</i>&nbsp;邀请</a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#sell_settings"><i class="icon icon-lg">shopping_cart</i>&nbsp;销售</a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#telegram_settings"><i class="icon icon-lg">airplanemode_active</i>&nbsp;Telegram</a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#subscribe_settings"><i class="icon icon-lg">attachment</i>&nbsp;订阅</a>
                                </li>
                            </ul>
                        </nav>

                        <div class="card-inner">
                           <div class="tab-content">
                                <div class="tab-pane fade active in" id="general_settings">
                                    <nav class="tab-nav margin-top-no">
                                        <ul class="nav nav-list">
                                            <li class="active">
                                                <a data-toggle="tab" href="#website_general"><i class="icon icon-lg">settings</i>&nbsp;网站设置</a>
                                            </li>
                                            <li>
                                                <a data-toggle="tab" href="#website_security"><i class="icon icon-lg">security</i>&nbsp;网站安全</a>
                                            </li>
                                            <li>
                                                <a data-toggle="tab" href="#website_backend"><i class="icon icon-lg">api</i>&nbsp;网站后端</a>
                                            </li>
                                        </ul>
                                    </nav>

                                    <div class="tab-pane fade active in" id="website_general">
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">网站地址</label>
                                            <input class="form-control maxwidth-edit" id="website_general_url" value="{$settings['website_general_url']}">
                                        </div>
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">网站名称</label>
                                            <input class="form-control maxwidth-edit" id="website_general_name" value="{$settings['website_general_name']}">
                                        </div>
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">网站 landing index</label>
                                            <input class="form-control maxwidth-edit" id="website_general_landing_index" value="{$settings['website_general_landing_index']}">
                                        </div>
                                        <button id="submit_website_general" type="submit" class="btn btn-brand btn-block">提交</button>
                                    </div> 
                                    <div class="tab-pane fade" id="website_security">
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">网站安全token</label>
                                            <input class="form-control maxwidth-edit" id="website_security_token" value="{$settings['website_security_token']}">
                                            <p class="form-control-guide"><i class="material-icons">info</i>保证前端请求安全，随机填写</p>
                                        </div>                                       
                                        <button id="submit_website_security" type="submit" class="btn btn-brand btn-block">提交</button>
                                    </div> 
                                    <div class="tab-pane fade" id="website_backend">
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">后端请求token</label>
                                            <input class="form-control maxwidth-edit" id="website_backend_token" value="{$settings['website_backend_token']}">
                                            <p class="form-control-guide"><i class="material-icons">info</i>前端与后端通信token,勿过于简单</p>
                                        </div>
                                        <button id="submit_website_backend" type="submit" class="btn btn-brand btn-block">提交</button>
                                    </div>                             
                                </div>

                                <div class="tab-pane fade" id="user_settings">
                                    <nav class="tab-nav margin-top-no">
                                        <ul class="nav nav-list">
                                            <li class="active">
                                                <a data-toggle="tab" href="#user_general"><i class="icon icon-lg">settings</i>&nbsp;设置</a>
                                            </li>
                                            <li>
                                                <a data-toggle="tab" href="#user_checkin"><i class="icon icon-lg">done_outline</i>&nbsp;签到</a>
                                            </li>
                                            <li>
                                                <a data-toggle="tab" href="#user_notify"><i class="icon icon-lg">campaign</i>&nbsp;通知</a>
                                            </li>
                                        </ul>
                                    </nav>

                                    <div class="tab-pane fade active in" id="user_general">
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">免费用户流量重置日</label>
                                            <input class="form-control maxwidth-edit" id="user_general_free_user_reset_day" value="{$settings['user_general_free_user_reset_day']}">
                                            <p class="form-control-guide"><i class="material-icons">info</i>免费用户的流量定期重置日, 0表示不重置</p>
                                        </div>
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">免费用户流量重置值 (单位: GB)</label>
                                            <input class="form-control maxwidth-edit" id="user_general_free_user_reset_traffic" value="{$settings['user_general_free_user_reset_traffic']}">
                                            <p class="form-control-guide"><i class="material-icons">info</i>免费用户的流量定期充值的值, 0表示不重置</p>
                                        </div>
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">等级到期流量重置值 (单位: GB)</label>
                                            <input class="form-control maxwidth-edit" id="user_general_class_expire_reset_traffic" value="{$settings['user_general_class_expire_reset_traffic']}">
                                            <p class="form-control-guide"><i class="material-icons">info</i>用户等级到期重置流量的值, 小于0时表示不重置</p>
                                        </div>
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">购买套餐时重置流量</label>
                                            <select class="form-control maxwidth-edit" id="enable_reset_traffic_when_purchase_user_general">
                                                <option value="0" {if $settings['enable_reset_traffic_when_purchase_user_general'] == false}selected{/if}>关闭</option>
                                                <option value="1" {if $settings['enable_reset_traffic_when_purchase_user_general'] == true}selected{/if}>开启</option>
                                            </select>
                                        </div>
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">购买套餐时延长等级时间</label>
                                            <select class="form-control maxwidth-edit" id="enable_add_times_when_purchase_user_general">
                                                <option value="0" {if $settings['enable_add_times_when_purchase_user_general'] == false}selected{/if}>关闭</option>
                                                <option value="1" {if $settings['enable_add_times_when_purchase_user_general'] == true}selected{/if}>开启</option>
                                            </select>
                                        </div>
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">更改用户名</label>
                                            <select class="form-control maxwidth-edit" id="enable_change_username_user_general">
                                                <option value="0" {if $settings['enable_change_username_user_general'] == false}selected{/if}>关闭</option>
                                                <option value="1" {if $settings['enable_change_username_user_general'] == true}selected{/if}>开启</option>
                                            </select>
                                        </div>
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">更改邮箱</label>
                                            <select class="form-control maxwidth-edit" id="enable_change_email_user_general">
                                                <option value="0" {if $settings['enable_change_email_user_general'] == false}selected{/if}>关闭</option>
                                                <option value="1" {if $settings['enable_change_email_user_general'] == true}selected{/if}>开启</option>
                                            </select>
                                        </div>
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">删除账户</label>
                                            <select class="form-control maxwidth-edit" id="enable_delete_account_user_general">
                                                <option value="0" {if $settings['enable_delete_account_user_general'] == false}selected{/if}>关闭</option>
                                                <option value="1" {if $settings['enable_delete_account_user_general'] == true}selected{/if}>开启</option>
                                            </select>
                                        </div>
                                        <button id="submit_user_general" type="submit" class="btn btn-brand btn-block">提交</button>
                                    </div> 
                                    <div class="tab-pane fade" id="user_checkin">
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">签到功能</label>
                                            <select class="form-control maxwidth-edit" id="enable_user_checkin">
                                                <option value="0" {if $settings['enable_user_checkin'] == false}selected{/if}>关闭</option>
                                                <option value="1" {if $settings['enable_user_checkin'] == true}selected{/if}>开启</option>
                                            </select>
                                        </div>
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">签到获取流量值的范围(单位: MB)</label>
                                            <input class="form-control maxwidth-edit" value="{$settings['user_checkin_get_traffic_value']}" id="user_checkin_get_traffic_value">
                                            <p class="form-control-guide"><i class="material-icons">info</i>签到获取流量值的范围</p>
                                        </div>
                                        <button id="submit_user_checkin" type="submit" class="btn btn-brand btn-block">提交</button>
                                    </div>
                                    <div class="tab-pane fade" id="user_notify">
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">流量不足提醒</label>
                                            <select class="form-control maxwidth-edit" id="enable_insufficient_traffic_user_notify">
                                                <option value="0" {if $settings['enable_insufficient_traffic_user_notify'] == false}selected{/if}>关闭</option>
                                                <option value="1" {if $settings['enable_insufficient_traffic_user_notify'] == true}selected{/if}>开启</option>
                                            </select>
                                        </div>
                                        <button id="submit_user_notify" type="submit" class="btn btn-brand btn-block">提交</button>
                                    </div>                            
                                </div>

                                <div class="tab-pane fade" id="mail_settings">
                                    <nav class="tab-nav margin-top-no">
                                        <ul class="nav nav-list">
                                            <li class="active">
                                                <a data-toggle="tab" href="#email_auth_settings"><i class="icon icon-lg">settings</i>&nbsp;设置</a>
                                            </li>
                                            <li>
                                                <a data-toggle="tab" href="#email_backup_settings"><i class="icon icon-lg">backup</i>&nbsp;备份</a>
                                            </li>
                                            <li>
                                                <a data-toggle="tab" href="#smtp"><i class="icon icon-lg">contact_mail</i>&nbsp;smtp</a>
                                            </li>
                                            <li>
                                                <a data-toggle="tab" href="#sendgrid"><i class="icon icon-lg">markunread_mailbox</i>&nbsp;sendgrid</a>
                                            </li>
                                            <li>
                                                <a data-toggle="tab" href="#mailgun"><i class="icon icon-lg">send</i>&nbsp;mailgun</a>
                                            </li>
                                            <li>
                                                <a data-toggle="tab" href="#ses"><i class="icon icon-lg">arrow_forward</i>&nbsp;ses</a>
                                            </li>
                                        </ul>
                                    </nav>

                                    <div class="tab-pane fade active in" id="email_auth_settings">
                                        <!-- mail_driver -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">邮件服务</label>
                                            <select id="mail_driver" class="form-control maxwidth-edit">
                                                <option value="none" {if $settings['mail_driver'] == "none"}selected{/if}>none</option>
                                                <option value="mailgun" {if $settings['mail_driver'] == "mailgun"}selected{/if}>mailgun</option>
                                                <option value="sendgrid" {if $settings['mail_driver'] == "sendgrid"}selected{/if}>sendgrid</option>
                                                <option value="ses" {if $settings['mail_driver'] == "ses"}selected{/if}>ses</option>
                                                <option value="smtp" {if $settings['mail_driver'] == "smtp"}selected{/if}>smtp</option>
                                            </select>
                                        </div>

                                        <button id="submit_mail" type="submit" class="btn btn-brand btn-dense">提交</button>

                                        <!-- smtp_test_recipient -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">测试邮件收件人</label>
                                            <input class="form-control maxwidth-edit" id="testing_email_recipients">
                                            <p class="form-control-guide"><i class="material-icons">info</i>邮件配置保存完成后，如需验证是否可用，可在上方填写一个有效邮箱，系统将发送一封测试邮件到该邮箱。如果能够正常接收，则说明配置可用</p>
                                            {if $settings['mail_driver'] == "none"}
                                            <p class="form-control-guide"><i class="material-icons">info</i>如需使用发信测试功能，请先在上方选择一个发信方式，并配置有效的相关参数</p>
                                            {/if}
                                        </div>

                                        <button id="submit_email_test" type="submit" class="btn btn-brand btn-dense" {if $settings['mail_driver'] == "none"}disabled{/if}>测试</button>
                                    </div>
                                    <div class="tab-pane fade" id="email_backup_settings">
                                        <p class="form-control-guide"><i class="material-icons">info</i>需添加定时任务：php /this/is/your/website/path/xcat Backup full / simple</p>
                                        <p class="form-control-guide"><i class="material-icons">info</i>full 将整体数据备份；simple 将只备份核心数据</p>
                                        <!-- auto_backup_email -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">接收备份的邮箱</label>
                                            <input class="form-control maxwidth-edit" id="auto_backup_email" value="{$settings['auto_backup_email']}">
                                        </div>
                                        <!-- auto_backup_password -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">备份的压缩密码</label>
                                            <input class="form-control maxwidth-edit" id="auto_backup_password" value="{$settings['auto_backup_password']}">
                                            <p class="form-control-guide"><i class="material-icons">info</i>留空将不加密备份压缩包</p>
                                        </div>
                                        <!-- auto_backup_notify -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">备份是否通知到TG群中</label>
                                            <select id="auto_backup_notify" class="form-control maxwidth-edit">
                                                <option value="0" {if $settings['auto_backup_notify'] == "0"}selected{/if}>关闭</option>
                                                <option value="1" {if $settings['auto_backup_notify'] == "1"}selected{/if}>开启</option>
                                            </select>
                                        </div>

                                        <button id="submit_email_backup" type="submit" class="btn btn-brand btn-dense">提交</button>
                                    </div>
                                    <div class="tab-pane fade" id="smtp">
                                        <!-- smtp_host -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">SMTP主机地址</label>
                                            <input class="form-control maxwidth-edit" id="smtp_host" value="{$settings['smtp_host']}">
                                            <p class="form-control-guide"><i class="material-icons">info</i>例如：smtpdm-ap-southeast-1.aliyun.com</p>
                                        </div>
                                        <!-- smtp_username -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">SMTP账户名</label>
                                            <input class="form-control maxwidth-edit" id="smtp_username" value="{$settings['smtp_username']}">
                                            <p class="form-control-guide"><i class="material-icons">info</i>例如：no-reply@airport.com</p>
                                        </div>
                                        <!-- smtp_password -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">SMTP账户密码</label>
                                            <input class="form-control maxwidth-edit" id="smtp_password" value="{$settings['smtp_password']}">
                                            <p class="form-control-guide"><i class="material-icons">info</i>如果你使用 QQ 邮箱或 163 邮箱，此处应当填写单独的授权码</p>
                                        </div>
                                        <!-- smtp_port -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">SMTP端口</label>
                                            <select id="smtp_port" class="form-control maxwidth-edit">
                                                <option value="465" {if $settings['smtp_port'] == "465"}selected{/if}>465</option>
                                                <option value="587" {if $settings['smtp_port'] == "587"}selected{/if}>587</option>
                                                <option value="2525" {if $settings['smtp_port'] == "2525"}selected{/if}>2525</option>
                                                <option value="25" {if $settings['smtp_port'] == "25"}selected{/if}>25</option>
                                            </select>
                                            <p class="form-control-guide"><i class="material-icons">info</i>常见端口一般就这些</p>
                                        </div>
                                        <!-- smtp_name -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">SMTP发信名称</label>
                                            <input class="form-control maxwidth-edit" id="smtp_name" value="{$settings['smtp_name']}">
                                            <p class="form-control-guide"><i class="material-icons">info</i>这里的设置在邮箱的邮件列表中可见。你可以设置为网站名称</p>
                                        </div>
                                        <!-- smtp_sender -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">SMTP账户发信地址</label>
                                            <input class="form-control maxwidth-edit" id="smtp_sender" value="{$settings['smtp_sender']}">
                                            <p class="form-control-guide"><i class="material-icons">info</i>如不知道填什么，请与此项保持一致：SMTP账户名</p>
                                        </div>
                                        <!-- smtp_ssl -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">是否使用 TLS/SSL 发信</label>
                                            <select id="smtp_ssl" class="form-control maxwidth-edit">
                                                <option value="1" {if $settings['smtp_ssl'] == true}selected{/if}>开启</option>
                                                <option value="0" {if $settings['smtp_ssl'] == false}selected{/if}>关闭</option>
                                            </select>
                                        </div>
                                        <!-- smtp_bbc -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">发给用户的邮件密送给指定邮箱备份</label>
                                            <input class="form-control maxwidth-edit" id="smtp_bbc" value="{$settings['smtp_bbc']}">
                                            <p class="form-control-guide"><i class="material-icons">info</i>如无需使用此功能，请留空</p>
                                        </div>

                                        <button id="submit_smtp" type="submit" class="btn  btn-brand btn-dense">提交</button>
                                    </div>
                                    <div class="tab-pane fade" id="sendgrid">
                                        <!-- sendgrid_key -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">Sendgrid 密钥</label>
                                            <input class="form-control maxwidth-edit" id="sendgrid_key" value="{$settings['sendgrid_key']}">
                                        </div>
                                        <!-- sendgrid_sender -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">Sendgrid 发件邮箱</label>
                                            <input class="form-control maxwidth-edit" id="sendgrid_sender" value="{$settings['sendgrid_sender']}">
                                        </div>
                                        <!-- sendgrid_name -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">Sendgrid 发件人名称</label>
                                            <input class="form-control maxwidth-edit" id="sendgrid_name" value="{$settings['sendgrid_name']}">
                                        </div>

                                        <button id="submit_sendgrid" type="submit" class="btn btn-brand btn-dense">提交</button>
                                    </div>
                                    <div class="tab-pane fade" id="mailgun">
                                        <!-- mailgun_key -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">Mailgun 密钥</label>
                                            <input class="form-control maxwidth-edit" id="mailgun_key" value="{$settings['mailgun_key']}">
                                        </div>
                                        <!-- mailgun_domain -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">Mailgun 域名</label>
                                            <input class="form-control maxwidth-edit" id="mailgun_domain" value="{$settings['mailgun_domain']}">
                                        </div>
                                        <!-- mailgun_sender -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">Mailgun 发送者</label>
                                            <input class="form-control maxwidth-edit" id="mailgun_sender" value="{$settings['mailgun_sender']}">
                                        </div>

                                        <button id="submit_mailgun" type="submit" class="btn btn-brand btn-dense">提交</button>
                                    </div>
                                    <div class="tab-pane fade" id="ses">
                                        <!-- aws_access_key_id -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">密钥 ID</label>
                                            <input class="form-control maxwidth-edit" id="aws_access_key_id" value="{$settings['aws_access_key_id']}">
                                        </div>
                                        <!-- aws_secret_access_key -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">密钥 KEY</label>
                                            <input class="form-control maxwidth-edit" id="aws_secret_access_key" value="{$settings['aws_secret_access_key']}">
                                        </div>

                                        <button id="submit_ses" type="submit" class="btn btn-brand btn-dense">提交</button>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="payment_settings">
                                    <nav class="tab-nav margin-top-no">
                                        <ul class="nav nav-list">
                                            <li class="active">
                                                <a data-toggle="tab" href="#public_payment_settings"><i class="icon icon-lg">settings</i>&nbsp;设置</a>
                                            </li>
                                            {foreach $payment_gateways as $key => $value}
                                            <li>
                                                <a data-toggle="tab" href="#{$value}">{$key}</a>
                                            </li>
                                            {/foreach}
                                        </ul>
                                    </nav>

                                    <div class="tab-pane fade active in" id="public_payment_settings">
                                        
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">支付宝</label>
                                                <input class="form-control maxwidth-edit" id="alipay_payment" value="{$settings['alipay_payment']}">
                                        </div>
                                        
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">微信支付</label>
                                            <input class="form-control maxwidth-edit" id="wechatpay_payment" value="{$settings['wechatpay_payment']}">
                                        </div>

                                        <div class="form-group form-group-label">
                                            <label class="floating-label">虚拟币支付</label>
                                            <input class="form-control maxwidth-edit" id="cryptopay_payment" value="{$settings['cryptopay_payment']}">
                                        </div>
                                        

                                        <button id="submit_payment_gateway" type="submit" class="btn btn-block btn-brand">提交</button>
                                    </div>
                                    
                                    <div class="tab-pane fade" id="paytaro">
                                        <p class="form-control-guide"><i class="material-icons">info</i>此处申请： <a href="https://paytaro.com" target="view_window">https://paytaro.com</a></p>
                                        <!-- paytaro_app_id -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">paytaro id</label>
                                            <input class="form-control maxwidth-edit" id="paytaro_app_id" value="{$settings['paytaro_app_id']}">
                                        </div>
                                        <!-- paytaro_app_secret -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">paytaro secret</label>
                                            <input class="form-control maxwidth-edit" id="paytaro_app_secret" value="{$settings['paytaro_app_secret']}">
                                        </div>

                                        <button id="submit_paytaro" type="submit" class="btn btn-block btn-brand">提交</button>
                                    </div>
                                    
                                    <div class="tab-pane fade" id="paybeaver">
                                        <p class="form-control-guide"><i class="material-icons">info</i>此处申请： <a href="https://merchant.paybeaver.com" target="view_window">https://merchant.paybeaver.com</a></p>
                                        <!-- paybeaver_app_id -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">Paybeaver id</label>
                                            <input class="form-control maxwidth-edit" id="paybeaver_app_id" value="{$settings['paybeaver_app_id']}">
                                        </div>
                                        <!-- paybeaver_app_secret -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">Paybeaver secret</label>
                                            <input class="form-control maxwidth-edit" id="paybeaver_app_secret" value="{$settings['paybeaver_app_secret']}">
                                        </div>

                                        <button id="submit_paybeaver" type="submit" class="btn btn-block btn-brand">提交</button>
                                    </div>

                                    <div class="tab-pane fade" id="tronapipay">
                                        <p class="form-control-guide"><i class="material-icons">info</i>此处申请： <a href="https://pro.tronapi.com" target="view_window">https://pro.tronapi.com</a></p>
                                        <!-- tronapipay public key -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">Public Key</label>
                                            <input class="form-control maxwidth-edit" id="tronapipay_public_key" value="{$settings['tronapipay_public_key']}">
                                        </div>
                                        <!-- tronapipay private key -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">Private Key</label>
                                            <input class="form-control maxwidth-edit" id="tronapipay_private_key" value="{$settings['tronapipay_private_key']}">
                                        </div>

                                        <button id="submit_tronapipay" type="submit" class="btn btn-block btn-brand">提交</button>
                                    </div>

                                    <div class="tab-pane fade" id="payjs">
                                        <p class="form-control-guide"><i class="material-icons">info</i>此处申请： <a href="https://payjs.cn" target="view_window">https://payjs.cn</a></p>
                                        <!-- payjs_mchid -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">payjs_mchid</label>
                                            <input class="form-control maxwidth-edit" id="payjs_mchid" value="{$settings['payjs_mchid']}">
                                        </div>
                                        <!-- payjs_key -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">payjs_key</label>
                                            <input class="form-control maxwidth-edit" id="payjs_key" value="{$settings['payjs_key']}">
                                        </div>

                                        <button id="submit_payjs_pay" type="submit" class="btn btn-block btn-brand">提交</button>
                                    </div>

                                    <div class="tab-pane fade" id="paymentwall">
                                        <p class="form-control-guide"><i class="material-icons">info</i>此处申请： <a href="https://www.paymentwall.com/cn" target="view_window">https://www.paymentwall.com/cn</a></p>
                                        <!-- pmw_publickey -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">pmw公钥</label>
                                            <textarea class="form-control maxwidth-edit" id="pmw_publickey" rows="5">{$settings['pmw_publickey']}</textarea>
                                        </div>
                                        <!-- pmw_privatekey -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">pmw私钥</label>
                                            <textarea class="form-control maxwidth-edit" id="pmw_privatekey" rows="7">{$settings['pmw_privatekey']}</textarea>
                                        </div>
                                        <!-- pmw_widget -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">pmw_widget</label>
                                            <input class="form-control maxwidth-edit" id="pmw_widget" value="{$settings['pmw_widget']}">
                                        </div>
                                        <!-- pmw_height -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">pmw_height</label>
                                            <input class="form-control maxwidth-edit" id="pmw_height" value="{$settings['pmw_height']}">
                                        </div>

                                        <button id="submit_paymentwall" type="submit" class="btn btn-block btn-brand">提交</button>
                                    </div>

                                    <div class="tab-pane fade" id="theadpay">
                                        <p class="form-control-guide"><i class="material-icons">info</i>此处申请：<a href="https://theadpay.com" target="view_window">https://theadpay.com</a></p>
                                        <!-- theadpay_url -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">theadpay_url</label>
                                            <input class="form-control maxwidth-edit" id="theadpay_url" value="{$settings['theadpay_url']}">
                                        </div>
                                        <!-- theadpay_mchid -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">theadpay_mchid</label>
                                            <input class="form-control maxwidth-edit" id="theadpay_mchid" value="{$settings['theadpay_mchid']}">
                                        </div>
                                        <!-- theadpay_key -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">theadpay_key</label>
                                            <input class="form-control maxwidth-edit" id="theadpay_key" value="{$settings['theadpay_key']}">
                                        </div>

                                        <button id="submit_theadpay" type="submit" class="btn btn-block btn-brand">提交</button>
                                    </div>
                                    
                                    <div class="tab-pane fade" id="stripe">
                                        <p class="form-control-guide"><i class="material-icons">warning</i>提供虚拟专用网络业务符合 Stripe 用户协议，但可能不符合 Stripe 提供的部分支付通道（如支付宝、微信）用户协议，相关支付通道可能存在被关闭的风险</p>
                                        <h5>支付渠道</h5>
                                        <!-- stripe_card -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">银行卡支付</label>
                                            <select id="stripe_card" class="form-control maxwidth-edit">
                                                <option value="1" {if $settings['stripe_card'] == true}selected{/if}>启用</option>
                                                <option value="0" {if $settings['stripe_card'] == false}selected{/if}>停用</option>
                                            </select>
                                        </div>
                                        <h5>支付设置</h5>
                                        <!-- stripe_currency -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">货币单位</label>
                                            <input class="form-control maxwidth-edit" id="stripe_currency" value="{$settings['stripe_currency']}">
                                        </div>
                                        <!-- stripe_min_recharge -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">最低充值限额（整数）</label>
                                            <input class="form-control maxwidth-edit" id="stripe_min_recharge" value="{$settings['stripe_min_recharge']}">
                                        </div>
                                         <!-- stripe_max_recharge -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">最高充值限额（整数）</label>
                                            <input class="form-control maxwidth-edit" id="stripe_max_recharge" value="{$settings['stripe_max_recharge']}">
                                        </div>
                                        <!-- stripe_pk -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">stripe_pk</label>
                                            <input class="form-control maxwidth-edit" id="stripe_pk" value="{$settings['stripe_pk']}">
                                        </div>
                                        <!-- stripe_sk -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">stripe_sk</label>
                                            <input class="form-control maxwidth-edit" id="stripe_sk" value="{$settings['stripe_sk']}">
                                        </div>
                                        <!-- stripe_webhook_key -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">WebHook密钥</label>
                                            <input class="form-control maxwidth-edit" id="stripe_webhook_key" value="{$settings['stripe_webhook_key']}">
                                        </div>

                                        <button id="submit_stripe" type="submit" class="btn btn-block btn-brand">提交</button>
                                    </div>

                                    <div class="tab-pane fade" id="vmqpay">
                                        <p class="form-control-guide"><i class="material-icons">info</i>此支付方式需自建网关并配置各项参数。访问 <a href="https://github.com/szvone/vmqphp" target="view_window">https://github.com/szvone/vmqphp</a> 了解更多</p>
                                        <p class="form-control-guide"><i class="material-icons">info</i>开源的 Android 监听端（推荐）：<a href="https://gitee.com/yuniks/VMQAPK" target="view_window">https://gitee.com/yuniks/VMQAPK</a></p>
                                        <p class="form-control-guide"><i class="material-icons">info</i>不开源的 Windows 监听端（不推荐）：<a href="https://toscode.gitee.com/pmhw/Vpay" target="view_window">https://toscode.gitee.com/pmhw/Vpay</a></p>
                                        <!-- vmq_gateway -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">V免签网关</label>
                                            <input class="form-control maxwidth-edit" id="vmq_gateway" value="{$settings['vmq_gateway']}">
                                            <p class="form-control-guide"><i class="material-icons">info</i>形如：https://pay.com</p>
                                        </div>
                                        <!-- vmq_key -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">V免签密钥</label>
                                            <input class="form-control maxwidth-edit" id="vmq_key" value="{$settings['vmq_key']}">
                                        </div>

                                        <button id="submit_vmq_pay" type="submit" class="btn btn-block btn-brand">提交</button>
                                    </div>

                                    <div class="tab-pane fade" id="f2fpay">
                                        <p class="form-control-guide"><i class="material-icons">info</i>此处申请： <a href="https://b.alipay.com/signing/productDetailV2.htm?productId=I1011000290000001003" target="view_window">https://b.alipay.com/signing/productDetailV2.htm?productId=I1011000290000001003</a></p>
                                        <!-- f2f_pay_app_id -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">App ID</label>
                                            <input class="form-control maxwidth-edit" id="f2f_pay_app_id" value="{$settings['f2f_pay_app_id']}">
                                        </div>
                                        <!-- f2f_pay_pid -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">PID</label>
                                            <input class="form-control maxwidth-edit" id="f2f_pay_pid" value="{$settings['f2f_pay_pid']}">
                                            <p class="form-control-guide"><i class="material-icons">info</i>此项可留空，不影响使用</p>
                                        </div>
                                        <!-- f2f_pay_public_key -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">公钥</label>
                                            <textarea class="form-control maxwidth-edit" id="f2f_pay_public_key" rows="4">{$settings['f2f_pay_public_key']}</textarea>
                                        </div>
                                        <!-- f2f_pay_private_key -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">私钥</label>
                                            <textarea class="form-control maxwidth-edit" id="f2f_pay_private_key" rows="12">{$settings['f2f_pay_private_key']}</textarea>
                                        </div>
                                        <!-- f2f_pay_notify_url -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">自定义回调地址</label>
                                            <input class="form-control maxwidth-edit" id="f2f_pay_notify_url" value="{$settings['f2f_pay_notify_url']}">
                                            <p class="form-control-guide"><i class="material-icons">info</i>此项可留空，不影响使用</p>
                                        </div>

                                        <button id="submit_f2f_pay" type="submit" class="btn btn-block btn-brand">提交</button>
                                    </div>
                                    <div class="tab-pane fade" id="epay">
                                        <p class="form-control-guide"><i class="material-icons">info</i> SSPanel-UIM Dev Team提醒您注意：易支付商家经常跑路！造成的损失由您自行承担</p>
                                        <!-- epay_url -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">易支付URL</label>
                                            <input class="form-control maxwidth-edit" id="epay_url" value="{$settings['epay_url']}">
											<p class="form-control-guide"><i class="material-icons">info</i>不同易支付url后缀不同，1：域名后面带/ 2：域名后面带submit.php/</p>
                                        </div>
                                        <!-- epay_pid -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">商户ID</label>
                                            <input class="form-control maxwidth-edit" id="epay_pid" value="{$settings['epay_pid']}">
                                            <p class="form-control-guide"><i class="material-icons">info</i>必填</p>
                                        </div>
                                        <!-- epay_key -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">商户Key</label>
											<input class="form-control maxwidth-edit" id="epay_key" value="{$settings['epay_key']}">
                                        
											<p class="form-control-guide"><i class="material-icons">info</i>必填</p>
                                        </div>
                                                                               
                                        <button id="submit_e_pay" type="submit" class="btn btn-block btn-brand">提交</button>
                                     </div>
                                </div>

                                <div class="tab-pane fade" id="customer_service_system_settings">
                                    <nav class="tab-nav margin-top-no">
                                        <ul class="nav nav-list">
                                            <li class="active">
                                                <a data-toggle="tab" href="#web_customer_service_system"><i class="icon icon-lg">settings</i>&nbsp;网页客服</a>
                                            </li>
                                            <li>
                                                <a data-toggle="tab" href="#admin_contact"><i class="icon icon-lg">call</i>&nbsp;联系站长</a>
                                            </li>
                                        </ul>
                                    </nav>
                                    <div class="tab-pane fade active in" id="web_customer_service_system">
                                        <!-- live_chat -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">网页客服系统</label>
                                            <select id="live_chat" class="form-control maxwidth-edit">
                                                <option value="none" {if $settings['live_chat'] == "none"}selected{/if}>不启用</option>
                                                <option value="tawk" {if $settings['live_chat'] == "tawk"}selected{/if}>Tawk</option>
                                                <option value="crisp" {if $settings['live_chat'] == "crisp"}selected{/if}>Crisp</option>
                                                <option value="livechat" {if $settings['live_chat'] == "livechat"}selected{/if}>LiveChat</option>
                                                <option value="mylivechat" {if $settings['live_chat'] == "mylivechat"}selected{/if}>MyLiveChat</option>
                                            </select>
                                            <p class="form-control-guide"><i class="material-icons">info</i>目前仅 Crisp 与 LiveChat 支持在聊天时传递用户部分账户信息（如账户余额、到期时间、已用流量和剩余流量等）</p>
                                        </div>
                                        <!-- tawk_id -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">Tawk</label>
                                            <input class="form-control maxwidth-edit" id="tawk_id" value="{$settings['tawk_id']}">
                                            <p class="form-control-guide"><i class="material-icons">info</i>在 <a href="https://tawk.to" target="view_window">https://tawk.to</a> 申请，这应该是 24 位字符</p>
                                        </div>
                                        <!-- crisp_id -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">Crisp</label>
                                            <input class="form-control maxwidth-edit" id="crisp_id" value="{$settings['crisp_id']}">
                                            <p class="form-control-guide"><i class="material-icons">info</i>在 <a href="https://crisp.chat/en" target="view_window">https://crisp.chat/en</a> 申请，这应该是一个 UUID</p>
                                        </div>
                                        <!-- livechat_id -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">LiveChat</label>
                                            <input class="form-control maxwidth-edit" id="livechat_id" value="{$settings['livechat_id']}">
                                            <p class="form-control-guide"><i class="material-icons">info</i>在 <a href="https://www.livechat.com/cn" target="view_window">https://www.livechat.com/cn</a> 申请，这应该是 8 位数字</p>
                                        </div>
                                        <!-- mylivechat_id -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">MyLiveChat</label>
                                            <input class="form-control maxwidth-edit" id="mylivechat_id" value="{$settings['mylivechat_id']}">
                                            <p class="form-control-guide"><i class="material-icons">info</i>在 <a href="https://www.mylivechat.com" target="view_window">https://www.mylivechat.com</a> 申请，这个我不知道</p>
                                        </div>

                                        <button id="submit_web_customer_service_system" type="submit" class="btn btn-block btn-brand">提交</button>
                                    </div>
                                    <div class="tab-pane fade" id="admin_contact">
                                        <p class="form-control-guide"><i class="material-icons">info</i>注意：留空的联系方式将不显示</p>
                                        <p class="form-control-guide"><i class="material-icons">info</i>支持使用 HTML 标签。你可以通过配置 a 标签，达到点击即可唤起对应app会话窗口的效果</p>
                                        <p class="form-control-guide"><i class="material-icons">info</i>若开启此功能，此页面展示的联系方式将显示在：</p>
                                        <p class="form-control-guide"><i class="material-icons">info</i>1. 注册或重置密码页面点击【无法收到验证码】按钮</p>
                                        <p class="form-control-guide"><i class="material-icons">info</i>2. 用户账户被停用的告知页面</p>
                                        <p class="form-control-guide"><i class="material-icons">info</i>3. 充值页面提示充值未到账的用户</p>
                                        <p class="form-control-guide"><i class="material-icons">info</i>4. 用户中心首页公告栏下方</p>
                                        <!-- enable_admin_contact -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">是否显示站长联系方式</label>
                                            <select id="enable_admin_contact" class="form-control maxwidth-edit">
                                                <option value="0" {if $settings['enable_admin_contact'] == false}selected{/if}>关闭</option>
                                                <option value="1" {if $settings['enable_admin_contact'] == true}selected{/if}>开启</option>
                                            </select>
                                        </div>
                                        <!-- admin_contact1 -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">站长联系方式一</label>
                                            <input class="form-control maxwidth-edit" id="admin_contact1" value="{htmlspecialchars($settings['admin_contact1'])}">
                                        </div>
                                        <!-- admin_contact2 -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">站长联系方式二</label>
                                            <input class="form-control maxwidth-edit" id="admin_contact2" value="{htmlspecialchars($settings['admin_contact2'])}">
                                        </div>
                                        <!-- admin_contact3 -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">站长联系方式三</label>
                                            <input class="form-control maxwidth-edit" id="admin_contact3" value="{htmlspecialchars($settings['admin_contact3'])}">
                                        </div>

                                        <button id="submit_admin_contact" type="submit" class="btn btn-block btn-brand">提交</button>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="verification_code_settings">
                                    <nav class="tab-nav margin-top-no">
                                        <ul class="nav nav-list">
                                            <li class="active">
                                                <a data-toggle="tab" href="#verification_code_public_settings"><i class="icon icon-lg">settings</i>&nbsp;设置</a>
                                            </li>
                                            <li>
                                                <a data-toggle="tab" href="#recaptcha"><i class="icon icon-lg">face</i>&nbsp;reCAPTCHA</a>
                                            </li>
                                            <li>
                                                <a data-toggle="tab" href="#geetest"><i class="icon icon-lg">extension</i>&nbsp;Geetest</a>
                                            </li>
                                        </ul>
                                    </nav>

                                    <div class="tab-pane fade active in" id="verification_code_public_settings">
                                        <!-- captcha_provider -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">验证码提供商</label>
                                            <select id="captcha_provider" class="form-control maxwidth-edit">
                                                <option value="recaptcha" {if $settings['captcha_provider'] == "recaptcha"}selected{/if}>reCaptcha</option>
                                                <option value="geetest" {if $settings['captcha_provider'] == "geetest"}selected{/if}>Geetest</option>
                                            </select>
                                        </div>
                                        <!-- enable_reg_captcha -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">注册验证码</label>
                                            <select id="enable_reg_captcha" class="form-control maxwidth-edit">
                                                <option value="0" {if $settings['enable_reg_captcha'] == false}selected{/if}>关闭</option>
                                                <option value="1" {if $settings['enable_reg_captcha'] == true}selected{/if}>开启</option>
                                            </select>
                                        </div>
                                        <!-- enable_login_captcha -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">登录验证码</label>
                                            <select id="enable_login_captcha" class="form-control maxwidth-edit">
                                                <option value="0" {if $settings['enable_login_captcha'] == false}selected{/if}>关闭</option>
                                                <option value="1" {if $settings['enable_login_captcha'] == true}selected{/if}>开启</option>
                                            </select>
                                        </div>
                                        <!-- enable_checkin_captcha -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">签到验证码</label>
                                            <select id="enable_checkin_captcha" class="form-control maxwidth-edit">
                                                <option value="0" {if $settings['enable_checkin_captcha'] == false}selected{/if}>关闭</option>
                                                <option value="1" {if $settings['enable_checkin_captcha'] == true}selected{/if}>开启</option>
                                            </select>
                                        </div>

                                        <button id="submit_verify_code" type="submit" class="btn btn-block btn-brand">提交</button>
                                    </div>
                                    <div class="tab-pane fade" id="recaptcha">
                                        <p class="form-control-guide"><i class="material-icons">info</i>在 <a href="https://www.google.com/recaptcha/admin/create" target="view_window">https://www.google.com/recaptcha/admin/create</a> 申请，选择【reCAPTCHA 第 2 版】的子选项【进行人机身份验证复选框】</p>
                                        <!-- recaptcha_sitekey -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">reCaptcha Site Key</label>
                                            <input class="form-control maxwidth-edit" id="recaptcha_sitekey" value="{$settings['recaptcha_sitekey']}">
                                        </div>
                                        <!-- recaptcha_secret -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">reCaptcha Secret</label>
                                            <input class="form-control maxwidth-edit" id="recaptcha_secret" value="{$settings['recaptcha_secret']}">
                                        </div>

                                        <button id="submit_recaptcha" type="submit" class="btn btn-block btn-brand">提交</button>
                                    </div>
                                    <div class="tab-pane fade" id="geetest">
                                        <p class="form-control-guide"><i class="material-icons">info</i>在 <a href="https://gtaccount.geetest.com/sensebot/overview" target="view_window">https://gtaccount.geetest.com/sensebot/overview</a> 申请</p>
                                        <!-- geetest_id -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">Geetest ID</label>
                                            <input class="form-control maxwidth-edit" id="geetest_id" value="{$settings['geetest_id']}">
                                        </div>
                                        <!-- geetest_key -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">Geetest Key</label>
                                            <input class="form-control maxwidth-edit" id="geetest_key" value="{$settings['geetest_key']}">
                                        </div>

                                        <button id="submit_geetest" type="submit" class="btn btn-block btn-brand">提交</button>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="personalise_settings">
                                    <nav class="tab-nav margin-top-no">
                                        <ul class="nav nav-list">
                                            <li class="active">
                                                <a data-toggle="tab" href="#custom_background_image"><i class="icon icon-lg">image</i>&nbsp;背景图像</a>
                                            </li>
                                        </ul>
                                    </nav>

                                    <div class="tab-pane fade active in" id="custom_background_image">
                                        <p class="form-control-guide"><i class="material-icons">info</i>默认背景图片地址：/theme/material/css/images/bg/amber.jpg <a href="/theme/material/css/images/bg/amber.jpg">预览</a></p>
                                        <p class="form-control-guide"><i class="material-icons">info</i>自带背景图片一地址：/theme/material/css/images/bg/streak.jpg <a href="/theme/material/css/images/bg/streak.jpg">预览</a></p>
                                        <p class="form-control-guide"><i class="material-icons">info</i>自带背景图片二地址：/theme/material/css/images/bg/geometry.jpg <a href="/theme/material/css/images/bg/geometry.jpg">预览</a></p>
                                        <p class="form-control-guide"><i class="material-icons">info</i>如需自定义，图片地址可以指向 public 目录或图床图片地址</p>
                                        <!-- user_center_bg -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">是否启用自定义用户中心背景图片</label>
                                            <select id="user_center_bg" class="form-control maxwidth-edit">
                                                <option value="0" {if $settings['user_center_bg'] == false}selected{/if}>关闭</option>
                                                <option value="1" {if $settings['user_center_bg'] == true}selected{/if}>开启</option>
                                            </select>
                                        </div>
                                        <!-- admin_center_bg -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">是否启用自定义管理中心背景图片</label>
                                            <select id="admin_center_bg" class="form-control maxwidth-edit">
                                                <option value="0" {if $settings['admin_center_bg'] == false}selected{/if}>关闭</option>
                                                <option value="1" {if $settings['admin_center_bg'] == true}selected{/if}>开启</option>
                                            </select>
                                        </div>
                                        <!-- user_center_bg_addr -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">用户中心背景图片地址</label>
                                            <input class="form-control maxwidth-edit" id="user_center_bg_addr" value="{$settings['user_center_bg_addr']}">
                                        </div>
                                        <!-- admin_center_bg_addr -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">管理中心背景图片地址</label>
                                            <input class="form-control maxwidth-edit" id="admin_center_bg_addr" value="{$settings['admin_center_bg_addr']}">
                                        </div>

                                        <button id="submit_custom_background_image" type="submit" class="btn btn-block btn-brand">提交</button>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="registration_settings">
                                    <nav class="tab-nav margin-top-no">
                                        <ul class="nav nav-list">
                                            <li class="active">
                                                <a data-toggle="tab" href="#reg_mode_and_verify"><i class="icon icon-lg">vpn_key</i>&nbsp;注册模式与验证</a>
                                            </li>
                                            <li>
                                                <a data-toggle="tab" href="#register_default_value"><i class="icon icon-lg">sd_card</i>&nbsp;默认值</a>
                                            </li>
                                        </ul>
                                    </nav>

                                    <div class="tab-pane fade active in" id="reg_mode_and_verify">
                                        <!-- reg_mode -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">注册模式设置</label>
                                            <select id="reg_mode" class="form-control maxwidth-edit">
                                                <option value="close" {if $settings['reg_mode'] == 'close'}selected{/if}>关闭公共注册</option>
                                                <option value="open" {if $settings['reg_mode'] == 'open'}selected{/if}>开启公共注册</option>
                                                <option value="invite" {if $settings['reg_mode'] == 'invite'}selected{/if}>仅限用户邀请注册</option>
                                            </select>
                                        </div>
                                        <!-- reg_email_verify -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">注册邮箱验证码验证</label>
                                            <select id="reg_email_verify" class="form-control maxwidth-edit">
                                                <option value="0" {if $settings['reg_email_verify'] == false}selected{/if}>关闭</option>
                                                <option value="1" {if $settings['reg_email_verify'] == true}selected{/if}>开启</option>
                                            </select>
                                        </div>
                                        <!-- email_verify_ttl -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">注册邮箱验证码有效期（单位：秒）</label>
                                            <input class="form-control maxwidth-edit" id="email_verify_ttl" value="{$settings['email_verify_ttl']}">
                                        </div>
                                        <!-- email_verify_ip_limit -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">验证码有效期内单个ip可请求的发件次数</label>
                                            <input class="form-control maxwidth-edit" id="email_verify_ip_limit" value="{$settings['email_verify_ip_limit']}">
                                        </div>

                                        <button id="submit_reg_mode_and_verify" type="submit" class="btn btn-block btn-brand">提交</button>
                                    </div>

                                    <div class="tab-pane fade" id="register_default_value">
                                        <h5>注册默认</h5>
                                        <!-- sign_up_for_free_traffic -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">注册时赠送的流量 ( 单位:GB )</label>
                                            <input class="form-control maxwidth-edit" id="sign_up_for_free_traffic" value="{$settings['sign_up_for_free_traffic']}">
                                        </div>
                                        <!-- sign_up_for_free_time -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">注册时赠送的账户时长（单位：天）</label>
                                            <input class="form-control maxwidth-edit" id="sign_up_for_free_time" value="{$settings['sign_up_for_free_time']}">
                                        </div>
                                        <!-- sign_up_for_class -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">注册时设定的等级</label>
                                            <input class="form-control maxwidth-edit" id="sign_up_for_class" value="{$settings['sign_up_for_class']}">
                                        </div>
                                        <!-- sign_up_for_class_time -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">注册时设定的等级时长（单位：天）</label>
                                            <input class="form-control maxwidth-edit" id="sign_up_for_class_time" value="{$settings['sign_up_for_class_time']}">
                                        </div>
                                        <h5>注册限制</h5>
                                        <!-- sign_up_for_invitation_codes -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">初始邀请注册链接使用次数限制</label>
                                            <input class="form-control maxwidth-edit" id="sign_up_for_invitation_codes" value="{$settings['sign_up_for_invitation_codes']}">
                                        </div>
                                        <!-- connection_device_limit -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">连接设备限制</label>
                                            <input class="form-control maxwidth-edit" id="connection_device_limit" value="{$settings['connection_device_limit']}">
                                        </div>
                                        <!-- connection_rate_limit -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">使用速率限制</label>
                                            <input class="form-control maxwidth-edit" id="connection_rate_limit" value="{$settings['connection_rate_limit']}">
                                        </div>
                                        <h5>SSR 设置</h5>
                                        <!-- sign_up_for_method -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">默认加密</label>
                                            <input class="form-control maxwidth-edit" id="sign_up_for_method" value="{$settings['sign_up_for_method']}">
                                        </div>
                                        <!-- sign_up_for_protocol -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">默认协议</label>
                                            <input class="form-control maxwidth-edit" id="sign_up_for_protocol" value="{$settings['sign_up_for_protocol']}">
                                        </div>
                                        <!-- sign_up_for_protocol_param -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">默认协议参数</label>
                                            <input class="form-control maxwidth-edit" id="sign_up_for_protocol_param" value="{$settings['sign_up_for_protocol_param']}">
                                        </div>
                                        <!-- sign_up_for_obfs -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">默认混淆</label>
                                            <input class="form-control maxwidth-edit" id="sign_up_for_obfs" value="{$settings['sign_up_for_obfs']}">
                                        </div>
                                        <!-- sign_up_for_obfs_param -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">默认混淆参数</label>
                                            <input class="form-control maxwidth-edit" id="sign_up_for_obfs_param" value="{$settings['sign_up_for_obfs_param']}">
                                        </div>
                                        <h5>其他</h5>
                                        <!-- sign_up_for_daily_report -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">注册后是否默认接收每日用量邮件推送</label>
                                            <select id="sign_up_for_daily_report" class="form-control maxwidth-edit">
                                                <option value="0" {if $settings['sign_up_for_daily_report'] == false}selected{/if}>关闭</option>
                                                <option value="1" {if $settings['sign_up_for_daily_report'] == true}selected{/if}>开启</option>
                                            </select>
                                        </div>

                                        <button id="submit_register_default_value" type="submit" class="btn btn-block btn-brand">提交</button>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="invitation_settings">
                                    <nav class="tab-nav margin-top-no">
                                        <ul class="nav nav-list">
                                            <li class="active">
                                                <a data-toggle="tab" href="#rebate_mode"><i class="icon icon-lg">developer_mode</i>&nbsp;模式</a>
                                            </li>
                                            <li>
                                                <a data-toggle="tab" href="#invitation_reward"><i class="icon icon-lg">card_giftcard</i>&nbsp;奖励</a>
                                            </li>
                                            <li>
                                                <a data-toggle="tab" href="#withdraw"><i class="icon icon-lg">move_down</i>&nbsp;提现</a>
                                            </li>
                                        </ul>
                                    </nav>

                                    <div class="tab-pane fade active in" id="rebate_mode">
                                        <!-- invitation_mode -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">邀请模式</label>
                                            <select id="invitation_mode" class="form-control maxwidth-edit">
                                                <option value="registration_only" {if $settings['invitation_mode'] == 'registration_only'}selected{/if}>
                                                仅使用邀请注册功能，不返利</option>
                                                <option value="after_recharge" {if $settings['invitation_mode'] == 'after_recharge'}selected{/if}>
                                                使用邀请注册功能，并在被邀请用户充值时返利</option>
                                                <option value="after_purchase" {if $settings['invitation_mode'] == 'after_purchase'}selected{/if}>
                                                使用邀请注册功能，并在被邀请用户购买时返利</option>
                                            </select>
                                        </div>
                                        <!-- invite_rebate_mode -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">返利模式</label>
                                            <select id="invite_rebate_mode" class="form-control maxwidth-edit">
                                                <option value="continued" {if $settings['invite_rebate_mode'] == 'continued'}selected{/if}>
                                                持续返利</option>
                                                <option value="limit_frequency" {if $settings['invite_rebate_mode'] == 'limit_frequency'}selected{/if}>
                                                限制邀请人能从被邀请人身上获得的总返利次数</option>
                                                <option value="limit_amount" {if $settings['invite_rebate_mode'] == 'limit_amount'}selected{/if}>
                                                限制邀请人能从被邀请人身上获得的总返利金额</option>
                                                <option value="limit_time_range" {if $settings['invite_rebate_mode'] == 'limit_time_range'}selected{/if}>
                                                限制邀请人能从被邀请人身上获得返利的时间范围</option>
                                            </select>
                                        </div>
                                        <p class="form-control-guide"><i class="material-icons">info</i>返利模式功能依赖 payback 表记录，请谨慎操作该表</p>
                                        <!-- rebate_ratio -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">返利比例。10 元商品反 2 元就填 0.2</label>
                                            <input class="form-control maxwidth-edit" id="rebate_ratio" value="{$settings['rebate_ratio']}">
                                        </div>
                                        <h5>返利限制模式</h5>
                                        <p class="form-control-guide"><i class="material-icons">info</i>以下设置项仅在选择对应返利限制模式时生效</p>
                                        <!-- rebate_time_range_limit -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">返利时间范围限制（单位：天）</label>
                                            <input class="form-control maxwidth-edit" id="rebate_time_range_limit" value="{$settings['rebate_time_range_limit']}">
                                        </div>
                                        <!-- rebate_frequency_limit -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">返利总次数限制</label>
                                            <input class="form-control maxwidth-edit" id="rebate_frequency_limit" value="{$settings['rebate_frequency_limit']}">
                                        </div>
                                        <p class="form-control-guide"><i class="material-icons">info</i>例如：设置为 3 时，一个被邀请用户先后购买了售价为 10，20，50，100 的商品，则只对前三笔订单返利（假设设置为在购买时返利）</p>
                                        <!-- rebate_amount_limit -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">返利总金额限制</label>
                                            <input class="form-control maxwidth-edit" id="rebate_amount_limit" value="{$settings['rebate_amount_limit']}">
                                        </div>
                                        <p class="form-control-guide"><i class="material-icons">info</i>例如：设置为 10 时，一个被邀请用户先后购买了售价为 10，20，50，100 的商品，若返点设置为 20% ，则第一次购买返利为 2；第二次为 4；第三次为 4；第四次及之后的购买，邀请人所能获得的返利均为 0（假设设置为在购买时返利）</p>
                                        <p class="form-control-guide"><i class="material-icons">info</i>在进行第三次返利计算时，按设置应返利订单金额的 20% ，即 10 元。但因已获得历史返利 6 元，则只能获得返利总金额限制与历史返利的差值</p>

                                        <br/><button id="submit_rebate_mode" type="submit" class="btn btn-block btn-brand">提交</button>
                                    </div>

                                    <div class="tab-pane fade" id="invitation_reward">
                                        <!-- invitation_to_register_balance_reward -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">若有人使用现存用户的邀请链接注册，被邀请人所能获得的余额奖励（单位：元）</label>
                                            <input class="form-control maxwidth-edit" id="invitation_to_register_balance_reward" value="{$settings['invitation_to_register_balance_reward']}">
                                        </div>
                                        <!-- invitation_to_register_traffic_reward -->
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">若有人使用现存用户的邀请链接注册，邀请人所能获得的流量奖励（单位：GB）</label>
                                            <input class="form-control maxwidth-edit" id="invitation_to_register_traffic_reward" value="{$settings['invitation_to_register_traffic_reward']}">
                                        </div>

                                        <button id="submit_invitation_reward" type="submit" class="btn btn-block btn-brand">提交</button>
                                    </div>

                                    <div class="tab-pane fade" id="withdraw">
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">开启佣金提现功能</label>
                                            <select class="form-control maxwidth-edit" id="enable_withdraw">
                                                <option value="0" {if $settings['enable_withdraw'] == false}selected{/if}>关闭</option>
                                                <option value="1" {if $settings['enable_withdraw'] == true}selected{/if}>开启</option>
                                            </select>
                                        </div>
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">提现最低金额</label>
                                            <input class="form-control maxwidth-edit" value="{$settings['withdraw_less_amount']}" id="withdraw_less_amount">
                                        </div>
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">提现方式</label>
                                            
                                            <input class="form-control maxwidth-edit" id="withdraw_method" value={$settings['withdraw_method']} />
                                            
                                        </div>
                                        <button id="submit_withdraw" type="submit" class="btn btn-block btn-brand">提交</button>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="sell_settings">
                                    <nav class="tab-nav margin-top-no">
                                        <ul class="nav nav-list">         
                                            <li class="active">
                                                <a data-toggle="tab" href="#flash_sell"><i class="icon icon-lg">sell</i>&nbsp;闪购</a>
                                            </li>
                                            <li>
                                                <a data-toggle="tab" href="#currency"><i class="icon icon-lg">monetization_on</i>&nbsp;货币</a>
                                            </li>
                                            <li>
                                                <a data-toggle="tab" href="#sales_agent"><i class="icon icon-lg">stairs</i>&nbsp;代理销售</a>
                                            </li>
                                        </ul>
                                    </nav>
                                    <div class="tab-pane fade active in" id="flash_sell">
                                        <div class="form-grop form-group-label">
                                            <label class="floating-label">开启闪购</label>
                                            <select id="enable_flash_sell" class="form-control maxwidth-edit">
                                                <option value="0" {if $settings['enable_flash_sell'] == false}selected{/if}>关闭</option>
                                                <option value="1" {if $settings['enable_flash_sell'] == true}selected{/if}>开启</option>
                                            </select>
                                        </div>
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">闪购商品ID</label>
                                            <input class="form-control maxwidth-edit" value="{$settings['flash_sell_product_id']}" id="flash_sell_product_id">
                                        </div>
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">闪购商品名称</label>
                                            <input class="form-control maxwidth-edit" id="flash_sell_product_name" value="{$settings['flash_sell_product_name']}">
                                        </div>
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">闪购开始时间</label>
                                            <input class="form-control maxwidth-edit" id="flash_sell_start_time" value="{$settings['flash_sell_start_time']}">
                                        </div>
                                        <button id="submit_flash_sell" type="submit" class="btn btn-block btn-brand">提交</button>
                                    </div>
                                    <div class="tab-pane fade" id="currency">
                                        <p class="form-control-guide"><i class="material-icons">info</i>关闭此功能, 则默认使用CNY货币</p>
                                        <div class="form-grop form-group-label">                                           
                                            <label class="floating-label">开启其他货币</label>
                                            <select id="enable_currency" class="form-control maxwidth-edit">
                                                <option value="0" {if $settings['enable_currency'] == false}selected{/if}>关闭</option>
                                                <option value="1" {if $settings['enable_currency'] == true}selected{/if}>开启</option>
                                            </select>
                                        </div>                                        
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">货币单位</label>
                                            <input class="form-control maxwidth-edit" value="{$settings['setting_currency']}" id="setting_currency">
                                        </div>
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">货币汇率</label>
                                            <input class="form-control maxwidth-edit" value="{$settings['currency_exchange_rate']}" id="currency_exchange_rate" disabled>
                                        </div>
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">汇率API KEY</label>
                                            <input class="form-control maxwidth-edit" value="{$settings['currency_exchange_rate_api_key']}" id="currency_exchange_rate_api_key">
                                        </div>
                                        <p class="form-control-guide"><i class="material-icons">info</i>API KEY申请地址: app.abstractapi.com </p>
                                        <button id="submit_setting_currency" type="submit" class="btn btn-block btn-brand">提交</button>
                                    </div>
                                    <div class="tab-pane fade" id="sales_agent">
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">开启销售代理功能</label>
                                            <select id="enable_sales_agent" class="form-control maxwidth-edit">
                                                <option value="0" {if $settings['enable_sales_agent'] == false}selected{/if}>关闭</option>
                                                <option value="1" {if $settings['enable_sales_agent'] == true}selected{/if}>开启</option>
                                            </select>
                                        </div>
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">购买销售代理的价格</label>
                                            <input class="form-control maxwidth-edit" value="{$settings['purchase_sales_agent_price']}" id="purchase_sales_agent_price">
                                        </div>
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">销售代理提成, 百分之30就填0.3</label>
                                            <input class="form-control maxwidth-edit" value="{$settings['sales_agent_commission_ratio']}" id="sales_agent_commission_ratio">
                                        </div>
                                        <button id="submit_sales_agent" type="submit" class="btn btn-block btn-brand">提交</button>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="telegram_settings">
                                    <nav class="tab-nav margin-top-no">
                                        <ul class="nav nav-list">
                                            <li class="active">
                                                <a data-toggle="tab" href="#telegram_general"><i class="icon icon-lg">settings</i>&nbsp;设置</a>
                                            </li>
                                            <li>
                                                <a data-toggle="tab" href="#telegram_bot"><i class="icon icon-lg">smart_toy</i>&nbsp;BOT</a>
                                            </li>
                                            <li>
                                                <a data-toggle="tab" href="#telegram_notify"><i class="icon icon-lg">campaign</i>&nbsp;通知</a>
                                            </li>
                                            <li>
                                                <a data-toggle="tab" href="#telegram_notify_content"><i class="icon icon-lg">note_add</i>&nbsp;通知内容</a>
                                            </li>
                                        </ul>
                                    </nav>
                                    <div class="tab-pane fade active in" id="telegram_general">
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">Telegram 群组 ID</label>
                                            <input class="form-control maxwidth-edit" value="{$settings['telegram_general_group_id']}" id="telegram_general_group_id" />
                                        </div>
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">Telegram 频道 ID</label>
                                            <input class="form-control maxwidth-edit" value="{$settings['telegram_general_channel_id']}" id="telegram_general_channel_id" />
                                        </div>
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">管理员ID</label>
                                                <input class="form-control maxwidth-edit" id="telegram_general_admin_id" {if $settings['telegram_general_admin_id'] == null} value="{$settings['telegram_general_admin_id']}" {else} value={$settings['telegram_general_admin_id']}{/if} />
                                        </div>
                                        <button id="submit_telegram_general" type="submit" class="btn btn-block btn-brand">提交</button>
                                    </div>
                                    <div class="tab-pane fade" id="telegram_bot">
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">开启Telegram Bot</label>
                                            <select id="enable_telegram_bot" class="form-control maxwidth-edit">
                                                <option value="0" {if $settings['enable_telegram_bot'] == false}selected{/if}>关闭</option>
                                                <option value="1" {if $settings['enable_telegram_bot'] == true}selected{/if}>开启</option>
                                            </select>
                                        </div>
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">开启新Telegram Bot</label>
                                            <select id="enable_new_telegram_bot" class="form-control maxwidth-edit">
                                                <option value="0" {if $settings['enable_new_telegram_bot'] == false}selected{/if}>关闭</option>
                                                <option value="1" {if $settings['enable_new_telegram_bot'] == true}selected{/if}>开启</option>
                                            </select>
                                        </div>
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">Telegram Bot 在群组中不回应</label>
                                            <select id="enable_telegram_bot_group_quiet" class="form-control maxwidth-edit">
                                                <option value="0" {if $settings['enable_telegram_bot_group_quiet'] == false}selected{/if}>关闭</option>
                                                <option value="1" {if $settings['enable_telegram_bot_group_quiet'] == true}selected{/if}>开启</option>
                                            </select>
                                        </div>
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">Telegram Bot 在菜单中显示加入用户群</label>
                                            <select id="enable_telegram_bot_menu_show_join_group" class="form-control maxwidth-edit">
                                                <option value="0" {if $settings['enable_telegram_bot_menu_show_join_group'] == false}selected{/if}>关闭</option>
                                                <option value="1" {if $settings['enable_telegram_bot_menu_show_join_group'] == true}selected{/if}>开启</option>
                                            </select>
                                        </div>
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">Telegram Bot Token</label>
                                            <input class="form-control maxwidth-edit" value="{$settings['telegram_bot_token']}" id="telegram_bot_token">
                                        </div>
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">Telegram Bot ID</label>
                                            <input class="form-control maxwidth-edit" value="{$settings['telegram_bot_id']}" id="telegram_bot_id">
                                        </div>
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">Telegram Bot Request Token</label>
                                            <input class="form-control maxwidth-edit" value="{$settings['telegram_bot_request_token']}" id="telegram_bot_request_token">
                                        </div>
                                        <button id="submit_telegram_bot" type="submit" class="btn btn-block btn-brand">提交</button>
                                    </div>
                                    <div class="tab-pane fade" id="telegram_notify">
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">Telegram Bot 推送用户充值通知</label>
                                            <select id="enable_sell_telegram_notify" class="form-control maxwidth-edit">
                                                <option value="0" {if $settings['enable_sell_telegram_notify'] == false}selected{/if}>关闭</option>
                                                <option value="1" {if $settings['enable_sell_telegram_notify'] == true}selected{/if}>开启</option>
                                            </select>
                                        </div>
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">Telegram Bot 推送用户工单通知</label>
                                            <select id="enable_ticket_telegram_notify" class="form-control maxwidth-edit">
                                                <option value="0" {if $settings['enable_ticket_telegram_notify'] == false}selected{/if}>关闭</option>
                                                <option value="1" {if $settings['enable_ticket_telegram_notify'] == true}selected{/if}>开启</option>
                                            </select>
                                        </div>
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">Telegram Bot 发送欢迎消息</label>
                                            <select id="enable_welcome_message_telegram_notify" class="form-control maxwidth-edit">
                                                <option value="0" {if $settings['enable_welcome_message_telegram_notify'] == false}selected{/if}>关闭</option>
                                                <option value="1" {if $settings['enable_welcome_message_telegram_notify'] == true}selected{/if}>开启</option>
                                            </select>
                                        </div>
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">Telegram Bot 发送财务报告</label>
                                            <select id="enable_finance_report_telegram_notify" class="form-control maxwidth-edit">
                                                <option value="0" {if $settings['enable_finance_report_telegram_notify'] == false}selected{/if}>关闭</option>
                                                <option value="1" {if $settings['enable_finance_report_telegram_notify'] == true}selected{/if}>开启</option>
                                            </select>
                                        </div>
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">Telegram Bot 推送每天系统运行状况</label>
                                            <select id="enable_system_report_telegram_notify" class="form-control maxwidth-edit">
                                                <option value="0" {if $settings['enable_system_report_telegram_notify'] == false}selected{/if}>关闭</option>
                                                <option value="1" {if $settings['enable_system_report_telegram_notify'] == true}selected{/if}>开启</option>
                                            </select>
                                        </div>
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">Telegram Bot 推送每天系统清理数据库</label>
                                            <select id="enable_system_clean_database_report_telegram_notify" class="form-control maxwidth-edit">
                                                <option value="0" {if $settings['enable_system_clean_database_report_telegram_notify'] == false}selected{/if}>关闭</option>
                                                <option value="1" {if $settings['enable_system_clean_database_report_telegram_notify'] == true}selected{/if}>开启</option>
                                            </select>
                                        </div>
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">Telegram Bot 推送节点离线通知</label>
                                            <select id="enable_system_node_offline_report_telegram_notify" class="form-control maxwidth-edit">
                                                <option value="0" {if $settings['enable_system_node_offline_report_telegram_notify'] == false}selected{/if}>关闭</option>
                                                <option value="1" {if $settings['enable_system_node_offline_report_telegram_notify'] == true}selected{/if}>开启</option>
                                            </select>
                                        </div>
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">Telegram Bot 推送节点上线通知</label>
                                            <select id="enable_system_node_online_report_telegram_notify" class="form-control maxwidth-edit">
                                                <option value="0" {if $settings['enable_system_node_online_report_telegram_notify'] == false}selected{/if}>关闭</option>
                                                <option value="1" {if $settings['enable_system_node_online_report_telegram_notify'] == true}selected{/if}>开启</option>
                                            </select>
                                        </div>
                                        <button id="submit_telegram_notify" type="submit" class="btn btn-block btn-brand">提交</button>
                                    </div>
                                    <div class="tab-pane fade" id="telegram_notify_content">
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">自定义推送系统每天的运行状况的内容</label>
                                            <textarea class="form-control maxwidth-edit" rows="5" id="diy_system_report_telegram_notify_content">{$settings['diy_system_report_telegram_notify_content']}</textarea>
                                        </div>
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">自定义推送系统清理数据库的内容</label>
                                            <textarea class="form-control maxwidth-edit" rows="5" id="diy_system_clean_database_report_telegram_notify_content">{$settings['diy_system_clean_database_report_telegram_notify_content']}</textarea>
                                        </div>
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">自定义推送系统节点离线的内容</label>
                                            <textarea class="form-control maxwidth-edit" rows="5" id="diy_system_node_offline_report_telegram_notify_content">{$settings['diy_system_node_offline_report_telegram_notify_content']}</textarea>
                                        </div>
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">自定义推送系统节点上线的内容</label>
                                            <textarea class="form-control maxwidth-edit" rows="5" id="diy_system_node_online_report_telegram_notify_content">{$settings['diy_system_node_online_report_telegram_notify_content']}</textarea>
                                        </div>
                                        <button id="submit_telegram_notify_content" type="submit" class="btn btn-block btn-brand">提交</button>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="subscribe_settings">
                                    <nav class="tab-nav margin-top-no">
                                        <ul class="nav nav-list">
                                            <li class="active">
                                                <a data-toggle="tab" href="#subscribe_general"><i class="icon icon-lg">settings</i>&nbsp;设置</a>
                                            </li>
                                        </ul>
                                    </nav>
                                    <div class="tab-pane fade active in" id="subscribe_general">
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">订阅功能</label>
                                            <select id="enable_subscribe" class="form-control maxwidth-edit">
                                                <option value="0" {if $settings['enable_subscribe'] == false}selected{/if}>关闭</option>
                                                <option value="1" {if $settings['enable_subscribe'] == true}selected{/if}> 开启</option>
                                            </select>
                                        </div>
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">订阅地址</label>
                                            <input id="subscribe_address_url" value="{$settings['subscribe_address_url']}" class="form-control maxwidth-edit">
                                        </div>
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">订阅emoji</label>
                                            <select id="enable_subscribe_emoji" class="form-control maxwidth-edit">
                                                <option value="0" {if $settings['enable_subscribe_emoji'] == false}selected{/if}>关闭</option>
                                                <option value="1" {if $settings['enable_subscribe_emoji'] == true}selected{/if}> 开启</option>
                                            </select>
                                        </div>
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">订阅显示流量和时间</label>
                                            <select id="enable_subscribe_extend" class="form-control maxwidth-edit">
                                                <option value="0" {if $settings['enable_subscribe_extend'] == false}selected{/if}>关闭</option>
                                                <option value="1" {if $settings['enable_subscribe_extend'] == true}selected{/if}> 开启</option>
                                            </select>
                                        </div>
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">更换密码强制更换订阅token</label>
                                            <select id="enable_subscribe_change_token_when_change_passwd" class="form-control maxwidth-edit">
                                                <option value="0" {if $settings['enable_subscribe_change_token_when_change_passwd'] == false}selected{/if}>关闭</option>
                                                <option value="1" {if $settings['enable_subscribe_change_token_when_change_passwd'] == true}selected{/if}> 开启</option>
                                            </select>
                                        </div>
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">订阅日志</label>
                                            <select id="enable_subscribe_log" class="form-control maxwidth-edit">
                                                <option value="0" {if $settings['enable_subscribe_log'] == false}selected{/if}>关闭</option>
                                                <option value="1" {if $settings['enable_subscribe_log'] == true}selected{/if}> 开启</option>
                                            </select>
                                        </div>
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">订阅日志保留周期</label>
                                            <input id="subscribe_log_save_days" value="{$settings['subscribe_log_save_days']}" class="form-control maxwidth-edit">
                                        </div>
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">订阅营销信息</label>
                                            <input id="subscribe_diy_message" value="{$settings['subscribe_diy_message']}" class="form-control maxwidth-edit">
                                        </div>
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">Clash 订阅默认配置</label>
                                            <input id="subscribe_clash_default_profile" value="{$settings['subscribe_clash_default_profile']}" class="form-control maxwidth-edit">
                                        </div>
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">Surge 订阅默认配置</label>
                                            <input id="subscribe_surge_default_profile" value="{$settings['subscribe_surge_default_profile']}" class="form-control maxwidth-edit">
                                        </div>
                                        <div class="form-group form-group-label">
                                            <label class="floating-label">Surfboard 订阅默认配置</label>
                                            <input id="subscribe_surfboard_default_profile" value="{$settings['subscribe_surfboard_default_profile']}" class="form-control maxwidth-edit">
                                        </div>
                                        <button id="submit_subscribe_general" type="submit" class="btn btn-block btn-brand">提交</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {include file='dialog.tpl'}
    </div>
</main>

{include file='admin/footer.tpl'}

<!--网站通用设置 -->
<script>
    window.addEventListener('load', () => {
        $$.getElementById('submit_website_general').addEventListener('click', () => {
            $.ajax({
                type: "POST",
                url: "/admin/setting",
                dataType: "json",
                data: {
                    class: 'website_general',
                    website_general_url: $$getValue('website_general_url'),
                    website_general_name: $$getValue('website_general_name'),
                    website_general_landing_index: $$getValue('website_general_landing_index')
                },
                success: data => {
                    $("#result").modal();
                    $$.getElementById('msg').innerHTML = data.msg;
                    if (data.ret) {
                        window.setTimeout("location.href='/admin/setting'", {$config['jump_delay']});
                    }
                },
                error: jqXHR => {
                    alert(`发生错误：${
                            jqXHR.status
                            }`);
                }
            })
        })
    })
</script>

<!-- 网站安全设置 -->
<script>
    window.addEventListener('load', () => {
        $$.getElementById('submit_website_security').addEventListener('click', () => {
            $.ajax({
                type: "POST",
                url: "/admin/setting",
                dataType: "json",
                data: {
                    class: 'website_security',
                    website_security_token: $$getValue('website_security_token')
                },
                success: data => {
                    $("#result").modal();
                    $$.getElementById('msg').innerHTML = data.msg;
                    if (data.ret) {
                        window.setTimeout("location.href='/admin/setting'", {$config['jump_delay']});
                    }
                },
                error: jqXHR => {
                    alert(`发生错误：${
                            jqXHR.status
                            }`);
                }
            })
        })
    })
</script>

<!-- 网站后端设置 -->
<script>
    window.addEventListener('load', () => {
        $$.getElementById('submit_website_backend').addEventListener('click', () => {
            $.ajax({
                type: "POST",
                url: "/admin/setting",
                dataType: "json",
                data: {
                    class: 'website_backend',
                    website_backend_token: $$getValue('website_backend_token')
                },
                success: data => {
                    $("#result").modal();
                    $$.getElementById('msg').innerHTML = data.msg;
                    if (data.ret) {
                        window.setTimeout("location.href='/admin/setting'", {$config['jump_delay']});
                    }
                },
                error: jqXHR => {
                    alert(`发生错误：${
                            jqXHR.status
                            }`);
                }
            })
        })
    })
</script>

<!-- 用户设置 -->
<script>
    window.addEventListener('load', () => {
        $$.getElementById('submit_user_general').addEventListener('click', () => {
            $.ajax({
                type: "POST",
                url: "/admin/setting",
                dataType: "json",
                data: {
                    class: 'user_general',
                    user_general_free_user_reset_day: $$getValue('user_general_free_user_reset_day'),
                    user_general_free_user_reset_traffic: $$getValue('user_general_free_user_reset_traffic'),
                    user_general_class_expire_reset_traffic: $$getValue('user_general_class_expire_reset_traffic'),
                    enable_reset_traffic_when_purchase_user_general: $$getValue('enable_reset_traffic_when_purchase_user_general'),
                    enable_add_times_when_purchase_user_general: $$getValue('enable_add_times_when_purchase_user_general'),
                    enable_change_username_user_general: $$getValue('enable_change_username_user_general'),
                    enable_change_email_user_general: $$getValue('enable_change_email_user_general'),
                    enable_delete_account_user_general: $$getValue('enable_delete_account_user_general')
                },
                success: data => {
                    $("#result").modal();
                    $$.getElementById('msg').innerHTML = data.msg;
                    if (data.ret) {
                        window.setTimeout("location.href='/admin/setting'", {$config['jump_delay']});
                    }
                },
                error: jqXHR => {
                    alert(`发生错误：${
                            jqXHR.status
                            }`);
                }
            })
        })
    })
</script>

<!-- 签到设置 -->
<script>
    window.addEventListener('load', () => {
        $$.getElementById('submit_user_checkin').addEventListener('click', () => {
            $.ajax({
                type: "POST",
                url: "/admin/setting",
                dataType: "json",
                data: {
                    class: 'user_checkin',
                    enable_user_checkin: $$getValue('enable_user_checkin'),
                    user_checkin_get_traffic_value: $$getValue('user_checkin_get_traffic_value')
                },
                success: data => {
                    $("#result").modal();
                    $$.getElementById('msg').innerHTML = data.msg;
                    if (data.ret) {
                        window.setTimeout("location.href='/admin/setting'", {$config['jump_delay']});
                    }
                },
                error: jqXHR => {
                    alert(`发生错误：${
                            jqXHR.status
                            }`);
                }
            })
        })
    })
</script>

<!--用户通知设置 -->
<script>
    window.addEventListener('load', () => {
        $$.getElementById('submit_user_notify').addEventListener('click', () => {
            $.ajax({
                type: "POST",
                url: "/admin/setting",
                dataType: "json",
                data: {
                    class: 'user_notify',
                    enable_insufficient_traffic_user_notify: $$getValue('enable_insufficient_traffic_user_notify')
                },
                success: data => {
                    $("#result").modal();
                    $$.getElementById('msg').innerHTML = data.msg;
                    if (data.ret) {
                        window.setTimeout("location.href='/admin/setting'", {$config['jump_delay']});
                    }
                },
                error: jqXHR => {
                    alert(`发生错误：${
                            jqXHR.status
                            }`);
                }
            })
        })
    })
</script>
<script>
    window.addEventListener('load', () => {
        $$.getElementById('submit_f2f_pay').addEventListener('click', () => {
            $.ajax({
                type: "POST",
                url: "/admin/setting",
                dataType: "json",
                data: {
                    class: 'f2f_pay',
                    f2f_pay_app_id: $$getValue('f2f_pay_app_id'),
                    f2f_pay_pid: $$getValue('f2f_pay_pid'),
                    f2f_pay_public_key: $$getValue('f2f_pay_public_key'),
                    f2f_pay_private_key: $$getValue('f2f_pay_private_key'),
                    f2f_pay_notify_url: $$getValue('f2f_pay_notify_url')
                },
                success: data => {
                    $("#result").modal();
                    $$.getElementById('msg').innerHTML = data.msg;
                    if (data.ret) {
                        window.setTimeout("location.href='/admin/setting'", {$config['jump_delay']});
                    }
                },
                error: jqXHR => {
                    alert(`发生错误：${
                            jqXHR.status
                            }`);
                }
            })
        })
    })
</script>

<script>
    window.addEventListener('load', () => {
        $$.getElementById('submit_payment_gateway').addEventListener('click', () => {
            
            $.ajax({
                type: "POST",
                url: "/admin/setting",
                dataType: "json",
                data: {
                    alipay_payment: $$getValue('alipay_payment'),
                    wechatpay_payment: $$getValue('wechatpay_payment'),
                    cryptopay_payment: $$getValue('cryptopay_payment'),
                    class: 'payment_gateway'
                },
                success: data => {
                    $("#result").modal();
                    $$.getElementById('msg').innerHTML = data.msg;
                    if (data.ret) {
                        window.setTimeout("location.href='/admin/setting'", {$config['jump_delay']});
                    }
                },
                error: jqXHR => {
                    alert(`发生错误：${
                            jqXHR.status
                            }`);
                }
            })
        })
    })
</script>

<script>
    window.addEventListener('load', () => {
        $$.getElementById('submit_vmq_pay').addEventListener('click', () => {
            $.ajax({
                type: "POST",
                url: "/admin/setting",
                dataType: "json",
                data: {
                    class: 'vmq_pay',
                    vmq_gateway: $$getValue('vmq_gateway'),
                    vmq_key: $$getValue('vmq_key')
                },
                success: data => {
                    $("#result").modal();
                    $$.getElementById('msg').innerHTML = data.msg;
                    if (data.ret) {
                        window.setTimeout("location.href='/admin/setting'", {$config['jump_delay']});
                    }
                },
                error: jqXHR => {
                    alert(`发生错误：${
                            jqXHR.status
                            }`);
                }
            })
        })
    })
</script>

<script>
    window.addEventListener('load', () => {
        $$.getElementById('submit_mail').addEventListener('click', () => {
            $.ajax({
                type: "POST",
                url: "/admin/setting",
                dataType: "json",
                data: {
                    class: 'mail',
                    mail_driver: $$getValue('mail_driver')
                },
                success: data => {
                    $("#result").modal();
                    $$.getElementById('msg').innerHTML = data.msg;
                    if (data.ret) {
                        window.setTimeout("location.href='/admin/setting'", {$config['jump_delay']});
                    }
                },
                error: jqXHR => {
                    alert(`发生错误：${
                            jqXHR.status
                            }`);
                }
            })
        })
    })
</script>

<script>
    window.addEventListener('load', () => {
        $$.getElementById('submit_smtp').addEventListener('click', () => {
            $.ajax({
                type: "POST",
                url: "/admin/setting",
                dataType: "json",
                data: {
                    class: 'smtp',
                    smtp_host: $$getValue('smtp_host'),
                    smtp_username: $$getValue('smtp_username'),
                    smtp_password: $$getValue('smtp_password'),
                    smtp_port: $$getValue('smtp_port'),
                    smtp_name: $$getValue('smtp_name'),
                    smtp_sender: $$getValue('smtp_sender'),
                    smtp_ssl: $$getValue('smtp_ssl'),
                    smtp_bbc: $$getValue('smtp_bbc')
                },
                success: data => {
                    $("#result").modal();
                    $$.getElementById('msg').innerHTML = data.msg;
                    if (data.ret) {
                        window.setTimeout("location.href='/admin/setting'", {$config['jump_delay']});
                    }
                },
                error: jqXHR => {
                    alert(`发生错误：${
                            jqXHR.status
                            }`);
                }
            })
        })
    })
</script>

<script>
    window.addEventListener('load', () => {
        $$.getElementById('submit_email_test').addEventListener('click', () => {
            $.ajax({
                type: "POST",
                url: "/admin/setting/email",
                dataType: "json",
                data: {
                    recipient: $$getValue('testing_email_recipients')
                },
                success: data => {
                    $("#result").modal();
                    $$.getElementById('msg').innerHTML = data.msg;
                    if (data.ret) {
                        window.setTimeout("location.href='/admin/setting'", {$config['jump_delay']});
                    }
                },
                error: jqXHR => {
                    alert(`发生错误：${
                            jqXHR.status
                            }`);
                }
            })
        })
    })
</script>

<script>
    window.addEventListener('load', () => {
        $$.getElementById('submit_verify_code').addEventListener('click', () => {
            $.ajax({
                type: "POST",
                url: "/admin/setting",
                dataType: "json",
                data: {
                    class: 'verify_code',
                    captcha_provider: $$getValue('captcha_provider'),
                    enable_reg_captcha: $$getValue('enable_reg_captcha'),
                    enable_login_captcha: $$getValue('enable_login_captcha'),
                    enable_checkin_captcha: $$getValue('enable_checkin_captcha')
                },
                success: data => {
                    $("#result").modal();
                    $$.getElementById('msg').innerHTML = data.msg;
                    if (data.ret) {
                        window.setTimeout("location.href='/admin/setting'", {$config['jump_delay']});
                    }
                },
                error: jqXHR => {
                    alert(`发生错误：${
                            jqXHR.status
                            }`);
                }
            })
        })
    })
</script>

<script>
    window.addEventListener('load', () => {
        $$.getElementById('submit_geetest').addEventListener('click', () => {
            $.ajax({
                type: "POST",
                url: "/admin/setting",
                dataType: "json",
                data: {
                    class: 'verify_code_geetest',
                    geetest_id: $$getValue('geetest_id'),
                    geetest_key: $$getValue('geetest_key')
                },
                success: data => {
                    $("#result").modal();
                    $$.getElementById('msg').innerHTML = data.msg;
                    if (data.ret) {
                        window.setTimeout("location.href='/admin/setting'", {$config['jump_delay']});
                    }
                },
                error: jqXHR => {
                    alert(`发生错误：${
                            jqXHR.status
                            }`);
                }
            })
        })
    })
</script>

<script>
    window.addEventListener('load', () => {
        $$.getElementById('submit_recaptcha').addEventListener('click', () => {
            $.ajax({
                type: "POST",
                url: "/admin/setting",
                dataType: "json",
                data: {
                    class: 'verify_code_recaptcha',
                    recaptcha_sitekey: $$getValue('recaptcha_sitekey'),
                    recaptcha_secret: $$getValue('recaptcha_secret')
                },
                success: data => {
                    $("#result").modal();
                    $$.getElementById('msg').innerHTML = data.msg;
                    if (data.ret) {
                        window.setTimeout("location.href='/admin/setting'", {$config['jump_delay']});
                    }
                },
                error: jqXHR => {
                    alert(`发生错误：${
                            jqXHR.status
                            }`);
                }
            })
        })
    })
</script>

<script>
    window.addEventListener('load', () => {
        $$.getElementById('submit_mailgun').addEventListener('click', () => {
            $.ajax({
                type: "POST",
                url: "/admin/setting",
                dataType: "json",
                data: {
                    class: 'mailgun',
                    mailgun_key: $$getValue('mailgun_key'),
                    mailgun_domain: $$getValue('mailgun_domain'),
                    mailgun_sender: $$getValue('mailgun_sender')
                },
                success: data => {
                    $("#result").modal();
                    $$.getElementById('msg').innerHTML = data.msg;
                    if (data.ret) {
                        window.setTimeout("location.href='/admin/setting'", {$config['jump_delay']});
                    }
                },
                error: jqXHR => {
                    alert(`发生错误：${
                            jqXHR.status
                            }`);
                }
            })
        })
    })
</script>

<script>
    window.addEventListener('load', () => {
        $$.getElementById('submit_sendgrid').addEventListener('click', () => {
            $.ajax({
                type: "POST",
                url: "/admin/setting",
                dataType: "json",
                data: {
                    class: 'sendgrid',
                    sendgrid_key: $$getValue('sendgrid_key'),
                    sendgrid_sender: $$getValue('sendgrid_sender'),
                    sendgrid_name: $$getValue('sendgrid_name')
                },
                success: data => {
                    $("#result").modal();
                    $$.getElementById('msg').innerHTML = data.msg;
                    if (data.ret) {
                        window.setTimeout("location.href='/admin/setting'", {$config['jump_delay']});
                    }
                },
                error: jqXHR => {
                    alert(`发生错误：${
                            jqXHR.status
                            }`);
                }
            })
        })
    })
</script>

<script>
    window.addEventListener('load', () => {
        $$.getElementById('submit_ses').addEventListener('click', () => {
            $.ajax({
                type: "POST",
                url: "/admin/setting",
                dataType: "json",
                data: {
                    class: 'ses',
                    aws_access_key_id: $$getValue('aws_access_key_id'),
                    aws_secret_access_key: $$getValue('aws_secret_access_key')
                },
                success: data => {
                    $("#result").modal();
                    $$.getElementById('msg').innerHTML = data.msg;
                    if (data.ret) {
                        window.setTimeout("location.href='/admin/setting'", {$config['jump_delay']});
                    }
                },
                error: jqXHR => {
                    alert(`发生错误：${
                            jqXHR.status
                            }`);
                }
            })
        })
    })
</script>

<script>
    window.addEventListener('load', () => {
        $$.getElementById('submit_email_backup').addEventListener('click', () => {
            $.ajax({
                type: "POST",
                url: "/admin/setting",
                dataType: "json",
                data: {
                    class: 'email_backup',
                    auto_backup_email: $$getValue('auto_backup_email'),
                    auto_backup_password: $$getValue('auto_backup_password'),
                    auto_backup_notify: $$getValue('auto_backup_notify')
                },
                success: data => {
                    $("#result").modal();
                    $$.getElementById('msg').innerHTML = data.msg;
                    if (data.ret) {
                        window.setTimeout("location.href='/admin/setting'", {$config['jump_delay']});
                    }
                },
                error: jqXHR => {
                    alert(`发生错误：${
                            jqXHR.status
                            }`);
                }
            })
        })
    })
</script>

<script>
    window.addEventListener('load', () => {
        $$.getElementById('submit_payjs_pay').addEventListener('click', () => {
            $.ajax({
                type: "POST",
                url: "/admin/setting",
                dataType: "json",
                data: {
                    class: 'payjs_pay',
                    payjs_mchid: $$getValue('payjs_mchid'),
                    payjs_key: $$getValue('payjs_key')
                },
                success: data => {
                    $("#result").modal();
                    $$.getElementById('msg').innerHTML = data.msg;
                    if (data.ret) {
                        window.setTimeout("location.href='/admin/setting'", {$config['jump_delay']});
                    }
                },
                error: jqXHR => {
                    alert(`发生错误：${
                            jqXHR.status
                            }`);
                }
            })
        })
    })
</script>

<!-- tronapi pay -->
<script>
    window.addEventListener('load', () => {
        $$.getElementById('submit_tronapipay').addEventListener('click', () => {
            $.ajax({
                type: "POST",
                url: "/admin/setting",
                dataType: "json",
                data: {
                    class: 'tronapipay',
                    tronapipay_public_key: $$getValue('tronapipay_public_key'),
                    tronapipay_private_key: $$getValue('tronapipay_private_key')
                },
                success: data => {
                    $("#result").modal();
                    $$.getElementById('msg').innerHTML = data.msg;
                    if (data.ret) {
                        window.setTimeout("location.href='/admin/setting'", {$config['jump_delay']});
                    }
                },
                error: jqXHR => {
                    alert(`发生错误：${
                            jqXHR.status
                            }`);
                }
            })
        })
    })
</script>

<script>
    window.addEventListener('load', () => {
        $$.getElementById('submit_paymentwall').addEventListener('click', () => {
            $.ajax({
                type: "POST",
                url: "/admin/setting",
                dataType: "json",
                data: {
                    class: 'paymentwall',
                    pmw_publickey: $$getValue('pmw_publickey'),
                    pmw_privatekey: $$getValue('pmw_privatekey'),
                    pmw_widget: $$getValue('pmw_widget'),
                    pmw_height: $$getValue('pmw_height')
                },
                success: data => {
                    $("#result").modal();
                    $$.getElementById('msg').innerHTML = data.msg;
                    if (data.ret) {
                        window.setTimeout("location.href='/admin/setting'", {$config['jump_delay']});
                    }
                },
                error: jqXHR => {
                    alert(`发生错误：${
                            jqXHR.status
                            }`);
                }
            })
        })
    })
</script>

<script>
    window.addEventListener('load', () => {
        $$.getElementById('submit_admin_contact').addEventListener('click', () => {
            $.ajax({
                type: "POST",
                url: "/admin/setting",
                dataType: "json",
                data: {
                    class: 'admin_contact',
                    enable_admin_contact: $$getValue('enable_admin_contact'),
                    admin_contact1: $$getValue('admin_contact1'),
                    admin_contact2: $$getValue('admin_contact2'),
                    admin_contact3: $$getValue('admin_contact3')
                },
                success: data => {
                    $("#result").modal();
                    $$.getElementById('msg').innerHTML = data.msg;
                    if (data.ret) {
                        window.setTimeout("location.href='/admin/setting'", {$config['jump_delay']});
                    }
                },
                error: jqXHR => {
                    alert(`发生错误：${
                            jqXHR.status
                            }`);
                }
            })
        })
    })
</script>

<script>
    window.addEventListener('load', () => {
        $$.getElementById('submit_web_customer_service_system').addEventListener('click', () => {
            $.ajax({
                type: "POST",
                url: "/admin/setting",
                dataType: "json",
                data: {
                    class: 'web_customer_service_system',
                    live_chat: $$getValue('live_chat'),
                    tawk_id: $$getValue('tawk_id'),
                    crisp_id: $$getValue('crisp_id'),
                    livechat_id: $$getValue('livechat_id'),
                    mylivechat_id: $$getValue('mylivechat_id')
                },
                success: data => {
                    $("#result").modal();
                    $$.getElementById('msg').innerHTML = data.msg;
                    if (data.ret) {
                        window.setTimeout("location.href='/admin/setting'", {$config['jump_delay']});
                    }
                },
                error: jqXHR => {
                    alert(`发生错误：${
                            jqXHR.status
                            }`);
                }
            })
        })
    })
</script>

<script>
    window.addEventListener('load', () => {
        $$.getElementById('submit_theadpay').addEventListener('click', () => {
            $.ajax({
                type: "POST",
                url: "/admin/setting",
                dataType: "json",
                data: {
                    class: 'theadpay',
                    theadpay_url: $$getValue('theadpay_url'),
                    theadpay_mchid: $$getValue('theadpay_mchid'),
                    theadpay_key: $$getValue('theadpay_key')
                },
                success: data => {
                    $("#result").modal();
                    $$.getElementById('msg').innerHTML = data.msg;
                    if (data.ret) {
                        window.setTimeout("location.href='/admin/setting'", {$config['jump_delay']});
                    }
                },
                error: jqXHR => {
                    alert(`发生错误：${
                            jqXHR.status
                            }`);
                }
            })
        })
    })
</script>

<script>
    window.addEventListener('load', () => {
        $$.getElementById('submit_stripe').addEventListener('click', () => {
            $.ajax({
                type: "POST",
                url: "/admin/setting",
                dataType: "json",
                data: {
                    class: 'stripe',
                    stripe_card: $$getValue('stripe_card'),
                    stripe_currency: $$getValue('stripe_currency'),
                    stripe_min_recharge: $$getValue('stripe_min_recharge'),
                    stripe_max_recharge: $$getValue('stripe_max_recharge'),
                    stripe_pk: $$getValue('stripe_pk'),
                    stripe_sk: $$getValue('stripe_sk'),
                    stripe_webhook_key: $$getValue('stripe_webhook_key')
                },
                success: data => {
                    $("#result").modal();
                    $$.getElementById('msg').innerHTML = data.msg;
                    if (data.ret) {
                        window.setTimeout("location.href='/admin/setting'", {$config['jump_delay']});
                    }
                },
                error: jqXHR => {
                    alert(`发生错误：${
                            jqXHR.status
                            }`);
                }
            })
        })
    })
</script>

<script>
    window.addEventListener('load', () => {
        $$.getElementById('submit_paytaro').addEventListener('click', () => {
            $.ajax({
                type: "POST",
                url: "/admin/setting",
                dataType: "json",
                data: {
                    class: 'paytaro',
                    paytaro_app_id: $$getValue('paytaro_app_id'),
                    paytaro_app_secret: $$getValue('paytaro_app_secret')
                },
                success: data => {
                    $("#result").modal();
                    $$.getElementById('msg').innerHTML = data.msg;
                    if (data.ret) {
                        window.setTimeout("location.href='/admin/setting'", {$config['jump_delay']});
                    }
                },
                error: jqXHR => {
                    alert(`发生错误：${
                            jqXHR.status
                            }`);
                }
            })
        })
    })
</script>

<script>
    window.addEventListener('load', () => {
        $$.getElementById('submit_paybeaver').addEventListener('click', () => {
            $.ajax({
                type: "POST",
                url: "/admin/setting",
                dataType: "json",
                data: {
                    class: 'paybeaver',
                    paybeaver_app_id: $$getValue('paybeaver_app_id'),
                    paybeaver_app_secret: $$getValue('paybeaver_app_secret')
                },
                success: data => {
                    $("#result").modal();
                    $$.getElementById('msg').innerHTML = data.msg;
                    if (data.ret) {
                        window.setTimeout("location.href='/admin/setting'", {$config['jump_delay']});
                    }
                },
                error: jqXHR => {
                    alert(`发生错误：${
                            jqXHR.status
                            }`);
                }
            })
        })
    })
</script>

<script>
    window.addEventListener('load', () => {
        $$.getElementById('submit_custom_background_image').addEventListener('click', () => {
            $.ajax({
                type: "POST",
                url: "/admin/setting",
                dataType: "json",
                data: {
                    class: 'background_image',
                    user_center_bg: $$getValue('user_center_bg'),
                    admin_center_bg: $$getValue('admin_center_bg'),
                    user_center_bg_addr: $$getValue('user_center_bg_addr'),
                    admin_center_bg_addr: $$getValue('admin_center_bg_addr')
                },
                success: data => {
                    $("#result").modal();
                    $$.getElementById('msg').innerHTML = data.msg;
                    if (data.ret) {
                        window.setTimeout("location.href='/admin/setting'", {$config['jump_delay']});
                    }
                },
                error: jqXHR => {
                    alert(`发生错误：${
                            jqXHR.status
                            }`);
                }
            })
        })
    })
</script>

<script>
    window.addEventListener('load', () => {
        $$.getElementById('submit_reg_mode_and_verify').addEventListener('click', () => {
            $.ajax({
                type: "POST",
                url: "/admin/setting",
                dataType: "json",
                data: {
                    class: 'register',
                    reg_mode: $$getValue('reg_mode'),
                    reg_email_verify: $$getValue('reg_email_verify'),
                    email_verify_ttl: $$getValue('email_verify_ttl'),
                    email_verify_ip_limit: $$getValue('email_verify_ip_limit')
                },
                success: data => {
                    $("#result").modal();
                    $$.getElementById('msg').innerHTML = data.msg;
                    if (data.ret) {
                        window.setTimeout("location.href='/admin/setting'", {$config['jump_delay']});
                    }
                },
                error: jqXHR => {
                    alert(`发生错误：${
                            jqXHR.status
                            }`);
                }
            })
        })
    })
</script>

<script>
    window.addEventListener('load', () => {
        $$.getElementById('submit_register_default_value').addEventListener('click', () => {
            $.ajax({
                type: "POST",
                url: "/admin/setting",
                dataType: "json",
                data: {
                    class: 'register_default_value',
                    sign_up_for_free_traffic: $$getValue('sign_up_for_free_traffic'),
                    sign_up_for_free_time: $$getValue('sign_up_for_free_time'),
                    sign_up_for_class: $$getValue('sign_up_for_class'),
                    sign_up_for_class_time: $$getValue('sign_up_for_class_time'),
                    sign_up_for_invitation_codes: $$getValue('sign_up_for_invitation_codes'),
                    connection_device_limit: $$getValue('connection_device_limit'),
                    connection_rate_limit: $$getValue('connection_rate_limit'),
                    sign_up_for_method: $$getValue('sign_up_for_method'),
                    sign_up_for_protocol: $$getValue('sign_up_for_protocol'),
                    sign_up_for_protocol_param: $$getValue('sign_up_for_protocol_param'),
                    sign_up_for_obfs: $$getValue('sign_up_for_obfs'),
                    sign_up_for_obfs_param: $$getValue('sign_up_for_obfs_param'),
                    sign_up_for_daily_report: $$getValue('sign_up_for_daily_report')
                },
                success: data => {
                    $("#result").modal();
                    $$.getElementById('msg').innerHTML = data.msg;
                    if (data.ret) {
                        window.setTimeout("location.href='/admin/setting'", {$config['jump_delay']});
                    }
                },
                error: jqXHR => {
                    alert(`发生错误：${
                            jqXHR.status
                            }`);
                }
            })
        })
    })
</script>

<script>
    window.addEventListener('load', () => {
        $$.getElementById('submit_invitation_reward').addEventListener('click', () => {
            $.ajax({
                type: "POST",
                url: "/admin/setting",
                dataType: "json",
                data: {
                    class: 'invitation_reward',
                    invitation_to_register_balance_reward: $$getValue('invitation_to_register_balance_reward'),
                    invitation_to_register_traffic_reward: $$getValue('invitation_to_register_traffic_reward')
                },
                success: data => {
                    $("#result").modal();
                    $$.getElementById('msg').innerHTML = data.msg;
                    if (data.ret) {
                        window.setTimeout("location.href='/admin/setting'", {$config['jump_delay']});
                    }
                },
                error: jqXHR => {
                    alert(`发生错误：${
                            jqXHR.status
                            }`);
                }
            })
        })
    })
</script>

<script>
    window.addEventListener('load', () => {
        $$.getElementById('submit_rebate_mode').addEventListener('click', () => {
            $.ajax({
                type: "POST",
                url: "/admin/setting",
                dataType: "json",
                data: {
                    class: 'rebate_mode',
                    invitation_mode: $$getValue('invitation_mode'),
                    invite_rebate_mode: $$getValue('invite_rebate_mode'),
                    rebate_ratio: $$getValue('rebate_ratio'),
                    rebate_frequency_limit: $$getValue('rebate_frequency_limit'),
                    rebate_amount_limit: $$getValue('rebate_amount_limit'),
                    rebate_time_range_limit: $$getValue('rebate_time_range_limit')
                },
                success: data => {
                    $("#result").modal();
                    $$.getElementById('msg').innerHTML = data.msg;
                    if (data.ret) {
                        window.setTimeout("location.href='/admin/setting'", {$config['jump_delay']});
                    }
                },
                error: jqXHR => {
                    alert(`发生错误：${
                            jqXHR.status
                            }`);
                }
            })
        })
    })
</script> 

<script>
    window.addEventListener('load', () => {
        $$.getElementById('submit_flash_sell').addEventListener('click', () => {
            $.ajax({
                type: "POST",
                url: "/admin/setting",
                dataType: "json",
                data: {
                    class: 'flash_sell',
                    enable_flash_sell: $$getValue('enable_flash_sell'),
                    flash_sell_product_id: $$getValue('flash_sell_product_id'),
                    flash_sell_product_name: $$getValue('flash_sell_product_name'),
                    flash_sell_start_time: $$getValue('flash_sell_start_time'),
                },
                success: data => {
                    $("#result").modal();
                    $$.getElementById('msg').innerHTML = data.msg;
                    if (data.ret) {
                        window.setTimeout("location.href='/admin/setting'", {$config['jump_delay']});
                    }
                },
                error: jqXHR => {
                    alert(`发生错误：${
                            jqXHR.status
                            }`);
                }
            })
        })
    })
</script> 

<script>
    window.addEventListener('load', () => {
        $$.getElementById('submit_setting_currency').addEventListener('click', () => {
            $.ajax({
                type: "POST",
                url: "/admin/setting",
                dataType: "json",
                data: {
                    class: 'currency',
                    enable_currency: $$getValue('enable_currency'),
                    setting_currency: $$getValue('setting_currency'),
                    currency_exchange_rate: $$getValue('currency_exchange_rate'),
                    currency_exchange_rate_api_key: $$getValue('currency_exchange_rate_api_key')
                },
                success: data => {
                    $("#result").modal();
                    $$.getElementById('msg').innerHTML = data.msg;
                    if (data.ret) {
                        window.setTimeout("location.href='/admin/setting'", {$config['jump_delay']});
                    }
                },
                error: jqXHR => {
                    alert(`发生错误：${
                            jqXHR.status
                            }`);
                }
            })
        })
    })
</script> 

<script>
    window.addEventListener('load', () => {
        $$.getElementById('submit_withdraw').addEventListener('click', () => {
            $.ajax({
                type: "POST",
                url: "/admin/setting",
                dataType: "json",
                data: {
                    class: 'withdraw',
                    enable_withdraw: $$getValue('enable_withdraw'),
                    withdraw_less_amount: $$getValue('withdraw_less_amount'),
                    withdraw_method: $$getValue('withdraw_method')
                },
                success: data => {
                    $("#result").modal();
                    $$.getElementById('msg').innerHTML = data.msg;
                    if (data.ret) {
                        window.setTimeout("location.href='/admin/setting'", {$config['jump_delay']});
                    }
                },
                error: jqXHR => {
                    alert(`发生错误：${
                            jqXHR.status
                            }`);
                }
            })
        })
    })
</script>

<script>
    window.addEventListener('load', () => {
        $$.getElementById('submit_sales_agent').addEventListener('click', () => {
            $.ajax({
                type: "POST",
                url: "/admin/setting",
                dataType: "json",
                data: {
                    class: 'sales_agent',
                    enable_sales_agent: $$getValue('enable_sales_agent'),
                    purchase_sales_agent_price: $$getValue('purchase_sales_agent_price'),
                    sales_agent_commission_ratio: $$getValue('sales_agent_commission_ratio')
                },
                success: data => {
                    $("#result").modal();
                    $$.getElementById('msg').innerHTML = data.msg;
                    if (data.ret) {
                        window.setTimeout("location.href='/admin/setting'", {$config['jump_delay']});
                    }
                },
                error: jqXHR => {
                    alert(`发生错误：${
                            jqXHR.status
                            }`);
                }
            })
        })
    })
</script>

<script>
    window.addEventListener('load', () => {
        $$.getElementById('submit_telegram_general').addEventListener('click', () => {
            $.ajax({
                type: "POST",
                url: "/admin/setting",
                dataType: "json",
                data: {
                    class: 'telegram_general',
                    telegram_general_admin_id: $$getValue('telegram_general_admin_id'),
                    telegram_general_group_id: $$getValue('telegram_general_group_id'),
                    telegram_general_channel_id: $$getValue('telegram_general_channel_id')
                },
                success: data => {
                    $("#result").modal();
                    $$.getElementById('msg').innerHTML = data.msg;
                    if (data.ret) {
                        window.setTimeout("location.href='/admin/setting'", {$config['jump_delay']});
                    }
                },
                error: jqXHR => {
                    alert(`发生错误：${
                            jqXHR.status
                            }`);
                }
            })
        })
    })
</script>
<!-- telegram_bot js -->
<script>
    window.addEventListener('load', () => {
        $$.getElementById('submit_telegram_bot').addEventListener('click', () => {
            $.ajax({
                type: "POST",
                url: "/admin/setting",
                dataType: "json",
                data: {
                    class: 'telegram_bot',
                    enable_telegram_bot: $$getValue('enable_telegram_bot'),
                    enable_new_telegram_bot: $$getValue('enable_new_telegram_bot'),
                    enable_telegram_bot_group_quiet: $$getValue('enable_telegram_bot_group_quiet'),
                    enable_telegram_bot_menu_show_join_group: $$getValue('enable_telegram_bot_menu_show_join_group'),
                    telegram_bot_token: $$getValue('telegram_bot_token'),
                    telegram_bot_id: $$getValue('telegram_bot_id'),
                    telegram_bot_request_token: $$getValue('telegram_bot_request_token'),
                },
                success: data => {
                    $("#result").modal();
                    $$.getElementById('msg').innerHTML = data.msg;
                    if (data.ret) {
                        window.setTimeout("location.href='/admin/setting'", {$config['jump_delay']});
                    }
                },
                error: jqXHR => {
                    alert(`发生错误：${
                            jqXHR.status
                            }`);
                }
            })
        })
    })
</script>
<!-- telegram_notify js -->
<script>
    window.addEventListener('load', () => {
        $$.getElementById('submit_telegram_notify').addEventListener('click', () => {
            $.ajax({
                type: "POST",
                url: "/admin/setting",
                dataType: "json",
                data: {
                    class: 'telegram_notify',
                    enable_sell_telegram_notify: $$getValue('enable_sell_telegram_notify'),
                    enable_ticket_telegram_notify: $$getValue('enable_ticket_telegram_notify'),
                    enable_welcome_message_telegram_notify: $$getValue('enable_welcome_message_telegram_notify'),
                    enable_finance_report_telegram_notify: $$getValue('enable_finance_report_telegram_notify'),
                    enable_system_report_telegram_notify: $$getValue('enable_system_report_telegram_notify'),
                    enable_system_clean_database_report_telegram_notify: $$getValue('enable_system_clean_database_report_telegram_notify'),
                    enable_system_node_offline_report_telegram_notify: $$getValue('enable_system_node_offline_report_telegram_notify'),
                    enable_system_node_online_report_telegram_notify: $$getValue('enable_system_node_online_report_telegram_notify')
                },
                success: data => {
                    $("#result").modal();
                    $$.getElementById('msg').innerHTML = data.msg;
                    if (data.ret) {
                        window.setTimeout("location.href='/admin/setting'", {$config['jump_delay']});
                    }
                },
                error: jqXHR => {
                    alert(`发生错误：${
                            jqXHR.status
                            }`);
                }
            })
        })
    })
</script>
<!-- telegram_notify_content js -->
<script>
    window.addEventListener('load', () => {
        $$.getElementById('submit_telegram_notify_content').addEventListener('click', () => {
            $.ajax({
                type: "POST",
                url: "/admin/setting",
                dataType: "json",
                data: {
                    class: 'telegram_notify_content',
                    diy_system_report_telegram_notify_content: $$getValue('diy_system_report_telegram_notify_content'),
                    diy_system_clean_database_report_telegram_notify_content: $$getValue('diy_system_clean_database_report_telegram_notify_content'),
                    diy_system_node_offline_report_telegram_notify_content: $$getValue('diy_system_node_offline_report_telegram_notify_content'),
                    diy_system_node_online_report_telegram_notify_content: $$getValue('diy_system_node_online_report_telegram_notify_content')
                },
                success: data => {
                    $("#result").modal();
                    $$.getElementById('msg').innerHTML = data.msg;
                    if (data.ret) {
                        window.setTimeout("location.href='/admin/setting'", {$config['jump_delay']});
                    }
                },
                error: jqXHR => {
                    alert(`发生错误：${
                            jqXHR.status
                            }`);
                }
            })
        })
    })
</script>
<!-- subscribe js-->
<script>
    window.addEventListener('load', () => {
        $$.getElementById('submit_subscribe_general').addEventListener('click', () => {
            $.ajax({
                type: "POST",
                url: "/admin/setting",
                dataType: "json",
                data: {
                    class: 'subscribe_general',
                    enable_subscribe: $$getValue('enable_subscribe'),
                    subscribe_address_url: $$getValue('subscribe_address_url'),
                    enable_subscribe_emoji: $$getValue('enable_subscribe_emoji'),
                    enable_subscribe_extend: $$getValue('enable_subscribe_extend'),
                    enable_subscribe_change_token_when_change_passwd: $$getValue('enable_subscribe_change_token_when_change_passwd'),
                    enable_subscribe_log: $$getValue('enable_subscribe_log'),
                    subscribe_log_save_days: $$getValue('subscribe_log_save_days'),
                    subscribe_diy_message: $$getValue('subscribe_diy_message'),
                    subscribe_clash_default_profile:$$getValue('subscribe_clash_default_profile'),
                    subscribe_surge_default_profile:$$getValue('subscribe_surge_default_profile'),
                    subscribe_surfboard_default_profile:$$getValue('subscribe_surfboard_default_profile')
                },
                success: data => {
                    $("#result").modal();
                    $$.getElementById('msg').innerHTML = data.msg;
                    if (data.ret) {
                        window.setTimeout("location.href='/admin/setting'", {$config['jump_delay']});
                    }
                },
                error: jqXHR => {
                    alert(`发生错误：${
                            jqXHR.status
                            }`);
                }
            })
        })
    })
</script>