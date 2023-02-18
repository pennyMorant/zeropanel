<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{$config["appName"]} Dashboard</title>
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
                                            <div class="card-title text-dark fs-3 fw-bolder">产品配置</div>
                                            <div class="card-toolbar">
                                                <a class="btn btn-primary btn-sm fw-bold" onclick="zeroAdminUpdateProduct('{$product->id}')">保存产品</a>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-xxl-6">
                                                    <label class="form-label required">产品名称</label>
                                                    <input class="form-control mb-5" id="name" name="name" type="text" placeholder="产品名称" value="{$product->name}">
                                                    <label class="form-label required">产品价格</label>
                                                    <div class="row">
                                                        <div class="col-3">
                                                            <input class="form-control mb-5" id="month_price" name="month_price" type="number" placeholder="月付" value="{$product->month_price}">
                                                        </div>
                                                        <div class="col-3">
                                                            <input class="form-control mb-5" id="quarter_price" name="quarter_price" type="number" placeholder="季付" value="{$product->quarter_price}">
                                                        </div>
                                                        <div class="col-3">
                                                            <input class="form-control mb-5" id="half_year_price" name="half_year_price" type="number" placeholder="半年付" value="{$product->half_year_price}">
                                                        </div>
                                                        <div class="col-3">
                                                            <input class="form-control mb-5" id="year_price" name="year_price" type="number" placeholder="年付" value="{$product->year_price}">
                                                        </div>
                                                    </div>
                                                    <label class="form-label required">产品类型</label>
                                                    <select class="form-select mb-5" id="type" data-control="select2" data-hide-search="true">
                                                        <option value="1" {if $product->type == 1}selected{/if}>周期产品</option>
                                                        <option value="2" {if $product->type == 2}selected{/if}>流量产品</option>
                                                        <option value="3" {if $product->type == 3}selected{/if}>其他产品</option>
                                                    </select>
                                                    <label class="form-label required">产品流量(GB)</label>
                                                    <input class="form-control mb-5" id="traffic" name="traffic" type="number" placeholder="产品流量" value="{$product->traffic}">
                                                    <label class="form-label required">产品等级</label>
                                                    <input class="form-control mb-5" id="class" name="class" type="number" placeholder="产品等级" value="{$product->class}">
                                                    <label class="form-label required">产品群组</label>
                                                    <input class="form-control mb-5" id="group" name="group" type="number" placeholder="产品群组" value="{$product->user_group}">
                                                </div>
                                                <div class="col-xxl-6">
                                                    <label class="form-label required">产品库存</label>
                                                    <input class="form-control mb-5" id="stock" name="name" type="number" placeholder="产品库存" value="{$product->stock}">
                                                    <label class="form-label required">产品流量重置周期</label>
                                                    <select class="form-select mb-5" id="reset" data-control="select2" data-hide-search="true">
                                                        <option value="0" {if $product->reset_traffic_cycle === 0}selected{/if}>一次性</option>
                                                        <option value="1" {if $product->reset_traffic_cycle === 1}selected{/if}>订单日重置</option>
                                                        <option value="2" {if $product->reset_traffic_cycle === 2}selected{/if}>每月1日重置</option>
                                                    </select>
                                                    <label class="form-label required">产品速度</label>
                                                    <input class="form-control mb-5" id="speed_limit" name="speed_limit" type="number" placeholder="产品速度" value="{$product->speed_limit}">
                                                    <label class="form-label required">产品IP</label>
                                                    <input class="form-control mb-5" id="ip_limit" name="ip_limit" type="number" placeholder="产品IP" value="{$product->ip_limit}">
                                                    <label class="form-label required">产品排序</label>
                                                    <input class="form-control mb-5" id="sort" name="sort" type="number" placeholder="产品排序" value="{$product->sort}">
                                                </div>
                                            </div>
                                        </div>  
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {include file='admin/script.tpl'}
    </body>
    <script>
        function zeroAdminUpdateProduct(id) {
            $.ajax({
                type: "PUT",
                url: "/admin/product/update",
                dataType: "JSON",
                data: {
                    id,
                    name: $('#name').val(),
                    month_price: $('#month_price').val(),
                    quarter_price: $('#quarter_price').val(),
                    half_year_price: $('#half_year_price').val(),
                    year_price: $('#year_price').val(),
                    type: $('#type').val(),
                    traffic: $('#traffic').val(),
                    class: $('#class').val(),
                    group: $('#group').val(),
                    stock: $('#stock').val(),
                    reset: $('#reset').val(),
                    speed_limit: $('#speed_limit').val(),
                    ip_limit: $('#ip_limit').val(),
                    sort: $('#sort').val(),
                },
                success: function(data){
                    if (data.ret === 1){
                        Swal.fire({
                            text: data.msg,
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "Ok",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        }).then(function (result) {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
                    } else {
                        getResult(data.msg, '', 'error');
                    }
                }
            })
        }
    </script>
</html>