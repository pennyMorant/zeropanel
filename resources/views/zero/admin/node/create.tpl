<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{$config["website_name"]} 创建节点</title>
        
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
        <link href="https://cdnjs.cloudflare.com/ajax/libs/jsoneditor/9.10.2/jsoneditor.min.css" rel="stylesheet" type="text/css">
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
                                            <div class="card-title fw-bolder">节点配置</div>
                                            <div class="card-toolbar">
                                                <button class="btn btn-sm btn-primary fw-bold me-3" data-bs-toggle="modal" data-bs-target="#zero_modal_node_config_template">配置模板</button>
                                                <button class="btn btn-sm btn-primary fw-bold" onclick="zeroAdminCreateNode()">
                                                <i class="bi bi-cloud-plus fs-3"></i>创建节点
                                                </button>
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
                                                    <label class="form-label required" for="name">节点名称</label>
                                                    <input class="form-control mb-5" id="name" name="name" type="text" placeholder="节点名称" value="">
                                                    <label class="form-label required" for="server">节点地址</label>
                                                    <input class="form-control mb-5" data-bs-toggle="tooltip" title="填写域名,节点IP会自动设置解析的IP" id="server" name="server" type="text" placeholder="节点地址" value="">
                                                    <label class="form-label required" for="node_ip">节点IP</label>
                                                    <input class="form-control mb-5" id="node_ip" name="node_ip" type="text" placeholder="节点IP" value="">
                                                    <label class="form-label required" for="traffic_rate">流量比例</label>
                                                    <input class="form-control mb-5" id="traffic_rate" name="traffic_rate" type="text" placeholder="流量比例" value="1">
                                                    <label class="form-label required" for="node_flag">节点旗帜</label>
                                                    <select class="form-select mb-5" id="node_flag">
                                                        <option value="united-states" data-kt-select2-country="/theme/zero/assets/media/flags/united-states.svg">美国</option>
                                                        <option value="united-kingdom" data-kt-select2-country="/theme/zero/assets/media/flags/united-kingdom.svg">英国</option>
                                                        <option value="canada" data-kt-select2-country="/theme/zero/assets/media/flags/canada.svg">加拿大</option>
                                                        <option value="russia" data-kt-select2-country="/theme/zero/assets/media/flags/russia.svg">俄罗斯</option>
                                                        <option value="hong-kong" data-kt-select2-country="/theme/zero/assets/media/flags/hong-kong.svg">香港</option>
                                                        <option value="taiwan" data-kt-select2-country="/theme/zero/assets/media/flags/taiwan.svg">台湾</option>
                                                        <option value="japan" data-kt-select2-country="/theme/zero/assets/media/flags/japan.svg">日本</option>
                                                        <option value="singapore" data-kt-select2-country="/theme/zero/assets/media/flags/singapore.svg">新加坡</option>
                                                        <option value="south-korea" data-kt-select2-country="/theme/zero/assets/media/flags/south-korea.svg">韩国</option>
                                                        <option value="australia" data-kt-select2-country="/theme/zero/assets/media/flags/australia.svg">澳大利亚</option>
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
                                                    <label class="form-label required" for="node_type">节点类型</label>
                                                    <select class="form-select mb-5" id="node_type" data-control="select2" data-hide-search="true">
                                                        <option value="1">Shadowsocks</option>
                                                        <option value="2">VMESS</option>
                                                        <option value="3">VLESS</option>
                                                        <option value="4">TROJAN</option>
                                                        <option value="5">Hysteria</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <label class="form-label required" for="node_class">节点等级</label>
                                                    <input class="form-control mb-5" id="node_class" name="node_class" type="number" placeholder="节点等级" value="0">
                                                    <label class="form-label required" for="node_group">节点群组</label>
                                                    <input class="form-control mb-5" data-bs-toggle="tooltip" title="0为不分组" id="node_group" name="node_group" type="number" placeholder="节点群组" value="0">
                                                    <label class="form-label required" for="node_traffic_limit">流量上限</label>
                                                    <div class="input-group mb-5">
                                                        <input class="form-control" data-bs-toggle="tooltip" title="0为不限制" id="node_traffic_limit" name="node_traffic_limit" type="text" value="0" placeholder="流量上限">
                                                        <span class="input-group-text">GB</span>
                                                    </div>
                                                    <label class="form-label required" for="node_traffic_limit_reset_date">流量上限清空日</label>
                                                    <input class="form-control mb-5" id="node_traffic_limit_reset_date" name="node_traffic_limit_reset_date" type="text" value="1" placeholder="流量上限清空日">
                                                    <label class="form-label required" for="node_speedlimit">节点速度</label>
                                                    <div class="input-group mb-5">
                                                        <input class="form-control" data-bs-toggle="tooltip" title="0为不限制" id="node_speedlimit" name="node_speedlimit" type="text" value="0" placeholder="节点速度">
                                                        <span class="input-group-text">Mbps</span>
                                                    </div>
                                                    <label class="form-label required" for="node_sort">节点排序</label>
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
        <!-- modal -->
        <div class="modal fade" id="zero_modal_node_config_template" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded">
                    <div class="modal-header justify-content-end border-0 pb-0">
                        <a class="btn btn-sm btn-light-primary" id="zero_modal_use_selected_template">使用此模板</a>
                    </div>
                    <div class="modal-body scroll-y pt-0 pb-5 px-5">
                        <div class="mb-5 text-center">
                            <h3 class="mb-3">配置模板</h3>
                        </div>
                        <div class="mb-5 hover-scroll-x">
                            <div class="d-grid">
                                <ul class="nav nav-tabs flex-nowrap text-nowrap" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link btn btn-active-light btn-color-gray-600 btn-active-color-primary rounded-bottom-0 active" data-bs-toggle="tab" data-bs-target="#ss">SS</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link btn btn-active-light btn-color-gray-600 btn-active-color-primary rounded-bottom-0" data-bs-toggle="tab" data-bs-target="#ss_2022">SS-2022</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link btn btn-active-light btn-color-gray-600 btn-active-color-primary rounded-bottom-0" data-bs-toggle="tab" data-bs-target="#vmess_tcp">vmess+tcp</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link btn btn-active-light btn-color-gray-600 btn-active-color-primary rounded-bottom-0" data-bs-toggle="tab" data-bs-target="#vmess_ws">vmess+ws</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link btn btn-active-light btn-color-gray-600 btn-active-color-primary rounded-bottom-0" data-bs-toggle="tab" data-bs-target="#vmess_tcp_tls">vmess+tcp+tls</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link btn btn-active-light btn-color-gray-600 btn-active-color-primary rounded-bottom-0" data-bs-toggle="tab" data-bs-target="#vless_tcp_reality">vless+tcp+reality</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link btn btn-active-light btn-color-gray-600 btn-active-color-primary rounded-bottom-0" data-bs-toggle="tab" data-bs-target="#trojan">trojan</a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="tab-content" id="zero_modal_node_template_content">
                            <div class="tab-pane fade show active" id="ss" role="tabpanel">
                                <pre>
{
    "ss_encryption": "aes-256-gcm",
    "offset_port_user": "30011",
    "offset_port_node": "30011"
}
                                </pre>
                            </div>
                            <div class="tab-pane fade" id="ss_2022" role="tabpanel">
                                <pre>
{
    "ss_encryption": "2022-blake3-aes-256-gcm",
    "server_psk": "",
    "offset_port_user": "30011",
    "offset_port_node": "30011"
}
                                </pre>
                            </div>
                            <div class="tab-pane fade" id="vmess_tcp" role="tabpanel">
                                <pre>
{
    "offset_port_user": "20022",
    "offset_port_node": "20022",
    "network": "tcp"
}                              
                                </pre>
                            </div>
                            <div class="tab-pane fade" id="vmess_ws" role="tabpanel">
                                <pre>
{
    "offset_port_user": "20022",
    "offset_port_node": "20022",
    "network": "ws",
    "host": "microsoft.com",
    "path": "/ufdsl900"
}                             
                                </pre>
                            </div>
                            <div class="tab-pane fade" id="vmess_tcp_tls" role="tabpanel">
                                <pre>
{
    "offset_port_user": "443",
    "offset_port_node": "443",
    "network": "tcp",
    "security": "tls",
    "host": "bing.com"
}                           
                                </pre>
                            </div>
                            <div class="tab-pane fade" id="vless_tcp_reality" role="tabpanel">
                                <pre>
{
    "offset_port_user": "20022",
    "offset_port_node": "20022",
    "network": "tcp",
    "security": "reality",
    "reality_config": {
        "show": "false",
        "dest": "dl.google.com:443",
        "proxy_protocol_ver": "0",
        "min_client_ver": "",
        "max_client_ver": "",
        "max_time_diff": "0",
        "short_ids": [
        "",
        "0123456789abcdef"
        ],
        "fingerprint": "chrome",
        "public_key": "",
        "private_key": "",
        "server_names": [
        "bing.com",
        "dl.google.com"
        ],
        "flow": "xtls-rprx-vision"
    }
}                         
                                </pre>
                            </div>
                            <div class="tab-pane fade" id="trojan" role="tabpanel">
                                <pre>
{
    "offset_port_user": "443",
    "offset_port_node": "443",
    "network": "tcp",
    "host": "bing.com",
    "security": "tls",
    "insecure": "true"
}                           
                                </pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {include file='admin/script.tpl'}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jsoneditor/9.10.2/jsoneditor.min.js"></script>
        <script src="/js/sodium.js" async></script>
        <script>
            function generateX25519Keys() {
                const privateKey = sodium.crypto_box_keypair().privateKey;
                const publicKey = sodium.crypto_box_keypair().publicKey;

                const privateKeyBase64 = sodium.to_base64(privateKey, sodium.base64_variants.URLSAFE_NO_PADDING);
                const publicKeyBase64 = sodium.to_base64(publicKey, sodium.base64_variants.URLSAFE_NO_PADDING);
                const keys = {
                    'public_key': publicKeyBase64,
                    'private_key': privateKeyBase64
                }
                console.log(keys);
                return keys;
            }
            function generateBase64Random() {
                const randomValues = new Uint8Array(32);
                window.crypto.getRandomValues(randomValues);
                const base64String = btoa(String.fromCharCode(...randomValues));
                return base64String;
            }
            const container = document.getElementById('custom_config');
            var options = {
                mode: 'text',
                modes: ['code', 'form', 'text', 'tree', 'view', 'preview'], // allowed modes
                onModeChange: function (newMode, oldMode) {
                    console.log('Mode switched from', oldMode, 'to', newMode)
                }
            };
            var editor = new JSONEditor(container, options);
            
            $('#zero_modal_use_selected_template').on('click', function(){
                const template = $('#zero_modal_node_template_content div.active pre').html();
                const jsonObj = JSON.parse(template);
                
                if ('reality_config' in jsonObj) {
                    jsonObj['reality_config'].private_key = generateX25519Keys().private_key;
                    jsonObj['reality_config'].public_key = generateX25519Keys().public_key;
                }
                if ('server_psk' in jsonObj) {
                    jsonObj.server_psk = generateBase64Random();
                }
                editor.set(jsonObj);
            });
        </script>
        <script>
            function zeroAdminCreateNode() {
                $.ajax({
                    type: "POST",
                    url: "/{$config['website_admin_path']}/node/create",
                    dataType: "JSON",
                    data: {
                        name: $('#name').val(),
                        server: $('#server').val(),
                        node_ip: $('#node_ip').val(),
                        traffic_rate: $('#traffic_rate').val(),
                        node_flag: $('#node_flag').val(),
                        node_type: $('#node_type').val(),
                        node_class: $('#node_class').val(),
                        node_group: $('#node_group').val(),
                        node_traffic_limit: $('#node_traffic_limit').val(),
                        node_traffic_limit_reset_date: $('#node_traffic_limit_reset_date').val(),
                        node_speedlimit: $('#node_speedlimit').val(),
                        node_sort: $('#node_sort').val(),
                        custom_config: Object.keys(editor.get()).length <= 0 ? '' : editor.get(),
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