<!DOCTYPE html>
<html lang="en">
    <head>
	<title>{$config["appName"]} Ticket </title>
        
        <meta charset="UTF-8" />
        <meta name="renderer" content="webkit" />
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
										<div class="card-header align-items-center py-5 gap-2 gap-md-5">
											<div class="card-title">
												<div class="fs-3 fw-bolder text-dark">
													{$trans->t('ticket')}
												</div>
											</div>
											<div class="card-toolbar">
												<button class="btn btn-primary fw-bold" type="button" data-bs-toggle="modal" data-bs-target="#zero_modal_create_ticket">{$trans->t('create ticket')}</button>
											</div>
										</div>
										<div class="card-body pt-0">
											<!-- begin datatable -->
											<table class="table align-middle table-striped table-row-bordered gy-5 gs-7" id="zero_ticket_table">
												<thead>
													<tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">                                                       
														<th>ID</th>
														<th>{$trans->t('subject')}</th>
														<th>{$trans->t('status')}</th>
														<th>{$trans->t('date')}</th>
														<th>{$trans->t('action')}</th>
													</tr>
												</thead>
												<tbody class="text-gray-600 fw-semibold"></tbody>
											</table>
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
		<script src="https://cdn.ckeditor.com/ckeditor5/35.1.0/classic/ckeditor.js"></script>
		<!-- create ticket modal -->
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
							<h1 class="mb-3">{$trans->t('create ticket')}</h1>
						</div>
						<div class="d-flex flex-column mb-8">
							<label class="d-flex align-items-center fs-6 fw-semibold mb-2">
								<span class="required">{$trans->t('subject')}</span>
							</label>
							<input type="text" class="form-control form-control-solid" placeholder="{$trans->t('subject')}" id="zero_create_ticket_title">
						</div>
						<div class="d-flex flex-column mb-8">
							<label class="fs-6 fw-semibold mb-2">
								<span class="required">{$trans->t('details')}</span>
							</label>
							<textarea id="zero_modal_create_ticket_ckeditor_classic" name="zero_modal_create_ticket_ckeditor_classic"></textarea>
						</div>
						<div class="d-flex flex-center flex-row-fluid pt-12">
							<button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">{$trans->t('discard')}</button>
							<button type="submit" class="btn btn-primary" data-kt-users-action="submit" onclick="KTUsersTicket('create_ticket')">
								<span class="indicator-label">{$trans->t('submit')}</span>
								<span class="indicator-progress">{$trans->t('please wait')}
								<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>

<script>
var editors;
ClassicEditor
    .create(document.getElementById('zero_modal_create_ticket_ckeditor_classic'))
    .then(editor => {
        editors = editor;
    })
    .catch(error => {
        console.error(error);
    });
</script>
    </body>
</html>