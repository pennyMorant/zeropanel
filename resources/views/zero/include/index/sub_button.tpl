											<div class="card card-flush ">
												<div class="card-header border-0">
													<div class="card-title text-dark fw-bolder fs-3">
														
															{$trans->t('user.dashboard.sub_url')}
														
													</div>
												</div>
												<div class="card-body pt-0">
													<div class="row">
														<div class="col">
															{if in_array('clash',$zeroconfig['index_sub'])}
															<!-- Clash订阅 -->
															<div class="btn-group mb-3 mr-3">
																<button type="button" class="btn btn-pill btn-clash dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="zero-clash text-white"></i>&nbsp;&nbsp;{$trans->t('user.dashboard.clash')}&nbsp;&nbsp;</button>
																<ul class="dropdown-menu">
																	<li><a class="dropdown-item copy-text" href="Javascript:;" data-clipboard-text="{$subInfo["clash"]}">{$trans->t('user.dashboard.copy_url')}</a></li>
																	<li><hr class="dropdown-divider"></li>
																	<li><a class="dropdown-item" href="Javascript:;" onclick="oneclickImport('clash', '{$subInfo["clash"]}')">{$trans->t('user.dashboard.import_url')}</a></li>
																</ul>
															</div>
															{/if}
															{if in_array('surge',$zeroconfig['index_sub'])}
															<!-- Surge订阅 -->
															<div class="btn-group mb-3 mr-3">
																<button type="button" class="btn btn-pill btn-surge dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="zero-surge text-white"></i>&nbsp;&nbsp;{$trans->t('user.dashboard.surge')}&nbsp;&nbsp;</button>
																<ul class="dropdown-menu">
																	<li><a class="dropdown-item copy-text" href="Javascript:;" data-clipboard-text="{$subInfo["surge"]}">{$trans->t('user.dashboard.copy_url')}</a></li>
																	<li><hr class="dropdown-divider"></li>
																	<li><a class="dropdown-item" href="Javascript:;" onclick="oneclickImport('surge4', '{$subInfo["surge"]}')">{$trans->t('user.dashboard.import_url')}</a></li>
																</ul>
															</div>
															{/if}
															{if in_array('ss',$zeroconfig['index_sub'])}
															<!-- ss订阅 -->
															<div class="btn-group mb-3 mr-3">
																<button type="button" class="btn btn-pill btn-surfboard copy-text" data-clipboard-text="{$subInfo["ss"]}"><i class="zero-ssr text-white"></i>&nbsp;&nbsp;复制 SS 订阅&nbsp;&nbsp;</button>
															</div>
															{/if}
															{if in_array('v2ray',$zeroconfig['index_sub'])}
															<!-- V2Ray订阅 -->
															<div class="btn-group mb-3 mr-3">
																<button type="button" class="btn btn-pill btn-v2ray copy-text" data-clipboard-text="{$subInfo["v2ray"]}"><i class="zero-v2rayng text-white"></i>{$trans->t('user.dashboard.v2ray')}</button>
															</div>
															{/if}
															{if in_array('shadowrocket',$zeroconfig['index_sub'])}
															<!-- Shadowrocket订阅 -->
															<div class="btn-group mb-3 mr-3">
																<button type="button" class="btn btn-pill btn-shadowrocket dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="zero-shadowrocket text-white"></i>{$trans->t('user.dashboard.shadowrocket')}</button>
																<ul class="dropdown-menu">
																	<li><a class="dropdown-item copy-text" href="Javascript:;" data-clipboard-text="{$subInfo["shadowrocket"]}">{$trans->t('user.dashboard.copy_url')}</a></li>
																	<li><hr class="dropdown-divider"></li>
																	<li><a class="dropdown-item" href="Javascript:;" onclick="oneclickImport('shadowrocket', '{$subInfo["shadowrocket"]}')">{$trans->t('user.dashboard.import_url')}</a></li>
																</ul>
															</div>
															{/if}
															{if in_array('quantumult',$zeroconfig['index_sub'])}
															<!-- Quantumult订阅 -->
															<div class="btn-group mb-3 mr-3">
																<button type="button" class="btn btn-pill btn-quantumult dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="zero-quantumult text-white"></i>{$trans->t('user.dashboard.quan')}</button>
																<ul class="dropdown-menu">
																	<li><a class="dropdown-item copy-text" href="Javascript:;" data-clipboard-text="{$subInfo["quantumult"]}">{$trans->t('user.dashboard.copy_url')}</a></li>
																	<li><hr class="dropdown-divider"></li>
																	<li><a class="dropdown-item" href="Javascript:;" onclick="oneclickImport('quantumult', '{$subInfo["quantumult"]}')">{$trans->t('user.dashboard.import_url')}</a></li>
																</ul>
															</div>
															{/if}
															{if in_array('quantumultx',$zeroconfig['index_sub'])}
															<!-- QuantumultX订阅 -->
															<div class="btn-group mb-3 mr-3">
																<button type="button" class="btn btn-pill btn-quantumultx copy-text" data-clipboard-text="{$subInfo["quantumultx"]}"><i class="zero-quantumultx text-white"></i>{$trans->t('user.dashboard.quanx')}</button>
															</div>
															{/if}
															{if in_array('v2rayvless',$zeroconfig['index_sub'])}
																<!-- V2Ray订阅 -->
																<div class="btn-group mb-3 mr-3">
																	<button type="button" class="btn btn-pill btn-v2ray copy-text" data-clipboard-text="{$subInfo["v2ray_vless"]}"><i class="zero-v2rayng text-white"></i>&nbsp;&nbsp;复制 V2Ray-VLESS 订阅&nbsp;&nbsp;</button>
																</div>
															{/if}
															{if in_array('surfboard',$zeroconfig['index_sub'])}
															<!-- Surfboard订阅 -->
															<div class="btn-group mb-3 mr-3">
																<button type="button" class="btn btn-pill btn-surfboard dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="zero-surfboard text-white"></i>{$trans->t('user.dashboard.surfboard')}</button>
																<ul class="dropdown-menu">
																	<li><a class="dropdown-item copy-text" href="Javascript:;" data-clipboard-text="{$subInfo["surfboard"]}">{$trans->t('user.dashboard.copy_url')}</a></li>
																	<li><hr class="dropdown-divider"></li>
																	<li><a class="dropdown-item" href="Javascript:;" onclick="oneclickImport('surfboard', '{$subInfo["surfboard"]}')">{$trans->t('user.dashboard.import_url')}</a></li>
																</ul>
															</div>
															{/if}
															{if in_array('kitsunebi',$zeroconfig['index_sub'])}
															<!-- Kitsunebi订阅 -->
															<div class="btn-group mb-3 mr-3">
																<button type="button" class="btn btn-pill btn-kitsunebi copy-text" data-clipboard-text="{$subInfo["kitsunebi"]}"><i class="zero-kitsunebi text-white"></i>&nbsp;&nbsp;复制 Kitsunebi 订阅&nbsp;&nbsp;</button>
															</div>
															{/if}
															{if in_array('anxray',$zeroconfig['index_sub'])}
															<!-- AnXray订阅 -->
															<div class="btn-group mb-3 mr-3">
																<button type="button" class="btn btn-pill btn-kitsunebi dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="zero-ssr text-white"></i>&nbsp;&nbsp;{$trans->t('user.dashboard.sagernet')}&nbsp;&nbsp;</button>
																<ul class="dropdown-menu">
																	<li><a class="dropdown-item copy-text" href="Javascript:;" data-clipboard-text="{$subInfo["anxray"]}">{$trans->t('user.dashboard.copy_url')}</a></li>
																	<li><hr class="dropdown-divider"></li>
																	<li><a class="dropdown-item" href="Javascript:;" onclick="oneclickImport('sagernet', '{$subInfo["anxray"]}')">{$trans->t('user.dashboard.import_url')}</a></li>
																</ul>
															</div>
															{/if}
														</div>
													</div>
												</div>
											</div>