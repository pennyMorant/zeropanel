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
        <link href="https://cdn.jsdelivr.net/npm/jsoneditor/dist/jsoneditor.min.css" rel="stylesheet" type="text/css">
        <script src="https://cdn.jsdelivr.net/npm/jsoneditor/dist/jsoneditor.min.js"></script>
        <link href="/favicon.png" rel="shortcut icon">
        <link href="/apple-touch-icon.png" rel="apple-touch-icon">
    </head>
	{include file ='admin/menu.tpl'}
                    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                        <div class="d-flex flex-column flex-column-fluid mt-10">
                            <div id="kt_app_content" class="app-content flex-column-fluid">
                                <div id="kt_app_content_container" class="app-container container-xxl">
                                    <div class="card mb-5">
                                        <div class="card-header card-flush">
                                            <div class="card-title fw-bold">节点配置</div>
                                            <div class="card-toolbar">
                                                <button class="btn btn-sm btn-primary fw-bold" onclick="zeroAdminCreateNode()">创建节点</button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div id="custom_config"></div>
                                        </div>
                                    </div>
                                    <div class="row g-5">
                                        <div class="col-xxl-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <label class="form-label required">节点名称</label>
                                                    <input class="form-control mb-5" id="name" name="name" type="text" placeholder="节点名称" value="">
                                                    <label class="form-label required">节点地址</label>
                                                    <input class="form-control mb-5" data-bs-toggle="tooltip" title="填写域名,节点IP会自动设置解析的IP" id="server" name="server" type="text" placeholder="节点地址" value="">
                                                    <label class="form-label required">节点IP</label>
                                                    <input class="form-control mb-5" id="node_ip" name="node_ip" type="text" placeholder="节点IP" value="">
                                                    <label class="form-label">流量比例</label>
                                                    <input class="form-control mb-5" id="traffic_rate" name="traffic_rate" type="text" placeholder="流量比例" value="1">
                                                    <label class="form-label required">节点旗帜</label>
                                                    <select class="form-select mb-5" id="node_flag">
                                                        <option value="united-states" data-kt-select2-country="/theme/zero/assets/media/flags/united-states.svg">美国</option>
                                                        <option value="united-kingdom" data-kt-select2-country="/theme/zero/assets/media/flags/united-kingdom.svg">英国</option>
                                                        <option value="canada" data-kt-select2-country="/theme/zero/assets/media/flags/canada.svg">加拿大</option>
                                                        <option value="russia" data-kt-select2-country="/theme/zero/assets/media/flags/russia.svg">俄罗斯</option>
                                                        <option value="hong-kong" data-kt-select2-country="/theme/zero/assets/media/flags/hong-kong.svg">香港</option>
                                                        <option value="japan" data-kt-select2-country="/theme/zero/assets/media/flags/japan.svg">日本</option>
                                                        <option value="singapore" data-kt-select2-country="/theme/zero/assets/media/flags/singapore.svg">新加坡</option>
                                                        <option value="south-korea" data-kt-select2-country="/theme/zero/assets/media/flags/south-korea.svg">韩国</option>
                                                        <option value="australia" data-kt-select2-country="/theme/zero/assets/media/flags/australia.svg"}>澳大利亚</option>
                                                        <option value="turkey" data-kt-select2-country="/theme/zero/assets/media/flags/turkey.svg">土耳其</option>
                                                        <option value="argentina" data-kt-select2-country="/theme/zero/assets/media/flags/argentina.svg">阿根廷</option>
                                                        <option value="brazil" data-kt-select2-country="/theme/zero/assets/media/flags/brazil.svg">巴西</option>
                                                        <option value="germany" data-kt-select2-country="/theme/zero/assets/media/flags/germany.svg">德国</option>
                                                        <option value="france" data-kt-select2-country="/theme/zero/assets/media/flags/france.svg">法国</option>
                                                        <option value="ireland" data-kt-select2-country="/theme/zero/assets/media/flags/ireland.svg">爱尔兰</option>
                                                        <option value="thailand" data-kt-select2-country="/theme/zero/assets/media/flags/thailand.svg">泰国</option>
                                                        <option value="philippines" data-kt-select2-country="/theme/zero/assets/media/flags/philippines.svg">菲律宾</option>
                                                        <option value="malaysia" data-kt-select2-country="/theme/zero/assets/media/flags/malaysia.svg">马来西亚</option>
                                                    </select>
                                                    <label class="form-label required">节点类型</label>
                                                    <select class="form-select mb-5" id="sort" data-control="select2" data-hide-search="true">
                                                        <option value="0">Shadowsocks</option>
                                                        <option value="11">VMESS</option>
                                                        <option value="13">Shadowsocks V2Ray-Plugin&Obfs</option>
                                                        <option value="14">TROJAN</option>
                                                        <option value="15">VLESS</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <label class="form-label">节点等级</label>
                                                    <input class="form-control mb-5" id="node_class" name="node_class" type="number" placeholder="节点等级" value="0">
                                                    <label class="form-label">节点群组</label>
                                                    <input class="form-control mb-5" data-bs-toggle="tooltip" title="0为不分组" id="node_group" name="node_group" type="number" placeholder="节点群组" value="0">
                                                    <label class="form-label">流量上限(GB)</label>
                                                    <input class="form-control mb-5" data-bs-toggle="tooltip" title="0为不限制" id="node_bandwidth_limit" name="node_bandwidth_limit" type="text" value="0" placeholder="流量上限">
                                                    <label class="form-label">流量上限清空日</label>
                                                    <input class="form-control mb-5" id="bandwidthlimit_resetday" name="bandwidthlimit_resetday" type="text" value="1" placeholder="流量上限清空日">
                                                    <label class="form-label">节点速度</label>
                                                    <input class="form-control mb-5" data-bs-toggle="tooltip" title="0为不限制" id="node_speedlimit" name="node_speedlimit" type="text" value="0" placeholder="节点速度">
                                                    <label class="form-label required">节点排序</label>
                                                    <input class="form-control mb-5" data-bs-toggle="tooltip" title="数值越大,越靠前" id="node_sort" name="node_sort" type="text" value="0" placeholder="节点排序">
                                                </div>
                                            </div>  
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
            const container = document.getElementById('custom_config');
            var options = {
                mode: 'text',
                modes: ['code', 'form', 'text', 'tree', 'view', 'preview'], // allowed modes
                onModeChange: function (newMode, oldMode) {
                    console.log('Mode switched from', oldMode, 'to', newMode)
                }
            };
            const editor = new JSONEditor(container, options);
        </script>
        <script>
            function zeroAdminCreateNode() {
                $.ajax({
                    type: "POST",
                    url: "/admin/node/create",
                    dataType: "JSON",
                    data: {
                        name: $('#name').val(),
                        server: $('#server').val(),
                        node_ip: $('#node_ip').val(),
                        traffic_rate: $('#traffic_rate').val(),
                        flag: $('#node_flag').val(),
                        sort: $('#sort').val(),
                        node_class: $('#node_class').val(),
                        node_group: $('#node_group').val(),
                        node_bandwidth_limit: $('#node_bandwidth_limit').val(),
                        bandwidthlimit_resetday: $('#bandwidthlimit_resetday').val(),
                        node_speedlimit: $('#node_speedlimit').val(),
                        node_sort: $('#node_sort').val(),
                        custom_config: editor.get(),
                    },
                    success: function(data){
                        if(data.ret === 1) {
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
                });
            }
        </script>
        <script>
            // Format options
            var optionFormat = function(item) {
                if ( !item.id ) {
                    return item.text;
                }
            
                var span = document.createElement('span');
                var imgUrl = item.element.getAttribute('data-kt-select2-country');
                var template = '';
            
                template += '<img src="' + imgUrl + '" class="rounded-circle h-20px me-2" alt="image"/>';
                template += item.text;
            
                span.innerHTML = template;
            
                return $(span);
            }
            
            // Init Select2 --- more info: https://select2.org/
            $('#node_flag').select2({
                templateSelection: optionFormat,
                templateResult: optionFormat
            });
        </script>
    </body>
</html>