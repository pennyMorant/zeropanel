                                                <div class="tab-pane fade" id="zero_admin_nav_account" role="tabpanel" aria-labelledby="zero_admin_nav_account_tab" tabindex="0">
                                                    <div class="card card-bordered mb-5">
                                                        <div class="card-header">
                                                            <div class="card-title fw-bolder">注册配置</div>
                                                            <div class="card-toolbar">
                                                                <button class="btn btn-light-primary btn-sm" type="button" onclick="updateAdminConfigSettings('register')">
                                                                <i class="bi bi-save"></i>保存配置
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row g-5">
                                                                <div class="col-xxl-6">
                                                                    <label class="form-label" for="reg_mode">注册模式</label>
                                                                    <select class="form-select mb-5" id="reg_mode" data-control="select2" data-hide-search="true">
                                                                        <option value="close">关闭注册</option>
                                                                        <option value="open">开启注册</option>
                                                                        <option value="invite">仅限邀请注册</option>
                                                                    </select>
                                                                    <label class="form-label" for="signup_default_class">默认等级</label>
                                                                    <input class="form-control mb-5" id="signup_default_class" value="{$settings['signup_default_class']}" type="text" placeholder="默认等级" />
                                                                    <label class="form-label" for="signup_default_class_time">默认等级时长</label>
                                                                    <div class="input-group mb-5">
                                                                        <input class="form-control" id="signup_default_class_time" value="{$settings['signup_default_class_time']}" type="text" placeholder="等级时长" />
                                                                        <span class="input-group-text">天</span>
                                                                    </div>
                                                                    <label class="form-label" for="verify_email">验证邮箱<i class="bi bi-question-circle ms-2" data-bs-toggle="tooltip" title="开启功能后, 用户未验证邮箱将无法购买任何套餐"></i></label>
                                                                    <select class="form-select mb-5" id="verify_email" data-control="select2" data-hide-search="true">
                                                                        <option value="close">关闭</option>
                                                                        <option value="open">开启</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-xxl-6">
                                                                    <label class="form-label" for="signup_default_traffic">默认流量</label>
                                                                    <div class="input-group mb-5">
                                                                        <input class="form-control" id="signup_default_traffic" value="{$settings['signup_default_traffic']}" type="text" placeholder="默认流量" />
                                                                        <span class="input-group-text">GB</span>
                                                                    </div>
                                                                    <label class="form-label" for="signup_default_ip_limit">默认IP限制</label>
                                                                    <div class="input-group mb-5">
                                                                        <input class="form-control" id="signup_default_ip_limit" value="{$settings['signup_default_ip_limit']}" type="text" placeholder="IP限制" />
                                                                        <span class="input-group-text">个</span>
                                                                    </div>
                                                                    <label class="form-label" for="signup_default_speed_limit">默认速度限制</label>
                                                                    <div class="input-group mb-5">
                                                                        <input class="form-control" id="signup_default_speed_limit" value="{$settings['signup_default_speed_limit']}" type="text" placeholder="速度限制" />
                                                                        <span class="input-group-text">Mbps</span>
                                                                    </div>
                                                                    <label class="form-label" for="limit_email_suffix">限制邮箱后缀<i class="bi bi-question-circle ms-2" data-bs-toggle="tooltip" title="开启功能后, 只允许设置的邮箱后缀才能注册账户"></i></label>
                                                                    <select class="form-select" id="limit_email_suffix" data-control="select2" data-placeholder="指定邮箱后缀" data-allow-clear="true" multiple="multiple" data-tags="true" >
                                                                        <option></option>                              
                                                                        {foreach json_decode($settings['limit_email_suffix']) as $email_domain}
                                                                            <option value="{$email_domain}">{$email_domain}</option>
                                                                        {/foreach}
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card card-bordered mb-5">
                                                        <div class="card-header">
                                                            <div class="card-title fw-bolder">验证配置</div>
                                                            <div class="card-toolbar">
                                                                <button class="btn btn-light-primary btn-sm" type="button" onclick="updateAdminConfigSettings('captcha')">
                                                                <i class="bi bi-save"></i>保存配置
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row -g-5">
                                                                <div class="col-xxl-6">
                                                                    <label class="form-label" for="captcha_provider">验证码提供商</label>
                                                                    <select id="captcha_provider" class="form-select mb-5" data-control="select2" data-hide-search="true">
                                                                        <option value="turnstile">Turnstile</option>
                                                                    </select>
                                                                    <label class="form-label" for="enable_signup_captcha">注册验证码</label>
                                                                    <select id="enable_signup_captcha" class="form-select mb-5" data-control="select2" data-hide-search="true">
                                                                        <option value="0">关闭</option>
                                                                        <option value="1">开启</option>
                                                                    </select>
                                                                    <label class="form-label" for="enable_signin_captcha">登录验证码</label>
                                                                    <select id="enable_signin_captcha" class="form-select" data-control="select2" data-hide-search="true">
                                                                        <option value="0">关闭</option>
                                                                        <option value="1">开启</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-xxl-6">
                                                                    <label class="form-label" for="turnstile_sitekey">Turnstile Site Key</label>
                                                                    <input class="form-select mb-5" id="turnstile_sitekey" value="{$settings['turnstile_sitekey']}">
                                                                    <label class="form-label" for="turnstile_secret">Turnstile Secret</label>
                                                                    <input class="form-select mb-5" id="turnstile_secret" value="{$settings['turnstile_secret']}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card card-bordered">
                                                        <div class="card-header">
                                                            <div class="card-title fw-bolder">客服配置</div>
                                                            <div class="card-toolbar">
                                                                <button class="btn btn-light-primary btn-sm" type="button" onclick="updateAdminConfigSettings('live_chat')">
                                                                <i class="bi bi-save"></i>保存配置
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row g-5">
                                                                <div class="col-xxl-6">
                                                                    <label class="form-label" for="live_chat">网页客服系统</label>
                                                                    <select id="live_chat" class="form-control mb-5" data-control="select2" data-hide-search="true">
                                                                        <option value="none">不启用</option>
                                                                        <option value="tawk">Tawk</option>
                                                                        <option value="crisp">Crisp</option>
                                                                        <option value="livechat">LiveChat</option>
                                                                        <option value="mylivechat">MyLiveChat</option>
                                                                    </select>
                                                                    <label class="form-label" for="tawk_id">Tawk</label>
                                                                    <input class="form-control mb-5" id="tawk_id" value="{$settings['tawk_id']}">
                                                                    <label class="form-label" for="crisp_id">Crisp</label>
                                                                    <input class="form-control" id="crisp_id" value="{$settings['crisp_id']}">
                                                                </div>
                                                                <div class="col-xxl-6">
                                                                    <label class="form-label" for="livechat_id">LiveChat</label>
                                                                    <input class="form-control mb-5" id="livechat_id" value="{$settings['livechat_id']}">
                                                                    <label class="form-label" for="mylivechat_id">MyLiveChat</label>
                                                                    <input class="form-control mb-5" id="mylivechat_id" value="{$settings['mylivechat_id']}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>