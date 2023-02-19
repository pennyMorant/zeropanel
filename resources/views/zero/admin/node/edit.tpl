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
                                                <button class="btn btn-sm btn-primary fw-bold" onclick="zeroAdminUpdateNode('{$node->id}')">保存节点</button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div id="custom_config"></div>
                                        </div>
                                    </div>
                                    <div class="row g-5">
                                        <div class="col-xxl-6">
                                            <div class="card card-stretch">
                                                <div class="card-body">
                                                    <label class="form-label required">节点名称</label>
                                                    <input class="form-control mb-5" id="name" name="name" type="text" placeholder="节点名称" value="{$node->name}">
                                                    <label class="form-label required">节点地址</label>
                                                    <input class="form-control mb-5" data-bs-toggle="tooltip" title="填写域名,节点IP会自动设置解析的IP" id="server" name="server" type="text" placeholder="节点地址" value="{$node->server}">
                                                    <label class="form-label required">节点IP</label>
                                                    <input class="form-control mb-5" id="node_ip" name="node_ip" type="text" placeholder="节点IP" value="{$node->node_ip}">
                                                    <label class="form-label">流量比例</label>
                                                    <input class="form-control mb-5" id="traffic_rate" name="traffic_rate" type="text" placeholder="流量比例" value="{$node->traffic_rate}">
                                                    <label class="form-label required">节点旗帜</label>
                                                    <select class="form-select mb-5" id="node_flag">
                                                        <option value="us" data-kt-select2-country="/theme/zero/assets/media/flags/united-states.svg" {if $node->flag == 'us'}selected{/if}>美国</option>
                                                        <option value="gb" data-kt-select2-country="/theme/zero/assets/media/flags/united-kingdom.svg" {if $node->flag == 'uk'}selected{/if}>英国</option>
                                                        <option value="ca" data-kt-select2-country="/theme/zero/assets/media/flags/canada.svg" {if $node->flag == 'ca'}selected{/if}>加拿大</option>
                                                        <option value="rus" data-kt-select2-country="/theme/zero/assets/media/flags/russia.svg" {if $node->flag == 'rus'}selected{/if}>俄罗斯</option>
                                                        <option value="hk" data-kt-select2-country="/theme/zero/assets/media/flags/hong-kong.svg" {if $node->flag == 'hk'}selected{/if}>香港</option>
                                                        <option value="jp" data-kt-select2-country="/theme/zero/assets/media/flags/japan.svg" {if $node->flag == 'jp'}selected{/if}>日本</option>
                                                        <option value="sg" data-kt-select2-country="/theme/zero/assets/media/flags/singapore.svg" {if $node->flag == 'sg'}selected{/if}>新加坡</option>
                                                        <option value="kr" data-kt-select2-country="/theme/zero/assets/media/flags/south-korea.svg" {if $node->flag == 'kr'}selected{/if}>韩国</option>
                                                        <option value="au" data-kt-select2-country="/theme/zero/assets/media/flags/australia.svg" {if $node->flag == 'au'}selected{/if}>澳大利亚</option>
                                                        <option value="tr" data-kt-select2-country="/theme/zero/assets/media/flags/turkey.svg" {if $node->flag == 'tr'}selected{/if}>土耳其</option>
                                                        <option value="arg" data-kt-select2-country="/theme/zero/assets/media/flags/argentina.svg" {if $node->flag == 'arg'}selected{/if}>阿根廷</option>
                                                        <option value="br" data-kt-select2-country="/theme/zero/assets/media/flags/brazil.svg" {if $node->flag == 'br'}selected{/if}>巴西</option>
                                                        <option value="de" data-kt-select2-country="/theme/zero/assets/media/flags/germany.svg" {if $node->flag == 'de'}selected{/if}>德国</option>
                                                        <option value="fr" data-kt-select2-country="/theme/zero/assets/media/flags/france.svg" {if $node->flag == 'fr'}selected{/if}>法国</option>
                                                        <option value="irl" data-kt-select2-country="/theme/zero/assets/media/flags/ireland.svg" {if $node->flag == 'irl'}selected{/if}>爱尔兰</option>
                                                        <option value="th" data-kt-select2-country="/theme/zero/assets/media/flags/thailand.svg" {if $node->flag == 'th'}selected{/if}>泰国</option>
                                                        <option value="phi" data-kt-select2-country="/theme/zero/assets/media/flags/philippines.svg" {if $node->flag == 'phi'}selected{/if}>菲律宾</option>
                                                        <option value="my" data-kt-select2-country="/theme/zero/assets/media/flags/malaysia.svg" {if $node->flag == 'my'}selected{/if}>马来西亚</option>
                                                    </select>
                                                    <label class="form-label required">节点类型</label>
                                                    <select class="form-select mb-5" id="sort" data-control="select2" data-hide-search="true">
                                                        <option value="0" {if $node->sort==0}selected{/if}>Shadowsocks</option>
                                                        <option value="11" {if $node->sort==11}selected{/if}>VMESS</option>
                                                        <option value="13" {if $node->sort==13}selected{/if}>Shadowsocks V2Ray-Plugin&Obfs</option>
                                                        <option value="14" {if $node->sort==14}selected{/if}>TROJAN</option>
                                                        <option value="15" {if $node->sort==15}selected{/if}>VLESS</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <label class="form-label">节点等级</label>
                                                    <input class="form-control mb-5" id="node_class" name="node_class" type="number" placeholder="节点等级" value="{$node->node_class}">
                                                    <label class="form-label">节点群组</label>
                                                    <input class="form-control mb-5" data-bs-toggle="tooltip" title="0为不分组" id="node_group" name="node_group" type="number" placeholder="节点群组" value="{$node->node_group}">
                                                    <label class="form-label">流量上限(GB)</label>
                                                    <input class="form-control mb-5" data-bs-toggle="tooltip" title="0为不限制" id="node_bandwidth_limit" name="node_bandwidth_limit" type="text" value="{$node->node_bandwidth_limit/1024/1024/1024}" placeholder="流量上限">
                                                    <label class="form-label">流量上限清空日</label>
                                                    <input class="form-control mb-5" id="bandwidthlimit_resetday" name="bandwidthlimit_resetday" type="text" value="{$node->bandwidthlimit_resetday}" placeholder="流量上限清空日">
                                                    <label class="form-label">节点速度</label>
                                                    <input class="form-control mb-5" data-bs-toggle="tooltip" title="0为不限制" id="node_speedlimit" name="node_speedlimit" type="text" value="{$node->node_speedlimit}" placeholder="节点速度">
                                                    <label class="form-label required">节点排序</label>
                                                    <input class="form-control mb-5" data-bs-toggle="tooltip" title="数值越大,越靠前" id="node_sort" name="node_sort" type="text" value="{$node->node_sort}" placeholder="节点排序">
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
    </body>
    <script>
    const container = document.getElementById('custom_config');
    var options = {
        mode: 'tree',
        modes: ['code', 'form', 'text', 'tree', 'view', 'preview'], // allowed modes
        onModeChange: function (newMode, oldMode) {
            console.log('Mode switched from', oldMode, 'to', newMode)
        }
    };
    const editor = new JSONEditor(container, options);
    editor.set({$node->custom_config})
    </script>
    <script>
        function zeroAdminUpdateNode(id) {
            $.ajax({
                type: "PUT",
                url: "/admin/node/update",
                dataType: "JSON",
                data: {
                    id,
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
</html>