<!DOCTYPE html>
<html lang="en">
    <head>
	<title>{$config["website_name"]} Ticket Detail</title>
        
        <meta charset="UTF-8" />
        <meta name="renderer" content="webkit" />
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
                                    <div class="card">
                                        <div class="card-body">
											<div class="mb-9">
                                                <form class="rounded border mt-10">
                                                    <div class="d-block">
                                                        <div id="zero_user_ticket_editor" class="border-0 h-250px px-3"></div>
                                                    </div>
                                                    <div class="d-flex flex-stack flex-wrap gap-2 py-5 ps-8 pe-5 border-top">
                                                        <div class="d-flex align-items-center me-3">
                                                            <button class="btn btn-primary fs-bold px-6" data-kt-users-action="submit" onclick="KTUsersTicket('reply_ticket', {$ticket->id}, 1)">
                                                                <span class="indicator-label">{$trans->t('submit')}</span>
                                                                <span class="indicator-progress">{$trans->t('please wait')}
                                                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
											</div>
												
										{foreach $comments as $comment}
                                            <div class="mb-9">
                                                <div class="card card-bordered w-100">
													
                                                    <div class="card-body">
                                                        <div class="w-100 d-flex flex-stack mb-8">
                                                            <div class="d-flex align-itmes-center f">
																<i class="bi bi-person-fill fs-3x me-3 {if $ticket->user()->is_admin == 1}text-primary {else}text-success{/if}"></i>
                                                                <div class="d-flex flex-column fw-semibold fs-5 text-gray-600 text-dark">
                                                                    <div class="d-flex align-items-center">
                                                                        <a class="text-gray-800 fw-bold text-hover-primary fs-5 me-3">{$comment['commenter_email']}</a>
                                                                        <span class="mb-0"></span>
                                                                    </div>
                                                                    <span class="text-muted fw-semibold fs-6">{date('Y-m-d H:i:s', $comment['datetime'])}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="fw-normal fs-3 text-gray-700 m-0">{nl2br($comment['comment'])}</div>
                                                    </div>
													
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
		<script>
			var quill = new Quill("#zero_user_ticket_editor", {
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
    </body>
</html>
