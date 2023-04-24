<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{$config["appName"]} 支付设置</title>
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
                                        <div class="card-header">
                                            <div class="card-title text-dark fs-3 fw-bolder">支付配置</div>
                                            <div class="card-toolbar">
                                                <button class="btn btn-primary fs-bold" data-bs-toggle="modal" data-bs-target="#zero_modal_create_payment">
                                                    <i class="bi bi-plus-lg fs-2"></i>添加支付方式
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            {include file='table/table.tpl'}
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

        <!-- modal -->
        <div class="modal fade" id="zero_modal_create_payment" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content rounded">
                    <div class="modal-header justify-content-end border-0 pb-0">
                        <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                            
                            <span class="svg-icon svg-icon-1">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                                    <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                                </svg>
                            </span>
                            
                        </div>
                    </div>
                    <div class="modal-body scroll-y pt-0 pb-15 px-5 px-xl-20">
                        <div class="mb-13 text-center">
                            <h1 class="mb-3">支付配置</h1>
                        </div>
                        <div class="d-flex flex-column mb-8">
                            <label class="form-label fw-bold">显示名称</label>
                            <input class="form-control mb-5" id="payment_name" value="" type="text" placeholder="用于前端显示" />
                            <label class="form-label fw-bold">图标URL(选填)</label>
                            <input class="form-control mb-5" id="payment_icon" value="" type="text" placeholder="用于前端显示使用(https://xxxx.com/icon.svg)" />
                            <label class="form-label fw-bold">自定义通知域名(选填)</label>
                            <input class="form-control mb-5" id="payment_notify_domain" value="" type="text" placeholder="网关通知将发送到此域名(https://xxxx.com)" />
                            <div class="row mb-5">
                                <div class="col-4">
                                    <label class="form-label fw-bold">百分比手续费(选填)</label>
                                    <div class="input-group">
                                        <input class="form-control" id="payment_percent_fee" value="" type="text" placeholder="在订单金额的基础上附加手续费" />
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <label class="form-label fw-bold">固定手续费(选填)</label>
                                    <input class="form-control" id="payment_fixed_fee" value="" type="text" placeholder="在订单金额的基础上附加手续费" />
                                </div>
                                <div class="col-4">
                                    <label class="form-label fw-bold">充值返利(选填，仅充值)</label>
                                    <div class="input-group">
                                        <input class="form-control" id="payment_recharge_bonus" value="" type="number" placeholder="在订单金额的基础返利" />
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                            <label class="form-label fw-bold">接口文件</label>
                            <select class="form-select mb-5" id="payment_gateway" value="" data-control="select2" data-hide-search="true">
                                <option value="Epay">Epay</option>>
                                <option value="TronapiPay">TronapiPay</option>
                                <option value="Mgate">Mgate</option>
                                <option value="AlipayF2F">AlipayF2F</option>
                                <option value="Epusdt">Epusdt</option>
                            </select>
                            <div id="payment_config_epay" class="">
                                <label class="form-label fw-bold">URL</label>
                                <input class="form-control mb-5" id="epay_url" value="" type="text" placeholder="" />
                                <label class="form-label fw-bold">PID</label>
                                <input class="form-control mb-5" id="epay_pid" value="" type="text" placeholder="" />
                                <label class="form-label fw-bold">KEY</label>
                                <input class="form-control mb-5" id="epay_key" value="" type="text" placeholder="" />
                            </div>
                            <div id="payment_config_tronapipay" class="d-none">
                                <label class="form-label fw-bold">Public key</label>
                                <input class="form-control mb-5" id="tronapipay_public_key" value="" type="text" placeholder="" />
                                <label class="form-label fw-bold">Private key</label>
                                <input class="form-control mb-5" id="tronapipay_private_key" value="" type="text" placeholder="" />
                            </div>
                            <div class="d-none" id="payment_config_mgate">
                                <label class="form-label fw-bold">APP URL</label>
                                <input class="form-control mb-5" id="mgate_url" value="" type="text" placeholder="" />
                                <label class="form-label fw-bold">APP ID</label>
                                <input class="form-control mb-5" id="mgate_id" value="" type="text" placeholder="" />
                                <label class="form-label fw-bold">APP SECRET</label>
                                <input class="form-control mb-5" id="mgate_secret" value="" type="text" placeholder="" />
                            </div>
                            <div class="d-none" id="payment_config_alipayf2f">
                                <label class="form-label fw-bold">APP ID</label>
                                <input class="form-control mb-5" id="alipayf2f_id" value="" type="text" placeholder="" />
                                <label class="form-label fw-bold">Private key</label>
                                <input class="form-control mb-5" id="alipayf2f_private_key" value="" type="text" placeholder="" />
                                <label class="form-label fw-bold">Public key</label>
                                <input class="form-control mb-5" id="alipayf2f_public_key" value="" type="text" placeholder="" />
                            </div>
                            <div class="d-none" id="payment_config_epusdt">
                                <label class="form-label fw-bold">Epusdt URL</label>
                                <input class="form-control mb-5" id="epusdt_url" value="" type="text" placeholder="" />
                                <label class="form-label fw-bold">Private key</label>
                                <input class="form-control mb-5" id="epusdt_private_key" value="" type="text" placeholder="" />
                            </div>
                        </div>
                        <div class="d-flex flex-center flex-row-fluid pt-12">
                            <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">{$trans->t('discard')}</button>
                            <button type="submit" class="btn btn-primary" data-kt-admin-create-payment-action="submit" onclick="zeroAdminCreatePayment('create')">
                                <span class="indicator-label">{$trans->t('submit')}</span>
                                <span class="indicator-progress">{$trans->t('please wait')}
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {include file='admin/script.tpl'}
        <script>
            window.addEventListener('load', () => {
                {include file='table/js_2.tpl'}
            })
        </script>
        <script>
            $('#payment_gateway').change(function() {
                var configToggle = $(this).val();
                var $paymentConfigs = $('[id^=payment_config_]');
                $paymentConfigs.addClass('d-none');
                $('#payment_config_'+configToggle.toLowerCase()).removeClass('d-none');
            });
        </script>
        <script>
            function zeroAdminCreatePayment(type, id = 0, enable = 0) {
                const submitButton = document.querySelector('[data-kt-admin-create-payment-action="submit"]');
                submitButton.setAttribute('data-kt-indicator', 'on');
                submitButton.disabled = true;
                const paymentConfigs = {
                    'Epay': function() {
                        return {
                        'epay_url': $('#epay_url').val(),
                        'epay_pid': $('#epay_pid').val(),
                        'epay_key': $('#epay_key').val(),
                        };
                    },
                    'TronapiPay': function() {
                        return {
                        'tronapipay_public_key': $('#tronapipay_public_key').val(),
                        'tronapipay_private_key': $('#tronapipay_private_key').val()
                        };
                    },
                    'Mgate': function() {
                        return {
                        'mgate_url': $('#mgate_url').val(),
                        'mgate_id': $('#mgate_id').val(),
                        'mgate_secret': $('#mgate_secret').val(),
                        };
                    },
                    'AlipayF2F': function() {
                        return {
                        'alipayf2f_id': $('#alipayf2f_id').val(),
                        'alipayf2f_private_key': $('#alipayf2f_private_key').val(),
                        'alipayf2f_public_key': $('#alipayf2f_public_key').val()
                        };
                    },
                    'Epusdt': function() {
                        return {
                            'epusdt_url': $('#epusdt_url').val(),
                            'epusdt_private_key': $('#epusdt_private_key').val()
                        };
                    }
                };

                const getPaymentConfig = function(payment) {
                    if (paymentConfigs.hasOwnProperty(payment)) {
                        return paymentConfigs[payment]();
                    }
                    return {};
                }

                const payment = $('#payment_gateway').val();
                const config = getPaymentConfig(payment);
                $.ajax({
                    type: 'POST',
                    url: '/{$config['website_admin_path']}/payment/'+type,
                    dataType: 'json',
                    data: {
                        id,
                        payment_name: $('#payment_name').val(),
                        payment_icon: $('#payment_icon').val(),
                        payment_notify_domain: $('#payment_notify_domain').val(),
                        payment_percent_fee: $('#payment_percent_fee').val(),
                        payment_fixed_fee: $('#payment_fixed_fee').val(),
                        payment_gateway: $('#payment_gateway').val(),
                        payment_recharge_bonus: $('#payment_recharge_bonus').val(),
                        payment_config: config
                    },
                    success: function(data) {
                        if (data.ret == 1) {
                            getResult(data.msg, '', 'success');
                            submitButton.removeAttribute('data-kt-indicator');
                            submitButton.disabled = false;
                            table_1.ajax.reload();
                            $("#zero_modal_create_payment").modal('hide');
                        } else {
                            getResult(data.msg, '', 'error');
                            submitButton.removeAttribute('data-kt-indicator');
                            submitButton.disabled = false;
                        }
                    }
                })
            }
        </script>
        <script>
            function zeroAdminUpdatePayment(type, id) {
                const submitButton = document.querySelector('[data-kt-admin-create-payment-action="submit"]');
                switch (type) {
                    case 'request':
                        $.ajax({
                            type: 'POST',
                            url: '/{$config['website_admin_path']}/payment/config',
                            dataType: 'json',
                            data: {
                                id
                            },
                            success: function(data) {
                                              
                                const paymentGatewaySelect = $('#payment_gateway');
                                const paymentConfigSections = $('[id^=payment_config_]');
                                
                                $('#payment_name').val(data.payment_name);
                                $('#payment_icon').val(data.payment_icon);
                                $('#payment_notify_domain').val(data.payment_notify_domain);
                                $('#payment_percent_fee').val(data.payment_percent_fee);
                                $('#payment_fixed_fee').val(data.payment_fixed_fee);
                                $('#payment_recharge_bonus').val(data.payment_recharge_bonus);
                                paymentGatewaySelect.val(data.payment_gateway).trigger('change');
                                
                                const paymentConfigObj = {
                                    'epay': {
                                        'url': 'epay_url',
                                        'pid': 'epay_pid',
                                        'key': 'epay_key',
                                    },
                                    'tronapipay': {
                                        'public_key': 'tronapipay_public_key',
                                        'private_key': 'tronapipay_private_key',
                                    },
                                    'mgate': {
                                        'url': 'mgate_url',
                                        'id': 'mgate_id',
                                        'secret': 'mgate_secret',
                                    },
                                    'alipayf2f': {
                                        'id': 'alipayf2f_id',
                                        'private_key': 'alipayf2f_private_key',
                                        'public_key': 'alipayf2f_public_key',
                                    },
                                    'epusdt': {
                                        'url': 'epusdt_url',
                                        'private_key': 'epusdt_private_key',
                                    },
                                };
                                
                                if (paymentConfigObj.hasOwnProperty(data.payment_gateway.toLowerCase())) {
                                        const configKeys = Object.keys(paymentConfigObj[data.payment_gateway.toLowerCase()]);
                                        configKeys.forEach(key => {
                                        const selector = '#'+paymentConfigObj[data.payment_gateway.toLowerCase()][key];
                                        $(selector).val(data.payment_config[paymentConfigObj[data.payment_gateway.toLowerCase()][key]]);
                                    });
                                }
                                
                                submitButton.setAttribute('onclick', 'zeroAdminUpdatePayment("update", ' + id + ')');
                                paymentConfigSections.addClass('d-none');
                                $('#payment_config_'+data.payment_gateway.toLowerCase()).removeClass('d-none');
                                $('#zero_modal_create_payment').modal('show');
                            }
                            
                        });
                        break;
                    case 'update':
                        zeroAdminCreatePayment(type, id);
                        break;
                    default:
                        getResult('发生错误', '', 'error');
                }   
            }
        </script>
        <script>
            function zeroAdminEnablePayment(status, id) {
                $.ajax({
                    type: 'PUT',
                    url: '/{$config['website_admin_path']}/payment/enable',
                    dataType: 'json',
                    data: {
                        status,
                        id,
                    },
                    success: function(data){
                        table_1.ajax.reload();
                    }
                });
            }
        </script>
        <script>
            const submitButton = document.querySelector('[data-kt-admin-create-payment-action="submit"]');
            const zeroModal = $("#zero_modal_create_payment");
            const paymentGatewaySelect = $('#payment_gateway');
            const paymentConfigSections = $('[id^=payment_config_]');

            zeroModal.on('hidden.bs.modal', function () {
                const paymentFields = [
                    '#payment_name',
                    '#payment_icon',
                    '#payment_notify_domain',
                    '#payment_percent_fee',
                    '#payment_fixed_fee',
                    '#payment_recharge_bonus',
                    '#epay_url',
                    '#epay_pid',
                    '#epay_key',
                    '#tronapipay_public_key',
                    '#tronapipay_private_key',
                    '#mgate_url',
                    '#mgate_id',
                    '#mgate_secret',
                    '#alipayf2f_id',
                    '#alipayf2f_private_key',
                    '#alipayf2f_public_key',
                    '#epusdt_url',
                    '#epusdt_private_key',
                ];
                paymentFields.forEach(field => $(field).val(''));
                
                paymentGatewaySelect.val('Epay');
                paymentConfigSections.addClass('d-none');
                $('#payment_config_epay').removeClass('d-none');
                submitButton.setAttribute('onclick', 'zeroAdminCreatePayment("create")');
                console.log('success');
            });
    </script>
    </body>
</html>