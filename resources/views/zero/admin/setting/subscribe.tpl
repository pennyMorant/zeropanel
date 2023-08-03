                                                <div class="tab-pane fade" id="zero_admin_nav_sub" role="tabpanel" aria-labelledby="zero_admin_nav_sub_tab" tabindex="0">                                                   
                                                    <div class="card card-bordered">
                                                        <div class="card-header">
                                                            <div class="card-title fw-bolder">订阅配置</div>
                                                            <div class="card-toolbar">
                                                                <button class="btn btn-light-primary btn-sm" type="button" onclick="updateAdminConfigSettings('subscribe')">
                                                                <i class="bi bi-save"></i>保存配置
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row g-5">
                                                                <div class="col-xxl-6">
                                                                    <label class="form-label" for="subscribe_address_url">订阅地址</label>
                                                                    <input class="form-control mb-5" id="subscribe_address_url" value="{$settings['subscribe_address_url']}" type="text" placeholder="订阅地址" />
                                                                    <label class="form-label" for="enable_subscribe_extend">订阅显示流量和时间</label>
                                                                    <select class="form-select mb-5" id="enable_subscribe_extend" data-control="select2" data-hide-search="true">
                                                                        <option value="0" {if $settings['enable_subscribe_extend'] == false}selected{/if}>关闭</option>
                                                                        <option value="1" {if $settings['enable_subscribe_extend'] == true}selected{/if}>开启</option>
                                                                    </select>
                                                                    <label class="form-label" for="enable_subscribe_emoji">订阅显示emoji</label>
                                                                    <select class="form-select mb-5" id="enable_subscribe_emoji" data-control="select2" data-hide-search="true">
                                                                        <option value="0" {if $settings['enable_subscribe_emoji'] == false}selected{/if}>关闭</option>
                                                                        <option value="1" {if $settings['enable_subscribe_emoji'] == true}selected{/if}>开启</option>
                                                                    </select>
                                                                    
                                                                </div>
                                                                <div class="col-xxl-6">
                                                                    <label class="form-label" for="subscribe_diy_message">订阅营销信息</label>
                                                                    <input class="form-control mb-5" id="subscribe_diy_message" value="{$settings['subscribe_diy_message']}" type="text" placeholder="营销信息" />
                                                                    <label class="form-label" for="enable_subscribe_log">订阅日志记录</label>
                                                                    <select class="form-select mb-5" id="enable_subscribe_log" data-control="select2" data-hide-search="true">
                                                                        <option value="0" {if $settings['enable_subscribe_log'] == false}selected{/if}>关闭</option>
                                                                        <option value="1" {if $settings['enable_subscribe_log'] == true}selected{/if}>开启</option>
                                                                    </select>
                                                                    <label class="form-label" for="subscribe_log_keep_time">订阅日志保留时间</label>
                                                                    <input class="form-control" id="subscribe_log_keep_time" value="{$settings['subscribe_log_keep_time']}" type="text" placeholder="保留时间" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>                                                      
                                                </div>