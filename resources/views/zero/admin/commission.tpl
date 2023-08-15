<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{$config["website_name"]} 佣金</title>
        
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

                                    <div class="card mb-5">
                                        <div class="card-header">
                                            <div class="card-title text-dark fs-3 fw-bolder">提现管理</div>
                                        </div>
                                        <div class="card-body">
                                            {include file='table/table.tpl'}
                                        </div>  
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-title text-dark fs-3 fw-bolder">佣金记录</div>
                                        </div>
                                        <div class="card-body">
                                            <table class="table align-middle table-striped table-row-bordered text-nowrap gy-5 gs-7" id="zero_admin_commission_record">
                                                <thead>
                                                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">                                                       
                                                        {foreach $table_config_commission['total_column'] as $key_commission => $value_commission}
                                                            <th class="{$key_commission}">{$value_commission}</th>
                                                        {/foreach}
                                                    </tr>
                                                </thead>
                                                <tbody class="text-gray-600 fw-semibold"></tbody>
                                            </table>
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
        </script>
        <script>
            KTAdminCommissionRecord = $('#zero_admin_commission_record').DataTable({
            ajax: {
            url: '{$table_config_commission['ajax_url']}',
            type: "POST"
            },
            processing: true,
            serverSide: true,
            order: [[ 0, 'desc' ]],
            stateSave: true,
            columnDefs: [
                { width: '5%', targets: 0 },
                { className: 'text-end', targets: -1 },
                { orderable: false, targets: '_all' },
                { orderable: true, targets: 0}
            ],
            columns: [
            {foreach $table_config_commission['total_column'] as $key_commission => $value_commission}
                { "data": "{$key_commission}" },
            {/foreach}
            ],
            {include file='table/lang_chinese.tpl'}
            })


            var has_init = JSON.parse(localStorage.getItem(window.location.href + '-hasinit'));
        </script>
        <script>
            function zeroAdminUpdateWithdrawCommission(mode, id){
                $.ajax({
                    type: "PUT",
                    url: "/{$config['website_admin_path']}/commission/withdraw/update",
                    dataType: "json",
                    data: {
                        mode,
                        id
                    },
                    success: function(data){
                        if(data.ret == 1) {
                            getResult(data.msg, '', 'success');
                        }else{
                            getResult(data.msg, '', 'error');
                        }
                    }
                });
            }
        </script>
    </body>
</html>