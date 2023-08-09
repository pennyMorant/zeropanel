                                                <div class="tab-pane fade" id="zero_admin_nav_sell" role="tabpanel" aria-labelledby="zero_admin_nav_sell_tab" tabindex="0">
                                                    <div class="row g-5">
                                                        <div class="col-xxl-6">
                                                            <div class="card card-bordered">
                                                                <div class="card-header">
                                                                    <div class="card-title d-flex flex-column">
                                                                        <span class="fw-bolder">货币配置</span>
                                                                        <span class="text-gray-400 pt-1 fw-semibold fs-6">API申请地址: https://app.abstractapi.com</span>
                                                                    </div>
                                                                    <div class="card-toolbar">
                                                                        <button class="btn btn-light-primary btn-sm" type="button" onclick="updateAdminConfigSettings('currency')">
                                                                        <i class="bi bi-save"></i>保存配置
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <label class="form-label" for="enable_currency">开启其他货币(不开启默认为CNY)</label>
                                                                    <select class="form-select mb-5" id="enable_currency" data-control="select2" data-hide-search="true"> 
                                                                        <option value="0" {if $settings['enable_currency'] == false}selected{/if}>关闭</option>
                                                                        <option value="1" {if $settings['enable_currency'] == true}selected{/if}>开启</option>
                                                                    </select>
                                                                    <label class="form-label" for="currency_unit">货币单位</label>
                                                                    <select class="form-select mb-5" id="currency_unit">
                                                                        <option value="USD" data-kt-select2-country="/theme/zero/assets/media/flags/united-states.svg">USD</option>
                                                                        <option value="GBP" data-kt-select2-country="/theme/zero/assets/media/flags/united-kingdom.svg">GBP</option>
                                                                        <option value="CAD" data-kt-select2-country="/theme/zero/assets/media/flags/canada.svg">CAD</option>
                                                                        <option value="HKD" data-kt-select2-country="/theme/zero/assets/media/flags/hong-kong.svg">HKD</option>
                                                                        <option value="JPY" data-kt-select2-country="/theme/zero/assets/media/flags/japan.svg">JPY</option>
                                                                        <option value="SGD" data-kt-select2-country="/theme/zero/assets/media/flags/singapore.svg">SGD</option>
                                                                        <option value="EUR" data-kt-select2-country="/theme/zero/assets/media/flags/european-union.svg">EUR</option>
                                                                    </select>
                                                                    <label class="form-label" for="currency_exchange_rate">货币汇率</label>
                                                                    <input class="form-control mb-5" id="currency_exchange_rate" value="{$settings['currency_exchange_rate']}" type="text" placeholder="货币汇率" />
                                                                    <label class="form-label" for="currency_exchange_rate_api_key">汇率 API KEY</label>
                                                                    <input class="form-control mb-5" id="currency_exchange_rate_api_key" value="{$settings['currency_exchange_rate_api_key']}" type="text" placeholder="API KEY" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xxl-6">
                                                            <div class="card card-bordered">
                                                                <div class="card-header">
                                                                    <div class="card-title fw-bolder">提现配置</div>
                                                                    <div class="card-toolbar">
                                                                        <button class="btn btn-light-primary btn-sm" type="button" onclick="updateAdminConfigSettings('withdraw')">
                                                                        <i class="bi bi-save"></i>保存配置
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <label class="form-label" for="enable_withdraw">开启提现</label>
                                                                    <select class="form-select mb-5" id="enable_withdraw" data-control="select2" data-hide-search="true">
                                                                        <option value="0">关闭</option>
                                                                        <option value="1">开启</option>
                                                                    </select>
                                                                    <label class="form-label" for="withdraw_method">提现方式</label>
                                                                    <select class="form-select mb-5" id="withdraw_method">
                                                                        <option value="USDT" data-kt-select2-image="/theme/zero/assets/media/payment_logo/tether.svg">USDT</option>
                                                                    </select>
                                                                    <label class="form-label" for="withdraw_minimum_amount">最低提现金额</label>
                                                                    <input class="form-control mb-5" id="withdraw_minimum_amount" value="{$settings['withdraw_minimum_amount']}" type="number" placeholder="最低金额" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>