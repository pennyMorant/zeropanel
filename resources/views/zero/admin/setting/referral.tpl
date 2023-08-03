                                                <div class="tab-pane fade" id="zero_admin_nav_referral" role="tabpanel" aria-labelledby="zero_admin_nav_referral_tab" tabindex="0">
                                                    <div class="card card-bordered">
                                                        <div class="card-header">
                                                            <div class="card-title fw-bolder">模式配置</div>
                                                            <div class="card-toolbar">
                                                                <button class="btn btn-light-primary btn-sm" type="button" onclick="updateAdminConfigSettings('invite')">
                                                                <i class="bi bi-save"></i>保存配置
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row g-5">
                                                                <div class="col-xxl-6">
                                                                    <label class="form-label" for="invitation_mode">邀请模式</label>
                                                                    <select id="invitation_mode" class="form-select mb-5" data-control="select2" data-hide-search="true">
                                                                        <option value="registration_only">仅使用邀请注册功能，不返利</option>
                                                                        <option value="after_topup">使用邀请注册功能，并在被邀请用户充值时返利</option>
                                                                        <option value="after_purchase">使用邀请注册功能，并在被邀请用户购买时返利</option>
                                                                    </select>
                                                                    <label class="form-label" for="invite_rebate_mode">返利模式</label>
                                                                    <select id="invite_rebate_mode" class="form-select mb-5" data-control="select2" data-hide-search="true">
                                                                        <option value="continued">持续返利</option>
                                                                        <option value="limit_frequency">限制邀请人能从被邀请人身上获得的总返利次数</option>
                                                                        <option value="limit_amount">限制邀请人能从被邀请人身上获得的总返利金额</option>
                                                                        <option value="limit_time_range">限制邀请人能从被邀请人身上获得返利的时间范围</option>
                                                                    </select>
                                                                    <label class="form-label" for="rebate_ratio">返利比例</label>
                                                                    <div class="input-group mb-5">
                                                                        <input class="form-control" id="rebate_ratio" value="{$settings['rebate_ratio']}" type="number">
                                                                        <span class="input-group-text">%</span>
                                                                    </div>
                                                                    <label class="form-label" for="rebate_time_range_limit">返利时间范围限制</label>
                                                                    <div class="input-group">
                                                                        <input class="form-control" id="rebate_time_range_limit" value="{$settings['rebate_time_range_limit']}" type="number">
                                                                        <span class="input-group-text">天</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xxl-6">
                                                                    <label class="form-label" for="rebate_frequency_limit">返利总次数限制</label>
                                                                    <input class="form-control mb-5" id="rebate_frequency_limit" value="{$settings['rebate_frequency_limit']}" type="number">
                                                                    <label class="form-label" for="rebate_amount_limit">返利总金额限制</label>
                                                                    <input class="form-control mb-5" id="rebate_amount_limit" value="{$settings['rebate_amount_limit']}" type="number">
                                                                    <label class="form-label" for="invitation_to_signup_credit_reward">若有人使用现存用户的邀请链接注册，被邀请人所能获得的余额奖励</label>
                                                                    <input class="form-control mb-5" id="invitation_to_signup_credit_reward" value="{$settings['invitation_to_signup_credit_reward']}" type="number">
                                                                    <label class="form-label" for="invitation_to_signup_traffic_reward">若有人使用现存用户的邀请链接注册，邀请人所能获得的流量奖励</label>
                                                                    <div class="input-group">
                                                                        <input class="form-control" id="invitation_to_signup_traffic_reward" value="{$settings['invitation_to_signup_traffic_reward']}" type="number"> 
                                                                        <span class="input-group-text">GB</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>