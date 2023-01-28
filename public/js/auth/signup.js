"use strict";
var KTSignupGeneral = function() {
    var e, t, a, r, s;
    return {
        init: function() {
            e = document.querySelector("#kt_sign_up_form"),
            t = document.querySelector("#kt_sign_up_submit"),
            a = FormValidation.formValidation(e, {
                fields: {
                    email: {
                        validators: {
                            regexp: {
                                regexp: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                                message: "The value is not a valid email address"
                            },
                            notEmpty: {
                                message: "Email address is required"
                            }
                        }
                    },
                    password: {
                        validators: {
                            notEmpty: {
                                message: "The password is required"
                            },
                            callback: {
                                message: "Use 8 or more characters with a mix of letters, numbers & symbols.",
                                callback: function(e) {
                                    if (e.value.length > 0) return s()
                                }
                            }
                        }
                    },
                    "confirm-password": {
                        validators: {
                            notEmpty: {
                                message: "he password confirmation is required"
                            },
                            identical: {
                                compare: function() {
                                    return e.querySelector('[name="password"]').value
                                },
                                message: "The password and its confirm are not the same"
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
                                passwd: $("#passwd").val(),
                                repasswd: $("#repasswd").val(),
                                code: $("#code").val(),
                                turnstile: turnstile.getResponse()
                        };
                    } else {
                        datas = {
                            email: $("#email").val(),
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
                                        text: data.msg,
                                        icon: "success",
                                        buttonsStyling: !1,
                                        confirmButtonText: "Ok",
                                        customClass: {
                                            confirmButton: "btn btn-primary"
                                        }
                                    }).then((function(t) {
                                        if (t.isConfirmed) {
                                            e.reset();
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
                        text: "Sorry, it seems some errors were detected, please try again",
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