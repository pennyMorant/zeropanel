<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{$config["appName"]} V2rayNG</title>
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
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-title">V2rayNG 教程</div>
                                            <div class="card-toolbar">
                                                <button class="btn btn-v2ray copy-text" type="button" data-clipboard-text="{$subInfo["v2rayn"]}">复制V2rayNG订阅</button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-6">
                                                    <h3>添加订阅连接</h3>
                                                    <ol>
                                                        <li>打开V2rayNG应用程序。</li>
                                                        <li>点击左上角的汉堡按钮，选择”订阅分组设置"。
                                                        <li>点击右上角的“+”按钮，输入订阅链接，然后点击右上角按钮。</li>
                                                    </ol>
                                                </div>
                                                <div class="col-6">
                                                    <h3>更新和使用</h3>
                                                    <ol>
                                                        <li>返回到APP主界面,点击右上角按钮。</li>
                                                        <li>选择"更新订阅", 等待更新完成。</li>
                                                        <li>出现节点列表后，选择一个节点</li>
                                                        <li>点击右下角按钮，开始使用。</li>
                                                    </ol>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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