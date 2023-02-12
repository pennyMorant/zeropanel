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
    {include file ='include/index/menu.tpl'}                
                    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                        <div class="d-flex flex-column flex-column-fluid mt-10">
                            <div id="kt_app_content" class="app-content flex-column-fluid">
                            	
                                <div id="kt_app_content_container" class="app-container container-xxl">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-header border-bottom">
                                                    <div class="card-title">
                                                    <div class="card-label fs-3 fw-bold">
                                                    {$trans->t('tutorial.android.clash.text_0')}
                                                    </div>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row p-5">
                                                        <div class="col-sm-12 col-md-12 pb-5 text-center fs-4">
                                                            <p class="fs-1 pb-5"><strong>{$trans->t('tutorial.android.clash.text_1')}</strong></p>
                                                            <div class="text-center">
                                                                <p><code>Clash</code>{$trans->t('tutorial.android.clash.text_2')}{$trans->t('tutorial.android.clash.text_3')}</p>
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="separator separator-dashed separator-border"></div>
                                                    <div class="row p-5">
                                                        <div class="col-sm-12 col-md-12 pb-5 text-center fs-4">
                                                            <p class="fs-1 pb-5"><strong>{$trans->t('tutorial.android.clash.text_4')}</strong></p>
                                                            <a href="{$zeroconfig['client_android']['clash']['down']}" class="btn btn-pill btn-clash mb-4">&nbsp;&nbsp;<i class="zero-clash text-white"></i>{$trans->t('tutorial.android.clash.text_5')}</a>&nbsp;&nbsp;&nbsp;
                                                            <p class="mb-2">{$trans->t('tutorial.android.clash.text_6')}</p>
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="separator separator-dashed separator-border"></div>
                                                    <div class="row p-5">
                                                        <div class="col-sm-12 col-md-12 pb-5 text-center fs-4">
                                                            <p class="fs-1 pb-5"><strong>{$trans->t('tutorial.android.clash.text_7')}</strong></p>
                                                            {if in_array('clash',$zeroconfig['index_sub'])}
                                                            <div class="drowdown mb-5 mr-3">
                                                                <button type="button" class="btn btn-clash dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">&nbsp;&nbsp;<i class="zero-clash text-white"></i>{$trans->t('tutorial.android.clash.text_8')}&nbsp;&nbsp;</button>
                                                                <ul class="dropdown-menu">
                                                                    <li><a class="dropdown-item copy-text" href="javasript:;" data-clipboard-text="{$subInfo["clash"]}">{$trans->t('tutorial.android.clash.text_9')}</a></li>
            
                                                                    <li><a class="dropdown-item" href="javasript:;" onclick="oneclickImport('clash', '{$subInfo["clash"]}')">{$trans->t('tutorial.android.clash.text_10')}</a></li>
                                                                </ul>
                                                            </div>
                                                            {/if}
                                                        
                                                            <p class="mb-2">{$trans->t('tutorial.android.clash.text_11')}</p>
                                                            
                    
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="separator separator-dashed separator-border"></div>
                                                    <div class="row p-5">
                                                        <div class="col-sm-12 col-md-12 pb-5 text-center fs-4">
                                                            <p class="fs-1 pb-5"><strong>{$trans->t('tutorial.android.clash.text_12')}</strong></p>
                                                            <p class="mb-2">{$trans->t('tutorial.android.clash.text_13')}</p>
                                                            
                                                        </div>
                                                        
                                                    </div>
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
        {include file='include/global/scripts.tpl'}
    </body>
</html>