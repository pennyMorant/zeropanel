<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{$config["website_name"]} 产品</title>
        
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
                                            <div class="card-title text-dark fs-3 fw-bolder">产品列表</div>
                                            <div class="card-toolbar">
                                                <a class="btn btn-primary btn-sm fw-bold" href="product/create">
                                                <i class="bi bi-cloud-plus fs-3"></i>创建产品
                                                </a>
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
        {include file='admin/script.tpl'}
        <script>
            window.addEventListener('load', () => {
                {include file='table/js_2.tpl'}
            })
            function updateProductStatus(method, type, id) {
                switch (method) {
                    case 'status':
                        $.ajax({
                            type: "PUT",
                            url: "/{$config['website_admin_path']}/product/update/status",
                            dataType: "JSON",
                            data: {
                                type,
                                id,
                                method
                            },
                            success: function(data){
                                table_1.ajax.reload();
                            }
                        });
                        break;
                    case 'renew':
                        $.ajax({
                            type: "PUT",
                            url: "/{$config['website_admin_path']}/product/update/status",
                            dataType: "JSON",
                            data: {
                                type,
                                id,
                                method
                            },
                            success: function(data){
                                table_1.ajax.reload();
                            }
                        });
                        break;
                    default:
                        getresult('请求错误', '', 'error');
                        break;
                }
            }
        </script>
    </body>
</html>