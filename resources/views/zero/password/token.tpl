<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Reset Password - {$config["appName"]}</title>
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
        <meta http-equiv="expires" content="0">
        <link href="/theme/zero/css/fonts.css?family=Poppins:300,400,500,600,700" rel="stylesheet" />
        <link href="/theme/zero/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
        <link href="/theme/zero/css/style.bundle.css" rel="stylesheet" type="text/css" />
        <link href="/theme/zero/css/pages/auth/style-1.css" rel="stylesheet" type="text/css" />
        <link href="/favicon.png" rel="shortcut icon">
    </head>
    <body id="kt_body" class="quick-panel-right demo-panel-right offcanvas-right header-fixed subheader-enabled page-loading">
        <div class="d-flex flex-column flex-root" style="background-image:url(https://acctcdn.msauth.net/images/2_vD0yppaJX3jBnfbHF1hqXQ2.svg)">
            <div class="login login-1 login-signin-on d-flex flex-row-fluid" id="kt_login">
                <div class="d-flex flex-center bgi-size-cover bgi-no-repeat flex-row-fluid p-7">
                    <div class="login-form text-center text-dark bg-white p-7 position-relative overflow-hidden shadow">

                        <div class="login-signin">
                            <div class="mb-10">
                                <h3>设置新密码</h3>
                            </div>
                            <form class="form" id="tokenform">
                                <div class="form-group">
                                    <div class="input-group">
                                        
                                        <input class="form-control h-auto text-dark opacity-70 bg-whiet rounded-pill border-0 py-4 px-8" type="password" placeholder="新密码" name="password" id="password" autocomplete="new-password" required />
                                    </div>    
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <input class="form-control h-auto text-dark opacity-70 bg-white rounded-pill border-0 py-4 px-8" type="password" placeholder="再次输入密码" name="repasswd" id="repasswd" autocomplete="new-password" required />
                                    </div>    
                                </div>
                                <div class="form-group text-center mt-10" style="white-space:nowrap;">
                                    <button type="submit" class="btn btn-pill btn-primary btn-shadow-hover btn-block font-weight-bolder px-15 py-3">确定更改密码</button>
                                </div>
                            </form>
                            <div class="mt-10">
                                <a href="/signin" class="text-dark-75 text-hover-dark opacity-75 hover-opacity-100 font-weight-bold"><strong>返回登录</strong></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="/theme/zero/plugins/global/plugins.bundle.js"></script>
    </body>
</html>
        <script>
        document.addEventListener('DOMContentLoaded', function(e) {
            const token = document.getElementById('tokenform');
            const submitButton = token.querySelector('[type="submit"]');
            FormValidation.formValidation(
			    token,
			    {
				    fields: {
				        password: {
						    validators: {
							    notEmpty: {
								    message: '密码必须填写'
							    },
								stringLength: {
								    min: 8,
									message: '密码至少八位'
								},
						    }
					    },
					    repasswd: {
                            validators: {
                                notEmpty: {
                                    message: '密码必须填写'
                                },
								identical: {
									compare: function() {
										return token.querySelector('[name="password"]').value;
									},
									message: '密码不一致',
								}
                            }
                        },
                    },
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger(),
                        submitButton: new FormValidation.plugins.SubmitButton(),
                        // defaultSubmit: new FormValidation.plugins.DefaultSubmit(), // Uncomment this line to enable normal button submit after form validation
					    bootstrap: new FormValidation.plugins.Bootstrap(),
					    fieldStatus: new FormValidation.plugins.FieldStatus({
                            onStatusChanged: function(areFieldsValid) {
                                if (areFieldsValid) {
                                   
                                    submitButton.removeAttribute('disabled');
                                    submitButton.classList.add('bg-blue');
                                    submitButton.classList.add('white');
                                } else {
                                    submitButton.setAttribute('disabled', 'disabled');
                                    submitButton.classList.remove('bg-blue');
                                    submitButton.classList.remove('white');
                                }
                            }
                        }),
                        
				    }
			    }
		    )
		    .on('core.form.valid', function() {
		            
		            Swal.fire({
                       title: "重置中...",
                       text: "",
                       timer: 5000,
                       onOpen: function() {
                           Swal.showLoading()
                       }
                    }).then(function(result) {
                        if (result.dismiss === "timer") {
                            console.log("I was closed by the timer")
                        }
                    });
                    
                    $.ajax({
                        type: "POST",
                        url: location.pathname,
                        dataType: "json",
                        data: {
                           password: $("#password").val(),
						   repasswd: $("#repasswd").val(),
                        },
                        success: function (data) {
					        if (data.ret == 1) {
					            Swal.fire({
						            position: "center",
							        icon: "success",
							        title: data.msg,
							        showConfirmButton: false,
							        time: 1000
						        });
						      // window.location.assign('/user');
						      window.setTimeout("location.href='/signin'", 1200);
						    } else {
						        Swal.fire({
                                    title: data.msg,
                                    text: "",
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "确定",
                                    customClass: {
                                    confirmButton: "btn btn-primary"
                                    }
                                });
                            }
                        },
                        error: function (jqXHR) {
					        Swal.fire("错误，请重试","", "error");
                        }
                    });
            });
        });
    </script>