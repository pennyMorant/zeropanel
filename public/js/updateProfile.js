//"use strict";

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

// Class definition
var KTUsersUpdateName = function () {
    // Shared variables
    const element = document.getElementById('zero_modal_user_update_name');
    const form = element.querySelector('#zero_modal_user_update_name_form');
    const modal = new bootstrap.Modal(element);

    // Init add schedule modal
    var initUpdateName = () => {

        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        var validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    'profile_name': {
                        validators: {
                            notEmpty: {
                                message: 'Naem is required'
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
                                url: "/user/update_name",
                                dataType: "json",
                                data: {
                                    newusername: $("#profile_name").val()
                                },
                                success: function(data) {
                                    if (data.ret == 1) {
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
            initUpdateName();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTUsersUpdateName.init();
});

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
                                url: "/user/update_email",
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
                                url: "/user/update_password",
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
        url: "/user/reset_passwd",
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
        url: "/user/reset_uuid",
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
        url: "/user/reset_sub_link",
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
    const html = document.getElementById('zero_product_'+id).innerHTML;
    const name = document.getElementById('zero_product_name_'+id).innerHTML;
    const price = document.getElementById('zero_product_price_'+id).innerHTML;
    const submitButton = document.querySelector('[data-kt-users-action="submit"]')
    document.getElementById('zero_modal_configure_product_inner_html').innerHTML =  html;
    document.getElementById('zero_modal_configure_product_name').innerHTML = name + '&nbsp;X&nbsp;1';
    document.getElementById('zero_modal_configure_product_price').innerHTML = price + 'USD';
    document.getElementById('zero_modal_configure_product_total').innerHTML = price + 'USD';
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
		$.ajax({
			type: "GET",
			url: "/user/nodeinfo/" + nodeid,
			dataType: "json",
			data: {},
			success: function(data) {
				if (data.ret == 1){
					if (data.sort == 11) {
						var content = data.url;
						document.getElementById('zero_modal_vmess_node_info_remark').innerHTML = data.info.remark;
						document.getElementById('zero_modal_vmess_node_info_add').innerHTML = data.info.add;
						document.getElementById('zero_modal_vmess_node_info_port').innerHTML = data.info.port;
						document.getElementById('zero_modal_vmess_node_info_aid').innerHTML = data.info.aid;
						document.getElementById('zero_modal_vmess_node_info_id').innerHTML = data.info.id;
						document.getElementById('zero_modal_vmess_node_info_net').innerHTML = data.info.net;
						document.getElementById('zero_modal_vmess_node_info_path').innerHTML = data.info.path;
                        document.getElementById('zero_modal_vmess_node_info_servicename').innerHTML =data.info.servicename;
						document.getElementById('zero_modal_vmess_node_info_type').innerHTML = data.info.type;
						document.getElementById('zero_modal_vmess_node_info_security').innerHTML = data.info.tls;
						document.getElementById('zero_modal_vmess_node_info_qrcode').innerHTML = '<div class="pb-3" align="center" id="qrcode'+nodeid+'"></div>';
						$("#qrcode"  + nodeid).qrcode({
							width: 200,
							height: 200,
							render: "canvas",
							text: content
						});
						Swal.close();
						$("#nodeinfo-v2ray-modal").modal('show');
					} else if ( data.sort == 14) {
						var content = data.url;
						document.getElementById('zero_modal_trojan_node_info_remark').innerHTML = data.info.remark;
						document.getElementById('zero_modal_trojan_node_info_add').innerHTML = data.info.address;
						document.getElementById('zero_modal_trojan_node_info_port').innerHTML = data.info.port;
						document.getElementById('zero_modal_trojan_node_info_id').innerHTML = data.info.passwd;
						document.getElementById('zero_modal_trojan_node_info_host').innerHTML = data.info.host;
						document.getElementById('zero_modal_trojan_node_info_security').innerHTML = data.info.tls;
						document.getElementById('zero_modal_trojan_node_info_qrcode').innerHTML = '<div class="pb-3" align="center" id="qrcode'+nodeid+'"></div>';
						$("#qrcode"  + nodeid).qrcode({
							width: 200,
							height: 200,
							render: "canvas",
							text: content
						});
						Swal.close();
						$("#nodeinfo-trojan-modal").modal('show');
					} else if (data.sort == 15) {
						var content = data.url;
						document.getElementById('zero_modal_vless_node_info_remark').innerHTML = data.info.remark;
						document.getElementById('zero_modal_vless_node_info_add').innerHTML = data.info.add;
						document.getElementById('zero_modal_vless_node_info_port').innerHTML = data.info.port;
						document.getElementById('zero_modal_vless_node_info_id').innerHTML = data.info.id;
						document.getElementById('zero_modal_vless_node_info_net').innerHTML = data.info.net;
						document.getElementById('zero_modal_vless_node_info_path').innerHTML = data.info.path;
						document.getElementById('zero_modal_vless_node_info_type').innerHTML = data.info.type;
						document.getElementById('zero_modal_vless_node_info_security').innerHTML = data.info.tls;
						document.getElementById('zero_modal_vless_node_info_flow').innerHTML = data.info.flow;
						document.getElementById('zero_modal_vless_node_info_sni').innerHTML = data.info.sni;
						document.getElementById('zero_modal_vless_node_info_qrcode').innerHTML = '<div class="pb-3" align="center" id="qrcode'+nodeid+'"></div>';
						$("#qrcode"  + nodeid).qrcode({
							width: 200,
							height: 200,
							render: "canvas",
							text: content
						});
						Swal.close();
						$("#nodeinfo-vless-modal").modal('show');
					} else if (data.sort == 0) {
						var content = data.url;
						document.getElementById('zero_modal_shadowsocks_node_info_remark').innerHTML = data.info.remark;
						document.getElementById('zero_modal_shadowsocks_node_info_address').innerHTML = data.info.address;
						document.getElementById('zero_modal_shadowsocks_node_info_port').innerHTML = data.info.port;
						document.getElementById('zero_modal_shadowsocks_node_info_method').innerHTML = data.info.method;
						document.getElementById('zero_modal_shadowsocks_node_info_passwd').innerHTML = data.info.passwd;
						document.getElementById('zero_modal_shadowsocks_node_info_qrcode').innerHTML = '<div class="pb-3" align="center" id="qrcode'+nodeid+'"></div>';
						$("#qrcode"  + nodeid).qrcode({
							width: 200,
							height: 200,
							render: "canvas",
							text: content
						});
						Swal.close();
						$("#nodeinfo-ss-modal").modal('show');
					}
				} else {
					getResult(data.msg, "", "error");
				}
			}
		});
    } else {       
        getResult("会员身份权限不足", "", "error");
    }
}