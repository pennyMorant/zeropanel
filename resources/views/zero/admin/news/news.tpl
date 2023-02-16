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
                                            <div class="card-title text-dark fs-3 fw-bolder">公告列表</div>
                                            <div class="card-toolbar">
												<button class="btn btn-primary fw-bold btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#zero_modal_create_news">创建公告</button>
											</div>
                                        </div>
                                        <div class="card-body">
                                            {include file='table/table.tpl'}
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
    <div class="modal fade" id="zero_modal_create_news" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
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
                        <h1 class="mb-3">创建公告</h1>
                    </div>
                    <div class="d-flex flex-column mb-8">
                        <label class="fs-6 fw-semibold mb-2">
                            <span class="required">{$trans->t('details')}</span>
                        </label>
                        <textarea id="zero_modal_create_news_ckeditor_classic" name="zero_modal_create_news_ckeditor_classic"></textarea>
                    </div>
                    <div class="d-flex flex-center flex-row-fluid pt-12">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">{$trans->t('discard')}</button>
                        <button type="submit" class="btn btn-primary" data-kt-admin-create-news-action="submit" onclick="zeroAdminCreateNews()">
                            <span class="indicator-label">{$trans->t('submit')}</span>
                            <span class="indicator-progress">{$trans->t('please wait')}
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="zero_modal_update_news" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
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
                        <h1 class="mb-3">更新公告</h1>
                    </div>
                    <div class="d-flex flex-column mb-8">
                        <label class="fs-6 fw-semibold mb-2">
                            <span class="required">{$trans->t('details')}</span>
                        </label>
                        <textarea id="zero_modal_update_news_ckeditor_classic" name="zero_modal_update_news_ckeditor_classic"></textarea>
                    </div>
                    <div class="d-flex flex-center flex-row-fluid pt-12">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">{$trans->t('discard')}</button>
                        <button type="submit" class="btn btn-primary" data-kt-admin-update-news-action="submit" onclick="zeroAdminUpdateNews()">
                            <span class="indicator-label">{$trans->t('submit')}</span>
                            <span class="indicator-progress">{$trans->t('please wait')}
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.ckeditor.com/ckeditor5/35.1.0/classic/ckeditor.js"></script>
    <script>
        var create_news_editors;
        ClassicEditor
            .create(document.getElementById('zero_modal_create_news_ckeditor_classic'))
            .then(editor => {
                create_news_editors = editor;
            })
            .catch(error => {
                console.error(error);
            });
    </script>
    <script>
        var update_news_editors;
        ClassicEditor
            .create(document.getElementById('zero_modal_update_news_ckeditor_classic'))
            .then(editor => {
                update_news_editors = editor;
            })
            .catch(error => {
                console.error(error);
            });
    </script>
    <script>
        window.addEventListener('load', () => {
            {include file='table/js_2.tpl'}
        })
    </script>
    <script>
        function zeroAdminCreateNews(){
            const submitButton = document.querySelector('[data-kt-admin-create-news-action="submit"]');
            submitButton.setAttribute('data-kt-indicator', 'on');
            submitButton.disabled = true;
            var text = create_news_editors.getData();
            setTimeout(function () {
                $.ajax({
                    type: "POST",
                    url: "/admin/news/create",
                    dataType: "json",
                    data: {
                        content: text,
                        issend: 1,
                        page: 1
                    },
                    success: function (data) {
                        if (data.ret == 1) {
                            setTimeout(function() {
                                submitButton.removeAttribute('data-kt-indicator');
                                submitButton.disabled = false;
                                getResult(data.msg, '', 'success');
                                location.reload();
                            }, 1500);
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
        function zeroAdminUpdateNews(type, id){
            const submitButton = document.querySelector('[data-kt-admin-update-news-action="submit"]');
            switch (type){
                case 'request':
                    $.ajax({
                        type: "POST",
                        url: "/admin/news/request",
                        dataType: "json",
                        data:{
                            id,
                        },
                        success: function(data){
                            update_news_editors.data.set(data.content);
                            submitButton.setAttribute('onclick', 'zeroAdminUpdateNews("update", '+data.id+')')
                            $('#zero_modal_update_news').modal('show');
                        }
                    });
                    break;
                case 'update':
                    submitButton.setAttribute('data-kt-indicator', 'on');
                    submitButton.disabled = true;
                    var text = update_news_editors.getData();
                    setTimeout(function () {
                        $.ajax({
                            type: "PUT",
                            url: "/admin/news/update",
                            dataType: "json",
                            data: {
                                content: text,
                                id
                            },
                            success: function (data) {
                                if (data.ret == 1) {
                                    setTimeout(function() {
                                        getResult(data.msg, '', 'success');
                                        submitButton.removeAttribute('data-kt-indicator');
                                        submitButton.disabled = false;
                                        //location.reload();
                                    }, 1500);
                                } else {
                                    getResult(data.msg, '', 'error');
                                    submitButton.removeAttribute('data-kt-indicator');
                                    submitButton.disabled = false;
                                }
                            }
                        });
                    }, 2000);
                    break;
            }
        }
    </script>
</html>