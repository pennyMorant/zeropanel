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
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-title text-dark fs-3 fw-bolder">用户配置</div>
                                            <div class="card-toolbar">
                                                <a class="btn btn-primary btn-sm fw-bold" onclick="updateUser('{$user->id}')">保存用户</a>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-xxl-6">
                                                    <label class="form-label required">用户邮箱</label>
                                                    <input class="form-control mb-5" id="email" name="email" type="text" placeholder="用户邮箱" value="{$user->email}">
                                                    <label class="form-label required">用户备注</label>
                                                    <input class="form-control mb-5" id="remark" name="remark" type="text" placeholder="用户备注" value="{$user->remark}">
                                                    <label class="form-label required">用户密码</label>
                                                    <input class="form-control mb-5" id="password" name="password" type="password" placeholder="用户密码" value="{$user->password}">
                                                    <label class="form-label required">用户余额</label>
                                                    <input class="form-control mb-5" id="money" name="money" type="number" placeholder="用户余额" value="{$user->money}">
                                                    <label class="form-label required">用户佣金</label>
                                                    <input class="form-control mb-5" id="commission" name="commission" type="number" placeholder="用户佣金" value="{$user->commission}">
                                                    <label class="form-label required">手动封禁时长(分钟)</label>
                                                    <input class="form-control mb-5" id="ban_time" name="ban_time" type="number" placeholder="手动封禁时长" value="0">
                                                    <label class="form-label required">最后被封禁时间</label>
                                                    <input class="form-control mb-5" id="last_detect_ban_time" name="last_detect_ban_time" type="text" disabled  value="{$user->last_detect_ban_time()}">
                                                    <label class="form-label required">解禁时间</label>
                                                    <input class="form-control mb-5" id="unban_time" name="unban_time" type="text" disabled  value="{$user->relieve_time()}">
                                                    <label class="form-label required">累积封禁次数</label>
                                                    <input class="form-control mb-5" id="detect_ban_number" name="detect_ban_number" type="text" disabled  value="{if $user->detect_ban_number()==0}标杆用户，没有被封禁过耶{else}太坏了，这位用户累计被封禁过 {$user->detect_ban_number()} 次呢{/if}"">
                                                    <label class="form-label required">累积触犯次数</label>
                                                    <input class="form-control mb-5" id="all_detect_number" name="all_detect_number" type="text" disabled  value="{$user->all_detect_number}">
                                                </div>
                                                <div class="col-xxl-6">
                                                    <label class="form-label required">SS 密码</label>
                                                    <input class="form-control mb-5" id="passwd" name="passwd" type="text" disabled value="{$user->passwd}">
                                                    <label class="form-label required">VMESS/TROJAN UUID</label>
                                                    <input class="form-control mb-5" id="uuid" name="uuid" type="text" disabled value="{$user->uuid}">
                                                    <label class="form-label required">用户流量</label>
                                                    <input class="form-control mb-5" id="transfer_enable" name="transfer_enable" type="text" value="{$user->enableTrafficInGB()}">
                                                    <label class="form-label required">已用流量</label>
                                                    <input class="form-control mb-5" id="usedTraffic" name="usedTraffic" type="text" disabled value="{$user->usedTraffic()}">
                                                    <label class="form-label required">邀请人ID</label>
                                                    <input class="form-control mb-5" id="ref_by" name="ref_by" type="number" disabled value="{$user->ref_by}">
                                                    <label class="form-label required">用户群组</label>
                                                    <input class="form-control mb-5" id="group" name="group" type="number" value="{$user->node_group}">
                                                    <label class="form-label required">用户等级</label>
                                                    <input class="form-control mb-5" id="class" name="class" type="number" value="{$user->class}">
                                                    <label class="form-label required">用户等级过期时间</label>
                                                    <input class="form-control mb-5" id="class_expire" name="class_expire" type="text" value="{$user->class_expire}">
                                                    <label class="form-label required">用户速度</label>
                                                    <input class="form-control mb-5" id="node_speedlimit" name="node_speedlimit" type="text" value="{$user->node_speedlimit}">
                                                    <label class="form-label required">用户IP数</label>
                                                    <input class="form-control mb-5" id="node_connector" name="node_connector" type="text" value="{$user->node_connector}">
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
        {include file='admin/script.tpl'}
    </body>
    <script>
        function updateUser(id) {
            $.ajax({
                type: "PUT",
                url: "/admin/user/update",
                dataType: "JSON",
                data: {
                    id,
                    email: $('#email').val(),
                    password: $('#password').val(),
                    group: $('#group').val(),
                    transfer_enable: $('#transfer_enable').val(),
                    node_speedlimit: $('#node_speedlimit').val(),
                    remark: $('#remark').val(),
                    money: $('#money').val(),
                    commission: $('#commission').val(),
                    ban_time: $('#ban_time').val(),
                    class: $('#class').val(),
                    class_expire: $('#class_expire').val(),
                    node_connector: $('#node_connector').val()
                },
                success: function(data){
                    if (data.ret === 1){
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
            })
        }
    </script>
</html>