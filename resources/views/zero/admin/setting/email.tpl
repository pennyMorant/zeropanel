                                                <div class="tab-pane fade" id="zero_admin_nav_email" role="tabpanel" aria-labelledby="zero_admin_nav_email_tab" tabindex="0">
                                                    <div class="row g-5">
                                                        <div class="col-xxl-6">                                                   
                                                            <div class="card card-bordered mb-5">
                                                                <div class="card-header">
                                                                    <div class="card-title fw-bolder">邮件配置</div>
                                                                    <div class="card-toolbar">
                                                                        <button class="btn btn-light-primary btn-sm" type="button" onclick="updateAdminConfigSettings('mail')">
                                                                            <i class="bi bi-save"></i>保存配置
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <label class="form-label" for="mail_driver">邮件服务商</label>
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
                                                                    <div class="card-title fw-bolder">邮件备份</div>
                                                                    <div class="card-toolbar">
                                                                        <button class="btn btn-light-primary btn-sm" type="button" onclick="updateAdminConfigSettings('backup')">
                                                                            <i class="bi bi-save"></i>保存配置
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <label class="form-label" for="auto_backup_email">接收备份的邮箱</label>
                                                                    <input class="form-control mb-5" id="auto_backup_email" value="{$settings['auto_backup_email']}" type="text" placeholder="邮箱" />
                                                                    <label class="form-label" for="auto_backup_password">备份的压缩密码</label>
                                                                    <input class="form-control mb-5" id="auto_backup_password" value="{$settings['auto_backup_password']}" type="text" placeholder="密码" />
                                                                    <label class="form-label" for="auto_backup_notify">备份成功推送TG消息</label>
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
                                                                    <div class="card-title fw-bolder">邮件测试</div>
                                                                    <div class="card-toolbar">
                                                                        <button class="btn btn-light-primary btn-sm" onclick="sendTestEmail()">
                                                                            <i class="bi bi-send"></i>测试
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <label class="form-label" for="test_email_address">账号</label>
                                                                    <input class="form-control" id="test_email_address" value="" type="text" placeholder="账号" />
                                                                </div>
                                                            </div>
                                                            <div class="card card-bordered">
                                                                <div class="card-header">
                                                                    <div class="card-title fw-bolder">SENDGRID 配置</div>
                                                                    <div class="card-toolbar">
                                                                        <button class="btn btn-light-primary btn-sm" type="button" onclick="updateAdminConfigSettings('sendgrid')">
                                                                            <i class="bi bi-save"></i>保存配置
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <label class="form-label" for="sendgrid_key">密钥</label>
                                                                    <input class="form-control mb-5" id="sendgrid_key" value="{$settings['sendgrid_key']}" type="text" placeholder="密钥" />
                                                                    <label class="form-label" for="sendgrid_sender">发信邮箱</label>
                                                                    <input class="form-control mb-5" id="sendgrid_sender" value="{$settings['sendgrid_sender']}" type="text" placeholder="邮箱" />
                                                                    <label class="form-label" for="sendgrid_name">发信名称</label>
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
                                                                    <div class="card-title fw-bolder">SMTP 配置</div>
                                                                    <div class="card-toolbar">
                                                                        <button class="btn btn-light-primary btn-sm" type="button" onclick="updateAdminConfigSettings('smtp')">
                                                                        <i class="bi bi-save"></i>保存配置
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <label class="form-label" for="smtp_host">SMTP 主机地址</label>
                                                                    <input class="form-control mb-5" id="smtp_host" value="{$settings['smtp_host']}" type="text" />
                                                                    <label class="form-label" for="smtp_username">SMTP 账户名</label>
                                                                    <input class="form-control mb-5" id="smtp_username" value="{$settings['smtp_username']}" type="text" />
                                                                    <label class="form-label" for="smtp_password">SMTP 账户密码</label>
                                                                    <input class="form-control mb-5" id="smtp_password" value="{$settings['smtp_password']}" type="text" />
                                                                    <label class="form-label" for="smtp_port">SMTP 端口</label>
                                                                    <select class="form-select mb-5" id="smtp_port" data-control="select2" data-hide-search="true">
                                                                        <option value="465">465</option>
                                                                        <option value="587">587</option>
                                                                        <option value="2525">2525</option>
                                                                        <option value="25">25</option>
                                                                    </select>
                                                                    <label class="form-label" for="smtp_name">SMTP 发信名称</label>
                                                                    <input class="form-control mb-5" id="smtp_name" value="{$settings['smtp_name']}" type="text" />
                                                                    <label class="form-label" for="smtp_sender">SMTP 发信地址</label>
                                                                    <input class="form-control mb-5" id="smtp_sender" value="{$settings['smtp_sender']}" type="text" />
                                                                    <label class="form-label" for="smtp_ssl">是否使用 TLS/SSL 发信</label>
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
                                                                    <div class="card-title fw-bolder">MAILGUN 配置</div>
                                                                    <div class="card-toolbar">
                                                                        <button class="btn btn-light-primary btn-sm" type="button" onclick="updateAdminConfigSettings('mailgun')">
                                                                        <i class="bi bi-save"></i>保存配置
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <label class="form-label" for="mailgun_key">密钥</label>
                                                                    <input class="form-control mb-5" id="mailgun_key" value="{$settings['mailgun_key']}" type="text" placeholder="密钥">
                                                                    <label class="form-label" for="mailgun_domain">域名</label>
                                                                    <input class="form-control mb-5" id="mailgun_domain" value="{$settings['mailgun_domain']}" type="text" placeholder="域名">
                                                                    <label class="form-label" for="mailgun_sender">发信名称</label>
                                                                    <input class="form-control mb-5" id="mailgun_sender" value="{$settings['mailgun_sender']}" type="text" placeholder="发信名称">
                                                                </div>
                                                            </div>
                                                            <div class="card card-bordered">
                                                                <div class="card-header">
                                                                    <div class="card-title fw-bolder">SES 配置</div>
                                                                    <div class="card-toolbar">
                                                                        <button class="btn btn-light-primary btn-sm" type="button" onclick="updateAdminConfigSettings('ses')">
                                                                        <i class="bi bi-save"></i>保存配置
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <label class="form-label" for="aws_access_key_id">密钥 ID</label>
                                                                    <input class="form-control mb-5" id="aws_access_key_id" value="{$settings['aws_access_key_id']}" type="text" placeholder="密钥 ID" />
                                                                    <label class="form-label" for="aws_secret_access_key">密钥 KEY</label>
                                                                    <input class="form-control mb-5" id="aws_secret_access_key" value="{$settings['aws_secret_access_key']}" type="text" placeholder="密钥 KEY" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>