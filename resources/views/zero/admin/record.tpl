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
                                    <div class="card mb-5">
                                        <div class="card-header">
                                            <div class="card-title text-dark fs-3 fw-bolder">在线用户</div>
                                        </div>
                                        <div class="card-body">
                                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="zero_admin_record_alive">
                                                <thead>
                                                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">                                                       
                                                        {foreach $table_config_alive['total_column'] as $key_alive => $value_alive}
                                                            <th class="{$key_alive}">{$value_alive}</th>
                                                        {/foreach}
                                                    </tr>
                                                </thead>
                                                <tbody class="text-gray-600 fw-semibold"></tbody>
                                            </table>
                                        </div>  
                                    </div>
                                    <div class="card mb-5">
                                        <div class="card-header">
                                            <div class="card-title text-dark fs-3 fw-bolder">登录记录</div>
                                        </div>
                                        <div class="card-body">
                                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="zero_admin_record_signin">
                                                <thead>
                                                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">                                                       
                                                        {foreach $table_config_signin['total_column'] as $key_signin => $value_signin}
                                                            <th class="{$key_signin}">{$value_signin}</th>
                                                        {/foreach}
                                                    </tr>
                                                </thead>
                                                <tbody class="text-gray-600 fw-semibold"></tbody>
                                            </table>
                                        </div>  
                                    </div>
                                    <div class="card mb-5">
                                        <div class="card-header">
                                            <div class="card-title text-dark fs-3 fw-bolder">订阅记录</div>
                                        </div>
                                        <div class="card-body">
                                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="zero_admin_record_subscribe">
                                                <thead>
                                                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">                                                       
                                                        {foreach $table_config_subscribe['total_column'] as $key_subscribe => $value_subscribe}
                                                            <th class="{$key_subscribe}">{$value_subscribe}</th>
                                                        {/foreach}
                                                    </tr>
                                                </thead>
                                                <tbody class="text-gray-600 fw-semibold"></tbody>
                                            </table>
                                        </div>  
                                    </div>
                                    <div class="card mb-5">
                                        <div class="card-header">
                                            <div class="card-title text-dark fs-3 fw-bolder">流量记录</div>
                                        </div>
                                        <div class="card-body">
                                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="zero_admin_record_subscribe">
                                                <thead>
                                                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">                                                       
                                                        {foreach $table_config_traffic['total_column'] as $key_traffic => $value_traffic}
                                                            <th class="{$key_traffic}">{$value_traffic}</th>
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
                    </div>
                </div>
            </div>
        </div>
        {include file='admin/script.tpl'}
    </body>
    <script>
        table_1 = $('#zero_admin_record_alive').DataTable({
        ajax: {
        url: '{$table_config_alive['ajax_url']}',
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
        {foreach $table_config_alive['total_column'] as $key_alive => $value_alive}
            { "data": "{$key_alive}" },
        {/foreach}
        ],
        {include file='table/lang_chinese.tpl'}
        })


        var has_init = JSON.parse(localStorage.getItem(window.location.href + '-hasinit'));
    </script>
    <script>
        table_1 = $('#zero_admin_record_signin').DataTable({
        ajax: {
        url: '{$table_config_signin['ajax_url']}',
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
        {foreach $table_config_signin['total_column'] as $key_signin => $value_signin}
            { "data": "{$key_signin}" },
        {/foreach}
        ],
        {include file='table/lang_chinese.tpl'}
        })


        var has_init = JSON.parse(localStorage.getItem(window.location.href + '-hasinit'));
    </script>
    <script>
        table_1 = $('#zero_admin_record_subscribe').DataTable({
        ajax: {
        url: '{$table_config_subscribe['ajax_url']}',
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
        {foreach $table_config_subscribe['total_column'] as $key_subscribe => $value_subscribe}
            { "data": "{$key_subscribe}" },
        {/foreach}
        ],
        {include file='table/lang_chinese.tpl'}
        })


        var has_init = JSON.parse(localStorage.getItem(window.location.href + '-hasinit'));
    </script>
    <script>
        table_1 = $('#zero_admin_record_traffic').DataTable({
        ajax: {
        url: '{$table_config_traffic['ajax_url']}',
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
        {foreach $table_config_traffic['total_column'] as $key_traffic => $value_traffic}
            { "data": "{$key_traffic}" },
        {/foreach}
        ],
        {include file='table/lang_chinese.tpl'}
        })


        var has_init = JSON.parse(localStorage.getItem(window.location.href + '-hasinit'));
    </script>
</html>