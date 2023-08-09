<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{$config["website_name"]} 优惠码</title>
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
                                            <div class="card-title text-dark fs-3 fw-bolder">优惠码记录</div>
                                            <div class="card-toolbar">
                                                <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#zero_modal_create_coupon">
                                                <i class="bi bi-cloud-plus fs-3"></i>创建优惠码
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
        <div class="modal fade" id="zero_modal_create_coupon" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
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
                            <h1 class="mb-3">创建优惠码</h1>
                        </div>
                        <div class="d-flex flex-column mb-8">
                            <label class="d-flex align-items-center fs-6 fw-semibold mb-2" for="zero_create_coupon_code">
                                <span class="required">优惠码</span>
                            </label>
                            <input type="text" value="" class="form-control form-control-solid" placeholder="优惠码" id="zero_create_coupon_code">
                        </div>
                        <div class="d-flex flex-column mb-8">
                            <label class="d-flex align-items-center fs-6 fw-semibold mb-2" for="zero_create_coupon_dicount_rate">
                                <span class="required">折扣比例</span>
                            </label>
                            <div class="input-group input-group-solid">
                                <input type="text" value="" class="form-control form-control-solid" placeholder="折扣比例" id="zero_create_coupon_dicount_rate">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="d-flex flex-column mb-8">
                            <label class="fs-6 fw-semibold mb-2" for="zero_create_coupon_valid_time">
                                <span class="required">有效时间</span>
                            </label>
                            <div class="input-group input-group-solid">
                                <input type="text" value="1" class="form-control form-control-solid" placeholder="有效时间" id="zero_create_coupon_valid_time">
                                <span class="input-group-text">小时</span>
                            </div>
                        </div>
                        <div class="d-flex flex-column mb-8">
                            <label class="fs-6 fw-semibold mb-2" for="zero_create_coupon_limit_product">
                                <span class="required">指定商品</span>
                            </label>
                            <select class="form-select form-select-solid" id="zero_create_coupon_limit_product" data-control="select2" data-allow-clear="true" data-placeholder="限制指定产品使用优惠" multiple="multiple">
                                <option></option>
                                {foreach $products as $product}
                                    <option value={$product->id}>{$product->name}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="d-flex flex-column mb-8">
                            <label class="fs-6 fw-semibold mb-2" for="zero_create_coupon_limit_product_period">
                                <span class="required">指定周期</span>
                            </label>
                            <select class="form-select form-select-solid" id="zero_create_coupon_limit_product_period" data-control="select2" data-allow-clear="true" data-placeholder="限制指定周期使用优惠" multiple="multiple">
                                <option></option>                              
                                <option value="30">一个月</option>
                                <option value="90">三个月</option>
                                <option value="180">半年</option>
                                <option value="360">一年</option>
                                <option value="720">两年</option>
                            </select>
                        </div>
                        <div class="d-flex flex-column mb-8">
                            <label class="fs-6 fw-semibold mb-2" for="zero_create_coupon_per_times">
                                <span class="required">用户使用次数</span>
                            </label>
                            <div class="input-group input-group-solid">
                                <input type="text" value="" class="form-control form-control-solid" placeholder="默认为无限制" id="zero_create_coupon_per_times">
                                <span class="input-group-text">次</span>
                            </div>
                        </div>
                        <div class="d-flex flex-column mb-8">
                            <label class="fs-6 fw-semibold mb-2" for="zero_create_coupon_total_times">
                                <span class="required">总使用次数</span>
                            </label>
                            <div class="input-group input-group-solid">
                                <input type="text" value="" class="form-control form-control-solid" placeholder="默认为无限制" id="zero_create_coupon_total_times">
                                <span class="input-group-text">次</span>
                            </div>
                        </div>
                        <div class="d-flex flex-column mb-8">
                            <label class="fs-6 fw-semibold mb-2" for="zero_create_coupon_generation_method">
                                <span class="required">生成方式</span>
                            </label>
                            <select class="form-select form-select-solid" data-control="select2" data-hide-search="true" id="zero_create_coupon_generation_method">
                                <option value="1">指定字符</option>
                                <option value="2">随机字符</option>
                                <option value="3">指定字符+随机字符</option>
                            </select>
                        </div>
                        <div class="d-flex flex-center flex-row-fluid pt-12">
                            <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">{$trans->t('discard')}</button>
                            <button type="submit" class="btn btn-primary" data-kt-admin-action="submit" onclick="zeroAdminCreateCoupon()">
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
            });
        </script>
        <script>
            function zeroAdminCreateCoupon(){
                const submitButton = document.querySelector('[data-kt-admin-action="submit"]');
                submitButton.setAttribute('data-kt-indicator', 'on');
                submitButton.disabled = true;
                const postData = {
                    code: $('#zero_create_coupon_code').val(),
                    discount: $('#zero_create_coupon_dicount_rate').val(),
                    limited_product: ($('#zero_create_coupon_limit_product').val().length === 0) ? [""] : $('#zero_create_coupon_limit_product').val(),
                    limited_product_period: ($('#zero_create_coupon_limit_product_period').val().length === 0) ? [""] : $('#zero_create_coupon_limit_product_period').val(),
                    per_use_count: $('#zero_create_coupon_per_times').val(),
                    total_use_count: $('#zero_create_coupon_total_times').val(),
                    expire: $('#zero_create_coupon_valid_time').val(),
                    generate_type: $('#zero_create_coupon_generation_method').val(),
                };
                $.ajax({
                    type: "POST",
                    url: "/{$config['website_admin_path']}/coupon/create",
                    dataType: "json",
                    data: postData,
                    success: function (data) {
                        if (data.ret == 1) {
                            setTimeout(function() {
                                submitButton.removeAttribute('data-kt-indicator');
                                submitButton.disabled = false;
                                getResult(data.msg, '', 'success');
                                $('#zero_modal_create_coupon').modal('hide');
                                table_1.ajax.reload();
                            }, 1500);
                        } else {
                            getResult(data.msg, '', 'error');
                            submitButton.removeAttribute('data-kt-indicator');
                            submitButton.disabled = false;
                        }
                    }
                });
            }
        </script>
    </body>
</html>