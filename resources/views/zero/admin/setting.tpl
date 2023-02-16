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
                                        <div class="card-body">
                                            <nav>
                                                <div class="nav nav-tabs mb-10" id="nav-tab" role="tablist">
                                                  <button class="nav-link active fw-bolder fs-3" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">基础</button>
                                                  <button class="nav-link fw-bolder fs-3" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">支付</button>
                                                  <button class="nav-link fw-bolder fs-3" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">邮件</button>
                                                  <button class="nav-link" id="nav-disabled-tab" data-bs-toggle="tab" data-bs-target="#nav-disabled" type="button" role="tab" aria-controls="nav-disabled" aria-selected="false" disabled>Disabled</button>
                                                </div>
                                            </nav>
                                            <div class="tab-content" id="nav-tabContent">
                                                <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
                                                    <div class="row d-flex flex-column-fluid">
                                                        <div class="col-6">
                                                            <label class="form-label">网站地址</label>
                                                            <input class="form-control mb-5" id="zero_web_url" name="zero_web_url" type="text" placeholder="网站地址" value="" />
                                                            <label class="form-label">网站名称</label>
                                                            <input class="form-control mb-5" id="zero_web_name" name="zero_web_name" type="text" placeholder="网站名称" value="" />
                                                            <label class="form-label">LANDING INDEX</label>
                                                            <input class="form-control mb-5" id="zero_web_landing_index" name="zero_web_landing_index" type="text" placeholder="" value="" />
                                                        </div>
                                                        <div class="col-6">
                                                            <label class="form-label">安全TOKEN</label>
                                                            <input class="form-control mb-5" id="zero_web_token" name="zero_web_token" type="text" placeholder="TOKEN" value="" />
                                                            <label class="form-label">后端TOKEN</label>
                                                            <input class="form-control mb-5" id="zero_backend_request_token" name="zero_web_url" type="text" placeholder="token" value="" />
                                                            <label class="form-label">本位货币</label>
                                                            <select class="form-select mb-5">
                                                                <option value="USD">USD</option>
                                                                <option value="CNY">CNY</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab" tabindex="0">
                                                    <div class="row g-5">
                                                        <div class="col-xxl-6">
                                                            <label class="form-label">支付宝</label>
                                                            <select class="form-select mb-5">
                                                                <option value="">关闭</option>
                                                                <option value="paybeaver">海狸支付</option>
                                                                <option value="paytaro">Paytaro</option>
                                                                <option value="stripe">Stripe</option>
                                                                <option value="easypay">易支付</option>
                                                            </select>
                                                            <label class="form-label">微信</label>
                                                            <select class="form-select mb-5">
                                                                <option value="">关闭</option>
                                                                <option value="paybeaver">海狸支付</option>
                                                                <option value="paytaro">Paytaro</option>
                                                                <option value="stripe">Stripe</option>
                                                                <option value="easypay">易支付</option>
                                                            </select>
                                                            
                                                        </div>
                                                        <div class="col-xxl-6">
                                                            <label class="form-label">虚拟币</label>
                                                            <select class="form-select mb-5">
                                                                <option value="">关闭</option>
                                                                <option value="tronapipay">TronapiPay</option>
                                                            </select>
                                                            <label class="form-label">QQ钱包</label>
                                                            <select class="form-select mb-5">
                                                                <option value="">关闭</option>
                                                            </select>
                                                        </div>                                                       
                                                    </div>
                                                    <div class="separator border-primary my-10"></div>
                                                    <div class="row g-5">
                                                        <div class="col-xxl-6">
                                                            <div class="card card-bordered mb-5">
                                                                <div class="card-header">
                                                                    <div class="card-title">海狸支付</div>
                                                                    <div class="card-toolbar">
                                                                        <button class="btn btn-light-primary btn-sm fw-bold" type="button">保存配置</button>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="card-body">
                                                                    <label class="form-label">账号</label>
                                                                    <input class="form-control mb-5" placeholder="账号">
                                                                    <label class="form-label">密钥</label>
                                                                    <input class="form-control mb-5" placeholder="密钥">
                                                                </div>
                                                            </div>
                                                            <div class="card card-bordered mb-5">
                                                                <div class="card-header">
                                                                    <div class="card-title">PayTaro</div>
                                                                    <div class="card-toolbar">
                                                                        <button class="btn btn-light-primary btn-sm fw-bold" type="button">保存配置</button>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <label class="form-label">账号</label>
                                                                    <input class="form-control mb-5" placeholder="账号">
                                                                    <label class="form-label">密钥</label>
                                                                    <input class="form-control mb-5" placeholder="密钥">
                                                                </div>
                                                            </div>
                                                            <div class="card card-bordered">
                                                                <div class="card-header">
                                                                    <div class="card-title">TronapiPay</div>
                                                                    <div class="card-toolbar">
                                                                        <button class="btn btn-light-primary btn-sm fw-bold" type="button">保存配置</button>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <label class="form-label">账号</label>
                                                                    <input class="form-control mb-5" placeholder="账号">
                                                                    <label class="form-label">密钥</label>
                                                                    <input class="form-control mb-5" placeholder="密钥">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xxl-6">
                                                            <div class="card card-bordered mb-5">
                                                                <div class="card-header">
                                                                    <div class="card-title">Stripe</div>
                                                                    <div class="card-toolbar">
                                                                        <button class="btn btn-light-primary btn-sm fw-bold" type="button">保存配置</button>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <label class="form-label">账号</label>
                                                                    <input class="form-control mb-5" placeholder="账号">
                                                                    <label class="form-label">密钥</label>
                                                                    <input class="form-control mb-5" placeholder="密钥">
                                                                </div>
                                                            </div>
                                                            <div class="card card-bordered">
                                                                <div class="card-header">
                                                                    <div class="card-title">易支付</div>
                                                                    <div class="card-toolbar">
                                                                        <button class="btn btn-light-primary btn-sm fw-bold" type="button">保存配置</button>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <label class="form-label">账号</label>
                                                                    <input class="form-control mb-5" placeholder="账号">
                                                                    <label class="form-label">密钥</label>
                                                                    <input class="form-control mb-5" placeholder="密钥">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab" tabindex="0">

                                                </div>
                                                <div class="tab-pane fade" id="nav-disabled" role="tabpanel" aria-labelledby="nav-disabled-tab" tabindex="0">...</div>
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
</html>