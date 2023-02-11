//"use strict";
//clipboard
var clipboard = new ClipboardJS('.copy-text');
clipboard.on('success', function(e) {
    getResult("复制成功", "", "success");
});

// get result 
function getResult(titles, texts, icons) {
    Swal.fire({
        title: titles,
        text: texts,
        icon: icons,
        buttonsStyling: false,
        confirmButtonText: "OK",
        customClass: {
            confirmButton: "btn btn-primary"
        }
    });
}

//get load
function getLoad() {
    Swal.fire({
        title: '',
        text: '',
        timer: 50000,
        confirmButtonText: "",
        didOpen: function() {
            Swal.showLoading()
        }
    }).then(function(result){
        if (result.dismiss == "timer") {
            console.log("I was closed by the timer")
        }
    });
}

// update email
var KTUsersUpdateEmail = function () {
    // Shared variables
    const element = document.getElementById('zero_modal_user_update_email');
    const form = element.querySelector('#zero_modal_user_update_email_form');
    const modal = new bootstrap.Modal(element);

    // Init add schedule modal
    var initUpdateEmail = () => {

        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        var validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    'profile_email': {
                        validators: {
                            notEmpty: {
                                message: 'Email address is required'
                            },
                            emailAddress: {
                                message: '邮箱格式不正确'
                            }
                        }
                    },
                },

                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row',
                        eleInvalidClass: '',
                        eleValidClass: ''
                    })
                }
            }
        );

        // Submit button handler
        const submitButton = element.querySelector('[data-kt-users-modal-action="submit"]');
        submitButton.addEventListener('click', function (e) {
            // Prevent default button action
            e.preventDefault();

            // Validate form before submit
            if (validator) {
                validator.validate().then(function (status) {
                    console.log('validated!');

                    if (status == 'Valid') {
                        // Show loading indication
                        submitButton.setAttribute('data-kt-indicator', 'on');

                        // Disable button to avoid multiple click 
                        submitButton.disabled = true;

                        // Simulate form submission. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                        setTimeout(function () {
                            // Remove loading indication
                            submitButton.removeAttribute('data-kt-indicator');

                            // Enable button
                            submitButton.disabled = false;

                            // Show popup confirmation 
                            $.ajax({
                                type: "POST",
                                url: "/user/update_profile/email",
                                dataType: "json",
                                data: {
                                    newemail: $("#profile_email").val()
                                },
                                success: function(data) {
                                    if(data.ret === 1) {
                                        Swal.fire({
                                            text: data.msg,
                                            icon: "success",
                                            buttonsStyling: false,
                                            confirmButtonText: "Ok, got it!",
                                            customClass: {
                                                confirmButton: "btn btn-primary"
                                            }
                                        }).then(function (result) {
                                            if (result.isConfirmed) {
                                                modal.hide();
                                                location.reload();
                                            }
                                        });
                                    } else {
                                        getResult(data.msg, '', 'error');
                                    }
                                }
                            });
                        }, 2000);
                    }
                });
            }
        });
    }

    return {
        // Public functions
        init: function () {
            initUpdateEmail();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTUsersUpdateEmail.init();
});

// update password
var KTUsersUpdatePassword = function () {
    // Shared variables
    const element = document.getElementById('zero_modal_user_update_password');
    const form = element.querySelector('#zero_modal_user_update_password_form');
    const modal = new bootstrap.Modal(element);

    // Init add schedule modal
    var initUpdatePassword = () => {

        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        var validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    'current_password': {
                        validators: {
                            notEmpty: {
                                message: '请输入当前密码'
                            }
                        }
                    },
                    'password': {
                        validators: {
                            notEmpty: {
                                message: '请输入新密码'
                            },
                            callback: {
                                message: '请输入有效的密码',
                                callback: function (input) {
                                    if (input.value.length > 0) {
                                        return validatePassword();
                                    }
                                }
                            }
                        }
                    },
                    'confirm_password': {
                        validators: {
                            notEmpty: {
                                message: '请确认新密码'
                            },
                            identical: {
                                compare: function () {
                                    return form.querySelector('[name="new_password"]').value;
                                },
                                message: '新密码两次输入不一致'
                            }
                        }
                    },
                },

                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row',
                        eleInvalidClass: '',
                        eleValidClass: ''
                    })
                }
            }
        );

        // Submit button handler
        const submitButton = element.querySelector('[data-kt-users-modal-action="submit"]');
        submitButton.addEventListener('click', function (e) {
            e.preventDefault();
            if (validator) {
                validator.validate().then(function (status) {
                    console.log('validated!');

                    if (status == 'Valid') {
                        submitButton.setAttribute('data-kt-indicator', 'on');

                        submitButton.disabled = true;

                        setTimeout(function () {
                            submitButton.removeAttribute('data-kt-indicator');
                            submitButton.disabled = false;
                            $.ajax({
                                type: "POST",
                                url: "/user/update_profile/password",
                                dataType: "json",
                                data: {
                                    current_password: $("#current_password").val(),
                                    new_password: $("#new_password").val()
                                },
                                success: function(data) {
                                    if(data.ret === 1) {
                                        Swal.fire({
                                            text: data.msg,
                                            icon: "success",
                                            buttonsStyling: false,
                                            confirmButtonText: "Ok, got it!",
                                            customClass: {
                                                confirmButton: "btn btn-primary"
                                            }
                                        }).then(function (result) {
                                            if (result.isConfirmed) {
                                                modal.hide();
                                                location.reload();
                                            }
                                        });
                                    } else {
                                        getResult(data.msg, '', 'error');
                                    }
                                }
                            });

                            //form.submit(); // Submit form
                        }, 2000);
                    }
                });
            }
        });
    }

    return {
        // Public functions
        init: function () {
            initUpdatePassword();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTUsersUpdatePassword.init();
});

