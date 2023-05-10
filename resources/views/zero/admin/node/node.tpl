<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{$config["appName"]} 节点</title>
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
                                            <div class="card-title text-dark fs-3 fw-bolder">节点列表</div>
                                            <div class="card-toolbar">
                                                <div class="d-flex align-items-center position-relative me-2 my-2">
                                                    <span class="svg-icon svg-icon-1 position-absolute ms-6"> 
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                                                        </svg>
                                                    </span>
                                                    <input type="text" data-kt-admin-table-filter="search" class="form-control form-control-solid w-250px ps-15" placeholder="Search Customers"/>
                                                </div>
                                                <a class="btn btn-primary btn-sm fw-bold" href="node/create">
                                                <i class="bi bi-cloud-plus fs-3"></i>创建节点
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
    </body>
    <script>
        window.addEventListener('load', () => {
            {include file='table/js_2.tpl'}
        })
    </script>
    <script>
        function updateNodeStatus(id) {
            if ($("#node_status_"+id).prop("checked")) {
                var status = 1;
            } else {
                var status = 0;
            }
            $.ajax({
                type: "PUT",
                url: "/{$config['website_admin_path']}/node/update/status",
                dataType: "JSON",
                data: {
                    status,
                    id,
                },
                success: function(data){}
            });
        }
    </script>
    <script>
        const filterSearch = document.querySelector('[data-kt-admin-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            table_1.search(e.target.value).draw();
        });
    </script>
</html>