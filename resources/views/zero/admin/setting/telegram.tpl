                                                <div class="tab-pane fade" id="zero_admin_nav_tg" role="tabpanel" aria-labelledby="zero_admin_nav_tg_tab" tabindex="0">
                                                    <div class="row g-5">
                                                        <div class="col-xxl-6">
                                                            <div class="card card-bordered">
                                                                <div class="card-header">
                                                                    <div class="card-title fw-bolder">Telegram 配置</div>
                                                                    <div class="card-toolbar">
                                                                        <button class="btn btn-light-primary btn-sm" type="button" onclick="updateAdminConfigSettings('telegram')">
                                                                        <i class="bi bi-save"></i>保存配置
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <label class="form-label" for="telegram_group_id">群组 ID</label>
                                                                    <input class="form-control mb-5" id="telegram_group_id" value="{$settings['telegram_group_id']}" type="text" placeholder="ID" />
                                                                    <label class="form-label" for="telegram_group_url">群组地址</label>
                                                                    <input class="form-control mb-5" id="telegram_group_url" value="{$settings['telegram_group_url']}" type="text" placeholder="地址" />
                                                                    <label class="form-label" for="telegram_channel_id">频道账号</label>
                                                                    <input class="form-control mb-5" id="telegram_channel_id" value="{$settings['telegram_channel_id']}" type="text" placeholder="账号" />
                                                                    <label class="form-label" for="telegram_admin_id">ADMIN ID</label>
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
                                                                    <div class="card-title fw-bolder">Telegram BOT</div>
                                                                    <div class="card-toolbar">
                                                                        <button class="btn btn-light-primary btn-sm" type="button" onclick="updateAdminConfigSettings('telegram_bot')">
                                                                        <i class="bi bi-save"></i>保存配置
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <label class="form-label" for="enable_telegram_bot">启用BOT</label>
                                                                    <select class="form-select mb-5" id="enable_telegram_bot" data-control="select2" data-hide-search="true">
                                                                        <option value="0" {if $settings['enable_telegram_bot'] == false} selected{/if}>关闭</option>
                                                                        <option value="1" {if $settings['enable_telegram_bot'] == true} selected{/if}>开启</option>
                                                                    </select>
                                                                    <label class="form-label" for="telegram_bot_token">BOT TOKEN</label>
                                                                    <input class="form-control mb-5" id="telegram_bot_token" value="{$settings['telegram_bot_token']}" type="text" placeholder="TOKEN" />
                                                                    <label class="form-label" for="telegram_bot_id">BOT ID</label>
                                                                    <input class="form-control mb-5" id="telegram_bot_id" value="{$settings['telegram_bot_id']}" type="text" placeholder="BOT ID" />
                                                                    <label class="form-label" for="telegram_bot_request_token">请求 TOKEN</label>
                                                                    <input class="form-control mb-5" id="telegram_bot_request_token" value="{$settings['telegram_bot_request_token']}" type="text" placeholder="TOKEN" />
                                                                    <label class="form-label" for="enable_push_top_up_message">BOT 推送充值消息</label>
                                                                    <select class="form-select mb-5" id="enable_push_top_up_message" data-control="select2" data-hide-search="true">
                                                                        <option value="0">关闭</option>
                                                                        <option value="1">开启</option>
                                                                    </select>
                                                                    <label class="form-label" for="enable_push_ticket_message">BOT 推送工单消息</label>
                                                                    <select class="form-select mb-5" id="enable_push_ticket_message" data-control="select2" data-hide-search="true">
                                                                        <option value="0">关闭</option>
                                                                        <option value="1">开启</option>
                                                                    </select>
                                                                    <label class="form-label" for="enable_push_system_report">BOT 推送系统运行情况</label>
                                                                    <select class="form-select mb-5" id="enable_push_system_report" data-control="select2" data-hide-search="true">
                                                                        <option value="0">关闭</option>
                                                                        <option value="1">开启</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>