"use strict";
var KTAuthNewPassword = function () {
    var t, e, r, o, a;
    return {
        init: function () {
            t = document.querySelector("#kt_new_password_form"), 
            e = document.querySelector("#kt_new_password_submit"), 
            o = KTPasswordMeter.getInstance(t.querySelector('[data-kt-password-meter="true"]')), 
            r = FormValidation.formValidation(t, {
                fields: {
                    password: {
                        validators: {
                            notEmpty: {
                                message: "请输入密码"
                            },
                            callback: {
                                message: "请输入有效的密码",
                                callback: function (t) {
                                    if (t.value.length > 0) return a()
                                }
                            }
                        }
                    },
                    "confirm-password": {
                        validators: {
                            notEmpty: {
                                message: "请确认密码"
                            },
                            identical: {
                                compare: function () {
                                    return t.querySelector('[name="password"]').value
                                }, 
                                message: "两次密码输入不一致"
                            }
                        }
                    }
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger({
                        event: {
                            password: !1
                        }
                    }),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: ".fv-row",
                        eleInvalidClass: "",
                        eleValidClass: ""
                    })
                }
            }), e.addEventListener("click", (function (a) {
                a.preventDefault(), 
                r.revalidateField("password"), 
                r.validate().then((function (r) {
                    "Valid" == r ? (e.setAttribute("data-kt-indicator", "on"), 
                    e.disabled = !0, 
                    setTimeout((function () {                        
                        $.ajax({
                            type: "POST",
                            url: location.pathname,
                            dataType: "json",
                            data: {
                                password: $("#password").val(),
                                repassword: $("#repassword").val()
                            },
                            success: function(data){
                                if (data.ret == 1){
                                    Swal.fire({
                                        text: data.msg,
                                        icon: "success",
                                        buttonsStyling: !1,
                                        confirmButtonText: "Ok",
                                        customClass: {
                                            confirmButton: "btn btn-primary"
                                        }
                                    }).then((function (e) {
                                        if (e.isConfirmed) {
                                            t.querySelector('[name="password"]').value = "", t.querySelector('[name="confirm-password"]').value = "", o.reset();
                                            var r = t.getAttribute("data-kt-redirect-url");
                                            r && (location.href = r)
                                        }
                                    }));
                                }else{
                                    Swal.fire({
                                        text: data.msg,
                                        icon: "error",
                                        buttonsStyling: !1,
                                        confirmButtonText: "OK",
                                        customClass: {
                                            confirmButton: "btn btn-primary"
                                        }
                                    });
                                    e.removeAttribute("data-kt-indicator");
                                    e.disabled = !1;
                                }
                            }
                        })                       
                    }), 2000)) : Swal.fire({
                        text: "抱歉，似乎检测到一些错误，请重试",
                        icon: "error",
                        buttonsStyling: !1,
                        confirmButtonText: "Ok",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    })
                }))
            })), t.querySelector('input[name="password"]').addEventListener("input", (function () {
                this.value.length > 0 && r.updateFieldStatus("password", "NotValidated")
            }))
        }
    }
}();
KTUtil.onDOMContentLoaded((function () {
    KTAuthNewPassword.init()
}));