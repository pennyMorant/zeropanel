<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{$config["website_name"]} 工单</title>
        
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
	{include file ='admin/menu.tpl'}
                    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                        <div class="d-flex flex-column flex-column-fluid mt-10">
                            <div id="kt_app_content" class="app-content flex-column-fluid">
                                <div id="kt_app_content_container" class="app-container container-xxl">

                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-title text-dark fs-3 fw-bolder">工单列表</div>
                                            <div class="card-toolbar">
												<button class="btn btn-primary fw-bold btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#zero_modal_create_ticket">
                                                <i class="bi bi-cloud-plus fs-3"></i>创建工单
                                                </button>
											</div>
                                        </div>
                                        <div class="card-body">
                                            {include file='table/table.tpl'}
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
        <div class="modal fade" id="zero_modal_create_ticket" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content rounded">
                    <div class="modal-header justify-content-end border-0 pb-0">
                        <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">                       
                            <span class="svg-icon svg-icon-1">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                                    <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                                </svg>
                            </span>
                            
                        </div>
                    </div>
                    <div class="modal-body scroll-y pt-0 pb-15 px-5 px-xl-20">
                        <div class="mb-13 text-center">
                            <h1 class="mb-3">创建工单</h1>
                        </div>
                        <form class="rounded border mt-10">
							<div class="d-block">
                                <div class="border-bottom">
                                    <select class="form-select" id="zero_admin_create_ticket_userid" data-control="select2" data-placeholder="选择一个用户">
                                        <option></option>
                                        {foreach $allUsers as $allUser}
                                            <option value={$allUser->id}>{$allUser->email}</option>
                                        {/foreach}
                                    </select>
                                </div>
								<div class="border-bottom">
									<select id="zero_admin_create_ticket_type" class="form-select" data-control="select2" data-hide-search="true" data-placeholder="工单类型">
										<option></option>
										<option value="support">支持</option>
										<option value="account">账户</option>
										<option value="billing">账单</option>
										<option value="sales">销售</option>
									</select>
								</div>															
								<div class="border-bottom">
									<input class="form-control border-0 px-8 min-h-45px" id="zero_admin_create_ticket_subject" placeholder="{$trans->t('subject')}" />
								</div>
								<div id="zero_admin_ticket_editor" class="border-0 h-250px px-3"></div>
							</div>
							<div class="d-flex flex-stack flex-wrap gap-2 py-5 ps-8 pe-5 border-top">
								<div class="d-flex align-items-center me-3">
									<button class="btn btn-primary fs-bold px-6" data-kt-users-action="submit" onclick="zeroAdminCreateTicket()">
										<span class="indicator-label">{$trans->t('submit')}</span>
										<span class="indicator-progress">{$trans->t('please wait')}
										<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
									</button>
								</div>
							</div>
						</form>
                    </div>
                </div>
            </div>
        </div>
        {include file='admin/script.tpl'}
        <script>
			var quill = new Quill("#zero_admin_ticket_editor", {
				modules: {
					toolbar: [
						[{
							header: [1, 2, !1]
						}],
						["bold", "italic", "underline"],
					]
				},
				placeholder: "",
				theme: "snow"
   			});
		</script>
        <script>
            window.addEventListener('load', () => {
                {include file='table/js_2.tpl'}
            })
        </script>
        <script>
            function zeroAdminCreateTicket(){
                const submitButton = document.querySelector('[data-kt-admin-action="submit"]');
                submitButton.setAttribute('data-kt-indicator', 'on');
                submitButton.disabled = true;
                var text = quill.root.innerHTML;
                setTimeout(function () {
                    $.ajax({
                        type: "POST",
                        url: "/{$config['website_admin_path']}/ticket/create",
                        dataType: "json",
                        data: {
                            subject: $("#zero_admin_create_ticket_subject").val(),
                            user_id: $("#zero_admin_create_ticket_userid").val(),
                            type: $("#zero_admin_create_ticket_type").val(),
                            content: text
                        },
                        success: function (data) {
                            if (data.ret == 1) {
                                getResult(data.msg, '', 'success');
                                submitButton.removeAttribute('data-kt-indicator');
                                submitButton.disabled = false;
                                $('#zero_modal_create_ticket').modal('hide');
                                table_1.ajax.reload();
                            } else {
                                getResult(data.msg, '', 'error');
                                submitButton.removeAttribute('data-kt-indicator');
                                submitButton.disabled = false;
                            }
                        }
                    });
                }, 2000);
            }
        </script>
        <script>
            function zeroAdminCloseTicket(id) {
                $.ajax({
                    type: "PUT",
                    url: "/{$config['website_admin_path']}/ticket/close",
                    dataType: "json",
                    data: {
                        id
                    },
                    success: function (data) {
                        getResult(data.msg, '', 'success');
                        table_1.ajax.reload();
                    }
                });
            }
        </script>
        
    </body>
</html>