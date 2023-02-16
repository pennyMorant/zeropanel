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
                                    <div class="card mb-9">
                                        <div class="card-header">
                                            <div class="card-title text-dark fs-3 fw-bolder">封禁规则</div>
                                            <div class="card-toolbar">
                                                <button class="btn btn-primary btn-sm">创建规则</button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            {include file='table/table.tpl'}
                                        </div>  
                                    </div>
                                    <div class="card mb-9">
                                        <div class="card-header">
                                            <div class="card-title text-dark fs-3 fw-bolder">封禁记录</div>
                                        </div>
                                        <div class="card-body">
                                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="zero_admin_ban_record">
                                                <thead>
                                                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">  
                                                        <th>ID</th>
                                                        <th>用户ID</th>
                                                        <th>触发次数</th>
                                                        <th>封禁时长</th>
                                                        <th>开始时间</th>
                                                        <th>结束时间</th>
                                                        <th>累积次数</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-gray-600 fw-semibold"></tbody>
                                            </table>
                                        </div>  
                                    </div>
                                    <div class="card mb-9">
                                        <div class="card-header">
                                            <div class="card-title text-dark fs-3 fw-bolder">探测记录</div>
                                        </div>
                                        <div class="card-body">
                                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="zero_admin_detect_record">
                                                <thead>
                                                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">  
                                                        <th>ID</th>
                                                        <th>用户ID</th>
                                                        <th>节点ID</th>
                                                        <th>规则ID</th>
                                                        <th>时间</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-gray-600 fw-semibold"></tbody>
                                            </table>
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
        window.addEventListener('load', () => {
            {include file='table/js_2.tpl'}
        })
    </script>
<script>
KTAdminBanRecord = $('#zero_admin_ban_record').DataTable({
ajax: {
url: '{$table_config_ban_record['ajax_url']}',
type: "POST"
},
processing: true,
serverSide: true,
order: [[ 0, 'desc' ]],
stateSave: true,
columnDefs: [
{
targets: [ '_all' ],
className: 'mdl-data-table__cell--non-numeric'
}
],
columns: [
{foreach $table_config_ban_record['total_column'] as $key => $value}
    { "data": "{$key}" },
{/foreach}
],
{include file='table/lang_chinese.tpl'}
})


var has_init = JSON.parse(localStorage.getItem(window.location.href + '-hasinit'));

</script>

<script>
KTAdminDetectRecord = $('#zero_admin_detect_record').DataTable({
ajax: {
url: '{$table_config_detect_record['ajax_url']}',
type: "POST"
},
processing: true,
serverSide: true,
order: [[ 0, 'desc' ]],
stateSave: true,
columnDefs: [
{
targets: [ '_all' ],
className: 'mdl-data-table__cell--non-numeric'
}
],
columns: [
{foreach $table_config_detect_record['total_column'] as $key => $value}
{ "data": "{$key}" },
{/foreach}
],
{include file='table/lang_chinese.tpl'}
})


var has_init = JSON.parse(localStorage.getItem(window.location.href + '-hasinit'));

</script>
</html>