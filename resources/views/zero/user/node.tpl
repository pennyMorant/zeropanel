<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{$config["appName"]} Node</title>
        
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
    {include file ='include/index/menu.tpl'}                
					<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
						<div class="d-flex flex-column flex-column-fluid mt-10">
                            <div id="kt_app_content" class="app-content flex-column-fluid">
                                <div id="kt_app_content_container" class="app-container container-xxl">
                                    <div class="card" id="kt_pricing">
                                        <div class="card-body p-lg-17">
                                            <div class="d-flex flex-column">
                                                
                                                <div class="nav-group nav-group-outline mx-auto mb-15 nav" data-kt-buttons="true">
                                                    {foreach $class as $grade}
                                                        {assign var="node_permission" value=$permission_group[$grade['node_class']]|default: "unknown"}
                                                        <button class="btn btn-color-gray-400 btn-active btn-active-secondary px-6 py-3 me-2 {if $grade['node_class'] == $min_node_class}active{/if}" data-bs-toggle="tab" data-bs-target="#node_show_{$grade['node_class']}">{$node_permission}</button>
                                                    {/foreach}
                                                </div>
                                                      
												<div class="tab-content">
                                                    {foreach $class as $grade}
													<div class="tab-pane fade show {if $grade['node_class'] == $min_node_class}active show{/if}" id="node_show_{$grade['node_class']}">
														<div class="row g-10">
                                                            {foreach $servers as $server} 
                                                            {if $server->node_class == $grade['node_class']}                     
															<div class="col-xl-4">
																<div class="d-flex h-100 align-items-center flex-wrap" type="button" onclick="KTUsersShowNodeInfo({$server['id']}, {$user->class}, {$server['node_class']})">
																	<div class="w-100 rounded-3 bg-light bg-opacity-75 px-10 py-5 d-flex flex-wrap">                                                      
                                                                        <div class="d-flex flex-column flex-grow-1">
                                                                            <img alt="image" class="rounded-circle" width="35"
                                                                                src="/theme/zero/assets/media/flags/{$server['flag']}.svg">
                                                                        </div>
                                                                        <div class="fw-bold fs-5">
                                                                            {$server['name']}
                                                                        </div>
                                                                        
																	</div>
																</div>
															</div>
                                                            {/if}
                                                            {/foreach}
														</div>
													</div>
                                                    {/foreach}
												</div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="app_footer py-4 d-flex flex-lg-column" id="kt_app_footer">
                            <div class="app-container container-fluid d-flex flex-column flex-md-row flex-center flex-md-stack py-3">
                                <div class="text-dark-75 order-2 order-md-1">
                                    &copy;<script>document.write(new Date().getFullYear());</script>,&nbsp;<a>{$config["appName"]},&nbsp;Inc.&nbsp;All rights reserved.</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {include file='include/index/news.tpl'}
		<!-- vmess modal -->
        <div class="modal fade" id="zero_modal_vmess_node_info" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="zero_modal_vmess_node_info_title" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content shadow-lg">
                    <div class="modal-header">
                        <h4 class="modal-title">
						<strong id="zero_modal_vmess_node_info_remark">{$trans->t('node name')}</strong></h4>
                    </div>
                    <div class="modal-body align-items-center" id="zero_modal_vmess_node_info_body">
						<nav class="nav nav-tabs nav-justified" role="tablist">
							<button class="nav-link active" type="button" data-bs-toggle="tab" aria-selected="true" data-bs-target="#zero_modal_tab_vmess_qrcode">
								{$trans->t('qrcode')}
							</button>
							<button class="nav-link" type="button" data-bs-toggle="tab" aria-selected="false" data-bs-target="#zero_modal_tab_vmess_config">
								{$trans->t('config')}
							</button>
                        </nav>
                        <div class="tab-content m-0 p-0">
                            <div class="tab-pane fade active show" id="zero_modal_tab_vmess_qrcode">
                                
                                    <div class="text-center pt-10" id="zero_modal_vmess_node_info_qrcode">
                                    </div>
                                
                            </div>
                            <div class="tab-pane fade show" id="zero_modal_tab_vmess_config">
								<div class="pt-10 pl-10 ms-10 text-start fs-4">
                                    <p>{$trans->t('address')}: <span class="badge badge-secondary badge-lg" id="zero_modal_vmess_node_info_address"></span></p>
                                    <p>{$trans->t('port')}: <span class="badge badge-secondary badge-lg" id="zero_modal_vmess_node_info_port"></span></p>
                                    <p>{$trans->t('alter id')}: <span class="badge badge-secondary badge-lg" id="zero_modal_vmess_node_info_aid"></span></p>
                                    <p>{$trans->t('uuid')}: <span class="badge badge-secondary badge-lg" id="zero_modal_vmess_node_info_uuid"></span></p>
                                    <p>{$trans->t('network')}: <span class="badge badge-secondary badge-lg" id="zero_modal_vmess_node_info_net"></span></p>
                                    <p>{$trans->t('path')}: <span class="badge badge-secondary badge-lg" id="zero_modal_vmess_node_info_path"></span></p>
									<p>{$trans->t('host')}: <span class="badge badge-secondary badge-lg" id="zero_modal_vmess_node_info_host"></span></p>
                                    <p>{$trans->t('grpc servicename')}: <span class="badge badge-secondary badge-lg" id="zero_modal_vmess_node_info_servicename"></span></p>
                                    <p>{$trans->t('protocol')}: <span class="badge badge-secondary badge-lg" id="zero_modal_vmess_node_info_type"></span></p>
                                    <p>{$trans->t('security')}: <span class="badge badge-secondary badge-lg" id="zero_modal_vmess_node_info_security"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">{$trans->t('discard')}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- vless modal -->
        <div class="modal fade" id="zero_modal_vless_node_info" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="zero_modal_vless_node_info_title" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content shadow-lg">
                    <div class="modal-header">
                        <h4 class="modal-title">
						<strong id="zero_modal_vless_node_info_remark">{$trans->t('node name')}</strong></h4>
                    </div>
                    <div class="modal-body align-items-center" id="zero_modal_vless_node_info_body">
						<nav class="nav nav-tabs nav-justified" role="tablist">
							<button class="nav-link active" type="button" data-bs-toggle="tab" aria-selected="true" data-bs-target="#zero_modal_tab_vless_qrcode">
								{$trans->t('qrcode')}
							</button>
							<button class="nav-link" type="button" data-bs-toggle="tab" aria-selected="false" data-bs-target="#zero_modal_tab_vless_config">
								{$trans->t('config')}
							</button>
                        </nav>
                        <div class="tab-content m-0 p-0">
                            <div class="tab-pane fade active show" id="zero_modal_tab_vless_qrcode">
                                
                                    <div class="text-center pt-10" id="zero_modal_vless_node_info_qrcode">
                                    </div>
                                
                            </div>
                            <div class="tab-pane fade show" id="zero_modal_tab_vless_config">
								<div class="pt-10 pl-10 ms-10 text-start fs-4">
                                    <p>{$trans->t('address')}: <span class="badge badge-secondary badge-lg" id="zero_modal_vless_node_info_address"></span></p>
                                    <p>{$trans->t('port')}: <span class="badge badge-secondary badge-lg" id="zero_modal_vless_node_info_port"></span></p>
                                    <p>{$trans->t('uuid')}: <span class="badge badge-secondary badge-lg" id="zero_modal_vless_node_info_uuid"></span></p>
                                    <p>{$trans->t('network')}: <span class="badge badge-secondary badge-lg" id="zero_modal_vless_node_info_net"></span></p>
                                    <p>{$trans->t('path')}: <span class="badge badge-secondary badge-lg" id="zero_modal_vless_node_info_path"></span></p>
									<p>{$trans->t('host')}: <span class="badge badge-secondary badge-lg" id="zero_modal_vless_node_info_host"></span></p>
                                    <p>{$trans->t('grpc servicename')}: <span class="badge badge-secondary badge-lg" id="zero_modal_vless_node_info_servicename"></span></p>
                                    <p>{$trans->t('protocol')}: <span class="badge badge-secondary badge-lg" id="zero_modal_vless_node_info_type"></span></p>
                                    <p>{$trans->t('security')}: <span class="badge badge-secondary badge-lg" id="zero_modal_vless_node_info_security"></span></p>
                                    <p>{$trans->t('flow')}: <span class="badge badge-secondary badge-lg" id="zero_modal_vless_node_info_flow"></span></p>
                                    <p>{$trans->t('sni')}: <span class="badge badge-secondary badge-lg" id="zero_modal_vless_node_info_sni"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">{$trans->t('discard')}
                        </button>
                    </div>
                </div>
            </div>
        </div>
		<!-- ss modal -->
		<div class="modal fade" id="zero_modal_shadowsocks_node_info" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="zero_modal_vmess_node_info_title" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content shadow-lg">
                    <div class="modal-header">
                        <h4 class="modal-title">
						<strong id="zero_modal_shadowsocks_node_info_remark">{$trans->t('node name')}</strong></h4>
                    </div>
                    <div class="modal-body align-items-center" id="zero_modal_shadowsocks_node_info_body">
                        <nav class="nav nav-tabs nav-justified" role="tablist">
							<button class="nav-link active" type="button" data-bs-toggle="tab" aria-selected="true" data-bs-target="#zero_modal_tab_shadowsocks_qrcode">
								{$trans->t('qrcode')}
							</button>
							<button class="nav-link" type="button" data-bs-toggle="tab" aria-selected="false" data-bs-target="#zero_modal_tab_shadowsocks_config">
								{$trans->t('config')}
							</button>
                        </nav>
                        <div class="tab-content m-0 p-0">
                            <div class="tab-pane fade active show" id="zero_modal_tab_shadowsocks_qrcode">
                                
                                    <div class="text-center pt-10" id="zero_modal_shadowsocks_node_info_qrcode">
                                    </div>
                                
                            </div>
                            <div class="tab-pane fade show" id="zero_modal_tab_shadowsocks_config">
                                <div class="pt-10 pl-10 ms-10 text-start fs-4">
                                    <p>{$trans->t('address')}: <span class="badge badge-secondary badge-lg" id="zero_modal_shadowsocks_node_info_address"></span></p>
                                    <p>{$trans->t('port')}: <span class="badge badge-secondary badge-lg" id="zero_modal_shadowsocks_node_info_port"></span></p>
									<p>{$trans->t('encrypt')}: <span class="badge badge-secondary badge-lg" id="zero_modal_shadowsocks_node_info_method"></span></p>
                                    <p>{$trans->t('passwd')}: <span class="badge badge-secondary badge-lg" id="zero_modal_shadowsocks_node_info_passwd"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">{$trans->t('discard')}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- trojan modal -->
		<div class="modal fade" id="zero_modal_trojan_node_info" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="zero_modal_vmess_node_info_title" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content shadow-lg">
                    <div class="modal-header">
                        <h4 class="modal-title">
						<strong id="zero_modal_trojan_node_info_remark">{$trans->t('node name')}</strong></h4>
                    </div>
                    <div class="modal-body align-items-center" id="zero_modal_trojan_node_info_body">
                        <nav class="nav nav-tabs nav-justified" role="tablist">
							<button class="nav-link active" type="button" data-bs-toggle="tab" aria-selected="true" data-bs-target="#zero_modal_tab_trojan_qrcode">
								{$trans->t('qrcode')}
							</button>
							<button class="nav-link" type="button" data-bs-toggle="tab" aria-selected="false" data-bs-target="#zero_modal_tab_trojan_config">
								{$trans->t('config')}
							</button>
                        </nav>
                        <div class="tab-content m-0 p-0">
                            <div class="tab-pane fade active show" id="zero_modal_tab_trojan_qrcode">
                                
                                    <div class="text-center pt-10" id="zero_modal_trojan_node_info_qrcode">
                                    </div>
                                
                            </div>
                            <div class="tab-pane fade show" id="zero_modal_tab_trojan_config">
                                <div class="pt-10 pl-10 ms-10 text-start fs-4">
                                    <p>{$trans->t('address')}: <span class="badge badge-secondary badge-lg" id="zero_modal_trojan_node_info_address"></span></p>
                                    <p>{$trans->t('port')}: <span class="badge badge-secondary badge-lg" id="zero_modal_trojan_node_info_port"></span></p>
									<p>{$trans->t('sni')}: <span class="badge badge-secondary badge-lg" id="zero_modal_trojan_node_info_sni"></span></p>
                                    <p>{$trans->t('uuid')}: <span class="badge badge-secondary badge-lg" id="zero_modal_trojan_node_info_uuid"></span></p>
                                    <p>{$trans->t('security')}: <span class="badge badge-secondary badge-lg" id="zero_modal_trojan_node_info_security"></span></p>
                                    <p>{$trans->t('flow')}: <span class="badge badge-secondary badge-lg" id="zero_modal_trojan_node_info_flow"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">{$trans->t('discard')}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {include file='include/global/scripts.tpl'}
        <script src="/js/qrcode.min.js"></script>
    </body>
</html>
                                            
                                                            
                                                                    