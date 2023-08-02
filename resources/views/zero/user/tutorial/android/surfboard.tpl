<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{$config["website_name"]} Surfboard</title>
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
    {include file ='include/index/menu.tpl'}                
                    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                        <div class="d-flex flex-column flex-column-fluid mt-10">
                            <div id="kt_app_content" class="app-content flex-column-fluid">                         	
                                <div id="kt_app_content_container" class="app-container container-xxl">
                                    <div class="row">
                                        <div class="col-12">
                                        {foreach $knowledges as $knowledge}
                                            <div class="card">
                                                <div class="card-header">
                                                    <div class="card-title">
                                                        <div class="fs-2 fw-bold text-dark">
                                                            {$knowledge->title}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body">                                                   
                                                    {eval var=$knowledge->content}                                                   
                                                </div>
                                            </div>
                                        {/foreach}
                                        </div>
                                    </div>
                                </div>
                            </div>                           
						</div>
                        <div class="app_footer py-4 d-flex flex-lg-column" id="kt_app_footer">
							<div class="app-container container-fluid d-flex flex-column flex-md-row flex-center flex-md-stack py-3">
								<div class="text-dark-75 order-2 order-md-1">
									&copy;<script>document.write(new Date().getFullYear());</script>,&nbsp;<a>{$config["website_name"]},&nbsp;Inc.&nbsp;All rights reserved.</a>
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