"use strict";
var KTSignupGeneral = function() {
    var e, t, a, r, s;
    return {
        init: function() {
            e = document.querySelector("#kt_sign_up_form"),
            t = document.querySelector("#kt_sign_up_submit"),
            r = KTPasswordMeter.getInstance(e.querySelector('[data-kt-password-meter="true"]')),
            a = FormValidation.formValidation(e, {
                fields: {
                    name: {
                        validators: {
                            notEmpty: {
                                message: "请输入用户名"
                            }
                        }
                    },
                    email: {
                        validators: {
                            regexp: {
                                regexp: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                                message: "请输入正确的邮箱"
                            },
                            notEmpty: {
                                message: "请输入邮箱"
                            }
                        }
                    },
                    password: {
                        validators: {
                            notEmpty: {
                                message: "请输入密码"
                            },
                            callback: {
                                message: "请输入有效的密码",
                                callback: function(e) {
                                    if (e.value.length > 0) return s()
                                }
                            }
                        }
                    },
                    "confirm-password": {
                        validators: {
                            notEmpty: {
                                message: "请再次输入密码"
                            },
                            identical: {
                                compare: function() {
                                    return e.querySelector('[name="password"]').value
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
            }),
            t.addEventListener("click", (function(s) {
                s.preventDefault(),
                //a.revalidateField("password"),
                a.validate().then((function(a) {
                    if ($('div').is('.cf-turnstile') == true) {
                        var datas = {
                            email: $("#email").val(),
                                name: $("#name").val(),
                                passwd: $("#passwd").val(),
                                repasswd: $("#repasswd").val(),
                                code: $("#code").val(),
                                turnstile: turnstile.getResponse()
                        };
                    } else {
                        datas = {
                            email: $("#email").val(),
                                name: $("#name").val(),
                                passwd: $("#passwd").val(),
                                repasswd: $("#repasswd").val(),
                                code: $("#code").val() 
                        };
                    }
                    "Valid" == a ? (t.setAttribute("data-kt-indicator", "on"), t.disabled = !0, setTimeout((function() {
                        t.removeAttribute("data-kt-indicator"),
                        t.disabled = !1,
                        $.ajax({
                            method: 'POST',
                            url: "/auth/register",
                            dataType: "json",
                            data: datas,
                            success: function (data) {
                                if (data.ret == 1){
                                    Swal.fire({
                                        text: "You have successfully signup",
                                        icon: "success",
                                        buttonsStyling: !1,
                                        confirmButtonText: "Ok",
                                        customClass: {
                                            confirmButton: "btn btn-primary"
                                        }
                                    }).then((function(t) {
                                        if (t.isConfirmed) {
                                            e.reset(),
                                            r.reset();
                                            var a = e.getAttribute("data-kt-redirect-url");
                                            a && (location.href = a)
                                        }
                                    }))
                                } else {
                                    Swal.fire({
                                        text: data.msg,
                                        icon: "error",
                                        buttonsStyling: !1,
                                        confirmButtonText: "Ok",
                                        customClass: {
                                            confirmButton: "btn btn-primary"
                                        }
                                    }) 
                                }
                            }
                        })
                    }), 1500)) : Swal.fire({
                        text: "抱歉，似乎检测到一些错误，请重试",
                        icon: "error",
                        buttonsStyling: !1,
                        confirmButtonText: "Ok",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    })
                }))
            })),
            e.querySelector('input[name="password"]').addEventListener("input", (function() {
                this.value.length > 0 && a.updateFieldStatus("password", "NotValidated")
            }))
        }
    }
} ();
KTUtil.onDOMContentLoaded((function() {
    KTSignupGeneral.init()
}));