// enable notify 
function KTUsersEnableNotify(type) {
    if (document.getElementById('notify_email').checked) {
        var types = type;
    }else if (document.getElementById('notify_telegram').checked) {
        var types = type;
    }
    $.ajax({
        type: "POST",
        url: "/user/enable_notify",
        dataType: "json",
        data: {
            notify_type: types
        },
        success: function(data) {}
    });
}

//reset ss connet passwd
function KTUsersResetPasswd() {
    $.ajax({
        type: "POST",
        url: "/user/update_profile/passwd",
        dataType: "json",
        data: {},
        success: function(data) {
            if(data.ret === 1) {
                Swal.fire({
                    text: data.msg,
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
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
    });
}

// reset uuid
function KTUsersResetUUID() {
    $.ajax({
        type: "POST",
        url: "/user/update_profile/uuid",
        dataType: "json",
        data: {},
        success: function(data) {
            if(data.ret === 1) {
                Swal.fire({
                    text: data.msg,
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
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
    });
}

// reset sub link
function KTUsersResetSubLink() {
    $.ajax({
        type: "POST",
        url: "/user/update_profile/sub_token",
        dataType: "json",
        data: {},
        success: function(data) {
            if(data.ret === 1) {
                Swal.fire({
                    text: data.msg,
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
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
    });
}

// show configure product modal 
function kTUserConfigureProductModal(id) {
    the_product_id = id;
    const html = $('#zero_product_'+id).html();
    const name = $('#zero_product_name_'+id).html();
    const price = $('#zero_product_price_'+id).html();
    const submitButton = document.querySelector('[data-kt-users-action="submit"]')
    $('#zero_modal_configure_product_inner_html').html(html);
    $('#zero_modal_configure_product_name').html(name + '&nbsp;X&nbsp;1');
    $('#zero_modal_configure_product_price').html(price + 'USD');
    $('#zero_modal_configure_product_total').html(price + 'USD');
    submitButton.setAttribute('onclick', 'KTUsersCreateOrder("purchase_product_order",' +id+')');
    $("#zero_modal_configure_product").modal("show");
}

// verify coupon
function KTUserVerifyCoupon() {
    $.ajax({
        type: "POST",
        url: "/user/verify_coupon",
        dataType: "json",
        data: {
            coupon_code: $("#zero_coupon_code").val(),
            product_id: the_product_id
        },
        success: function (data) {
            if (data.ret == 1) {
                document.getElementById('zero_modal_configure_product_total').innerHTML = data.total + 'USD';
            } else {
                getResult(data.msg, '', 'error');
            }
        }
    })
}
// create order
function KTUsersCreateOrder(type, product_id) {
    const submitButton = document.querySelector('[data-kt-users-action="submit"]');
    submitButton.setAttribute('data-kt-indicator', 'on');
    submitButton.disabled = true;
    switch (type) {
        case 'purchase_product_order':
            setTimeout(function () {
                $.ajax({
                    type: "POST",
                    url: "/user/order/create_order/"+type,
                    dataType: "json",
                    data: {
                        product_id: product_id,
                        coupon_code: $("#zero_coupon_code").val(),
                    },
                    success: function (data) {
                        if (data.ret == 1) {
                            setTimeout(function() {
                                submitButton.removeAttribute('data-kt-indicator');
                                submitButton.disabled = false;
                                $(location).attr('href', '/user/order/' + data.order_id);
                            }, 1500);
                        } else {
                            getResult(data.msg, '', 'error');
                            submitButton.removeAttribute('data-kt-indicator');
                            submitButton.disabled = false;
                        }
                    }
                });
            }, 2000)
            break;
        case 'add_credit_order':
            setTimeout(function () {
                $.ajax({
                    type: "POST",
                    url: "/user/order/create_order/"+type,
                    dataType: "json",
                    data: {
                        add_credit_amount: $("#add_credit_amount").val()
                    },
                    success: function (data) {
                        if (data.ret == 1) {
                            setTimeout(function() {
                                submitButton.removeAttribute('data-kt-indicator');
                                submitButton.disabled = false;
                                $(location).attr('href', '/user/order/' + data.order_id);
                            }, 1500);
                        } else {
                            getResult(data.msg, '', 'error');
                            submitButton.removeAttribute('data-kt-indicator');
                            submitButton.disabled = false;
                        }
                    }
                });
            }, 2000)
            break; 
    }
}

//pay for order
function KTUsersPayOrder(order_no) {
    const submitButton = document.querySelector('[data-kt-users-action="submit"]');
    submitButton.setAttribute('data-kt-indicator', 'on');
    submitButton.disabled = true;
    setTimeout(function () {
        $.ajax({
            type: "POST",
            url: "/user/order/pay_order",
            dataType: "json",
            data: {
                method: $("#payment_method a.active").attr("data-name"),
                order_no: order_no
            },
            success: function (data) {
                if (data.ret == 1) {
                    setTimeout(function() {
                        $(location).attr('href', data.url);
                        submitButton.removeAttribute('data-kt-indicator');
                        submitButton.disabled = false;
                    }, 1500);
                } else if (data.ret == 2){
                    Swal.fire({
                        text: data.msg,
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    }).then(function (result) {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                    submitButton.removeAttribute('data-kt-indicator');
                    submitButton.disabled = false;
                } else {
                    getResult(data.msg, '', 'error');
                    submitButton.removeAttribute('data-kt-indicator');
                    submitButton.disabled = false;
                }
            }
        });
    }, 2000);
}

// ticket
function KTUsersTicket(type, ticket_id, ticket_status) {
    const submitButton = document.querySelector('[data-kt-users-action="submit"]');
    submitButton.setAttribute('data-kt-indicator', 'on');
    submitButton.disabled = true;
    var text = editors.getData();
    switch (type) {
        case 'create_ticket':
            setTimeout(function () {
                $.ajax({
                    type: "POST",
                    url: "/user/ticket",
                    dataType: "json",
                    data: {
                        title: $("#zero_create_ticket_title").val(),
                        content: text
                    },
                    success: function (data) {
                        if (data.ret == 1) {
                            setTimeout(function() {
                                $(location).attr('href', '/user/ticket/'+data.tid+'/view');
                                submitButton.removeAttribute('data-kt-indicator');
                                submitButton.disabled = false;
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
        case 'reply_ticket':
            setTimeout(function () {
                $.ajax({
                    type: "PUT",
                    url: "/user/ticket/"+ticket_id,
                    dataType: "json",
                    data: {
                        status: ticket_status,
                        content: text
                    },
                    success: function (data) {
                        if (data.ret == 1) {
                            setTimeout(function() {
                                location.reload();
                                submitButton.removeAttribute('data-kt-indicator');
                                submitButton.disabled = false;
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

// show node 
function KTUsersShowNodeInfo(id, userclass, nodeclass) {
    nodeid = id;
    usersclass = userclass;
    nodesclass = nodeclass;
    if (usersclass >= nodesclass) {
        getLoad();
		$.ajax({
			type: "GET",
			url: "/user/nodeinfo/" + nodeid,
			dataType: "json",
			data: {},
			success: function(data) {
				if (data.ret == 1){
                    var content = data.url;
                    switch (data.sort) {
                        case 11:
                            $("#zero_modal_vmess_node_info_remark").html(data.info.remark);
                            $("#zero_modal_vmess_node_info_add").html(data.info.add);
                            $("#zero_modal_vmess_node_info_port").html(data.info.port);
                            $("#zero_modal_vmess_node_info_aid").html(data.info.aid);
                            $("#zero_modal_vmess_node_info_id").html(data.info.id);
                            $("#zero_modal_vmess_node_info_net").html(data.info.net);
                            $("#zero_modal_vmess_node_info_path").html(data.info.path);
                            $("#zero_modal_vmess_node_info_host").html(data.info.host);
                            $("#zero_modal_vmess_node_info_servicename").html(data.info.servicename);
                            $("#zero_modal_vmess_node_info_type").html(data.info.type);
                            $("#zero_modal_vmess_node_info_security").html(data.info.tls);
                            $("#zero_modal_vmess_node_info_qrcode").html('<div class="pb-3" align="center" id="qrcode'+nodeid+'"></div>');
                            $("#qrcode"  + nodeid).qrcode({
                                width: 200,
                                height: 200,
                                render: "canvas",
                                text: content
                            });
                            Swal.close();
                            $("#zero_modal_vmess_node_info").modal('show');
                            break;
                        case 14:
                            $("#zero_modal_trojan_node_info_remark").html(data.info.remark);
                            $("#zero_modal_trojan_node_info_add").html(data.info.address);
                            $("#zero_modal_trojan_node_info_port").html(data.info.port);						
                            $("#zero_modal_trojan_node_info_id").html(data.info.passwd);
                            $("#zero_modal_trojan_node_info_host").html(data.info.host);
                            $("#zero_modal_trojan_node_info_security").html(data.info.tls);
                            $("#zero_modal_trojan_node_info_qrcode").html('<div class="pb-3" align="center" id="qrcode'+nodeid+'"></div>');
                            $("#qrcode"  + nodeid).qrcode({
                                width: 200,
                                height: 200,
                                render: "canvas",
                                text: content
                            });
                            Swal.close();
                            $("#nodeinfo-trojan-modal").modal('show');
                            break;
                        case 15:
                            $("#zero_modal_vless_node_info_remark").html(data.info.remark);
                            $("#zero_modal_vless_node_info_add").html(data.info.add);
                            $("#zero_modal_vless_node_info_port").html(data.info.port);
                            $("#zero_modal_vless_node_info_id").html(data.info.id);
                            $("#zero_modal_vless_node_info_net").html(data.info.net);
                            $("#zero_modal_vless_node_info_path").html(data.info.path);
                            $("#zero_modal_vless_node_info_host").html(data.info.host);
                            $("#zero_modal_vless_node_info_servicename").html(data.info.servicename);
                            $("#zero_modal_vless_node_info_type").html(data.info.type);
                            $("#zero_modal_vless_node_info_security").html(data.info.tls);
                            $("#zero_modal_vless_node_info_flow").html(data.info.flow);
                            $("#zero_modal_vless_node_info_sni").html(data.info.sni);
                            $("#zero_modal_vless_node_info_qrcode").html('<div class="pb-3" align="center" id="qrcode'+nodeid+'"></div>');
                            $("#qrcode"  + nodeid).qrcode({
                                width: 200,
                                height: 200,
                                render: "canvas",
                                text: content
                            });
                            Swal.close();
                            $("#nodeinfo-vless-modal").modal('show');
                            break;
                        case 0:
                            $("#zero_modal_shadowsocks_node_info_remark").html(data.info.remark);
                            $("#zero_modal_shadowsocks_node_info_address").html(data.info.address);
                            $("#zero_modal_shadowsocks_node_info_port").html(data.info.port);
                            $("#zero_modal_shadowsocks_node_info_method").html(data.info.method);
                            $("#zero_modal_shadowsocks_node_info_passwd").html(data.info.passwd);
                            $("#zero_modal_shadowsocks_node_info_qrcode").html('<div class="pb-3" align="center" id="qrcode'+nodeid+'"></div>');
                            $("#qrcode"  + nodeid).qrcode({
                                width: 200,
                                height: 200,
                                render: "canvas",
                                text: content
                            });
                            Swal.close();
                            $("#zero_modal_shadowsocks_node_info").modal('show');
                            break;
                    }
				} else {                   
					getResult(data.msg, "", "error");
				}
			}
		});
    } else {
        getResult("权限不足", "", "error");
    }
}

//import sub url
function oneclickImport(client, subLink) {
    var sublink = {
      surfboard: "surfboard:///install-config?url=" + encodeURIComponent(subLink),
      quantumult: "quantumult://configuration?server=" + btoa(subLink).replace(/=/g, '') + "&filter=YUhSMGNITTZMeTl0ZVM1dmMyOWxZMjh1ZUhsNkwzSjFiR1Z6TDNGMVlXNTBkVzExYkhRdVkyOXVaZw",
      shadowrocket: "shadowrocket://add/sub://" + btoa(subLink),
      surge4: "surge4:///install-config?url=" + encodeURIComponent(subLink),
      clash: "clash://install-config?url=" + encodeURIComponent(subLink),
      sagernet: "sn://subscription?url=" + encodeURIComponent(subLink),
    }
    Swal.fire({
        title: "Whether to import subscription links",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Submit",
        cancelButtonText: "Discard",
        focusClose: false,
        focusConfirm: false,
    }).then((result) => {
        if (result.value) {
        window.location.href = sublink[client];
        }
    });
}

