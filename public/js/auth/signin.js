"use strict";
var KTSigninGeneral = function() {
    var e, t, i;
    return {
        init: function() {
            e = document.querySelector("#kt_sign_in_form"),
            t = document.querySelector("#kt_sign_in_submit"),
            
            i = FormValidation.formValidation(e, {
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
                            }
                        }
                    }
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger,
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: ".fv-row",
                        eleInvalidClass: "",
                        eleValidClass: ""
                    })
                }
            }),
            t.addEventListener("click", (function(n) {
                n.preventDefault(),
                i.validate().then((function(i) {
                    if ($('div').is('.cf-turnstile') == true) {
                        var datas = {
                            email: $("#signin-email").val(),
                            passwd: $("#signin-passwd").val(),                               
                            turnstile: turnstile.getResponse()
                        };
                    } else {
                        datas = {
                            email: $("#signin-email").val(),
                            passwd: $("#signin-passwd").val()             
                        };
                    }
                    "Valid" == i ? (t.setAttribute("data-kt-indicator", "on"), t.disabled = !0, setTimeout((function() {
                        t.removeAttribute("data-kt-indicator"),
                        t.disabled = !1,
                        $.ajax({
                            type: 'POST',
                            url: "/auth/signin",
                            dataType: "json",                           
                            data: datas,
                            success: function(data){
                                if(data.ret == 1){
                                    Swal.fire({
                                        text: "You have successfully logged in!",
                                        icon: "success",
                                        buttonsStyling: !1,
                                        confirmButtonText: "Ok",
                                        customClass: {
                                            confirmButton: "btn btn-primary"
                                        }
                                    }).then((function(t) {
                                        if (t.isConfirmed) {
                                            e.querySelector('[name="email"]').value = "",
                                            e.querySelector('[name="password"]').value = "";
                                            var i = e.getAttribute("data-kt-redirect-url");
                                            i && (location.href = i)
                                        }
                                    }))
                                }else{
                                    Swal.fire({
                                        text: data.msg,
                                        icon: "error",
                                        buttonsStyling: !1,
                                        confirmButtonText: "Ok, got it!",
                                        customClass: {
                                            confirmButton: "btn btn-primary"
                                        }
                                    })
                                }
                            }
                        })
                    }), 2e3)) : Swal.fire({
                        text: "Sorry, it seems some errors were detected, please try again",
                        icon: "error",
                        buttonsStyling: !1,
                        confirmButtonText: "Ok",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    })
                }))
            }))
        }
    }
} ();
KTUtil.onDOMContentLoaded((function() {
    KTSigninGeneral.init()
}));