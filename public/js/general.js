//"use strict";

//get left date
function countdown(date, dom) {
    let timerInterval = null;

    function updateTimer() {
        const endDate = new Date(date);
        const now = new Date().getTime();
        const distance = endDate.getTime() - now;
        if (distance <= 0) {
        clearInterval(timerInterval);
        } else {
            const days = Math.floor(distance / (1000 * 3600 * 24));
            const hours = Math.floor((distance % (1000 * 3600 * 24)) / (1000 * 3600));
            const minutes = Math.floor((distance % (1000 * 3600)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            const countdown = `${days}${i18next.t('day')} ${hours}:${minutes}:${seconds}`;
            document.getElementById(dom).innerHTML = countdown;
        }
    } 
    // 初始状态
    updateTimer();
    // 开启计时器
    timerInterval = setInterval(updateTimer, 1000);
}

//get cookie
function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i].trim();
        if (c.indexOf(name)==0) { return c.substring(name.length,c.length); }
    }
    return "";
}

//clipboard
var clipboard = new ClipboardJS('.copy-text');
clipboard.on('success', function(e) {
    getResult(i18next.t('copy success'), "", "success");
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

$(document).ready(function (){
    // 获取当前 URL 路径
    var path = window.location.pathname;

    // 使用 split() 切割路径字符串
    var parts = path.split('/');

    // 访问最后一个元素
    var target2 = parts[2];
    var target1 = parts[1];
    $(`a.menu-link[href='/${target1}/${target2}']`).addClass('active');
});

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

// show configure product modal 
function kTUserConfigureProductModal(id) {
    const checkOutButton = document.querySelector(`[data-kt-users-action="check-out-${id}"]`);
    checkOutButton.setAttribute('data-kt-indicator', 'on');
    checkOutButton.disabled = true;
    setTimeout(function() {
        function getProductData() {
            return new Promise(function(resolve, reject) {
                $.ajax({
                    type: "POST",
                    url: "/user/product/getinfo",
                    dataType: "json",
                    data: {
                        id
                    },
                    success: function(data){
                        resolve(data);
                    }
                });
            })
        }
        getProductData().then(function(data) {
            const product_info    = data;
            const html            = $('#zero_product_'+id).html();
            const name            = product_info.name;
            const month_price     = product_info.month_price;
            const quarter_price   = product_info.quarter_price;
            const half_year_price = product_info.half_year_price;
            const year_price      = product_info.year_price;
            const two_year_price  = product_info.two_year_price;
            const onetime_price   = product_info.onetime_price;
            var   modalInnerHtml  = $('#zero_modal_configure_product_inner_html');
            var   modalName       = $('#zero_modal_configure_product_name');
            var   modalPrice      = $('#zero_modal_configure_product_price');
            var   modalTotal      = $('#zero_modal_configure_product_total');
            var   modalCoupon     = $('#zero_modal_configure_coupon');
            var   modalCouponHtml = $('#zero_modal_configure_coupon_html');
            const submitButton    = document.querySelector('[data-kt-users-action="submit"]');
            if (product_info.type == 1) {
                const all_prices = {
                    month_price: {
                    label: i18next.t('monthly fee'),
                    value: parseFloat(month_price)
                    },
                    quarter_price: {
                    label: i18next.t('quarterly fee'),
                    value: parseFloat(quarter_price)
                    },
                    half_year_price: {
                    label: i18next.t('semi annua fee'),
                    value: parseFloat(half_year_price)
                    },
                    year_price: {
                    label: i18next.t('annual fee'),
                    value: parseFloat(year_price)
                    },
                    two_year_price: {
                        label: i18next.t('biennial fee'),
                        value: parseFloat(two_year_price)
                    },
                    onetime_price: {
                        label: i18next.t('onetime fee'),
                        value: parseFloat(onetime_price)
                    }
                };

                console.log(all_prices);
                const prices = [
                    all_prices.month_price,
                    all_prices.quarter_price,
                    all_prices.half_year_price,
                    all_prices.year_price,
                    all_prices.two_year_price,
                    all_prices.onetime_price
                ].filter(v => v.value !== null && typeof v.value !== 'undefined' && !isNaN(v.value));
                minPrice = prices.length > 0 ? prices.reduce((acc, curr) => curr.value < acc.value ? curr : acc, prices[0]) : null;
                
                console.log(minPrice);
                
                Object.entries(all_prices).forEach(([key, { label, value }]) => {
                    if (value) {
                    $('#zero_modal_configure_product_' + key).html(`<a class="btn btn-outline btn-active-light-primary" data-bs-toggle="pill">${label}</a>`);
                    $('#zero_modal_configure_product_' + key).attr('onclick', `KTUsersChangePlan("${value.toFixed(2)}", ${id}, "${key}")`);
                    if (value == minPrice.value) {
                        $(`#zero_modal_configure_product_${key} a`).addClass('active');
                    }
                    }
                });
                modalCoupon.attr('onclick', `KTUserVerifyCoupon(${minPrice.value.toFixed(2)}, ${id})`);      
            }

            modalInnerHtml.html(html);
            product_info.type == 3 ? modalCouponHtml.hide() : false;
            const product_final_price = (product_info.type == 1 ? minPrice.value.toFixed(2) : onetime_price); // 判断不同类型商品的价格
            modalName.html(product_info.type == 1 ? name + '&nbsp;X&nbsp;' + minPrice.label : name);
            modalPrice.html(product_final_price + currency_unit);
            modalTotal.html(product_final_price + currency_unit);
            submitButton.setAttribute('onclick', `KTUsersCreateOrder(${1}, "${product_final_price}", ${id})`);
            $("#zero_modal_configure_product").modal("show");
            checkOutButton.removeAttribute('data-kt-indicator');
            checkOutButton.disabled = false;
        });
    }, 2000)
    
}

function KTUsersChangePlan(price, id, type) {
    const productPlanMap = {
        'month_price': i18next.t('monthly fee'),
        'quarter_price': i18next.t('quarterly fee'),
        'half_year_price': i18next.t('semi annua fee'),
        'year_price': i18next.t('annual fee'),
        'two_year_price': i18next.t('biennial fee')
        };
    const name = $('#zero_product_name_'+id).html();
    const submitButton = document.querySelector('[data-kt-users-action="submit"]');
    const modalCoupon = $('#zero_modal_configure_coupon');
    modalCoupon.attr('onclick', `KTUserVerifyCoupon("${price}", ${id})`);
    $('#zero_modal_configure_product_name').html(`${name} X ${productPlanMap[type]}`);
    $('#zero_modal_configure_product_price').html(`${price}${currency_unit}`);
    $('#zero_modal_configure_product_total').html(`${price}${currency_unit}`);
    submitButton.setAttribute('onclick', `KTUsersCreateOrder(${1}, "${price}", ${id})`);
}

// verify coupon
function KTUserVerifyCoupon(product_price, product_id) {
    const submitButton = document.querySelector('[data-kt-users-action="verify-coupon"]');
    submitButton.setAttribute('data-kt-indicator', 'on');
    submitButton.disabled = true;
    setTimeout(function() {
        $.ajax({
            type: "POST",
            url: "/user/verify_coupon",
            dataType: "json",
            data: {
                coupon_code: $("#zero_coupon_code").val(),
                product_price,
                product_id
            },
            success: function (data) {
                if (data.ret == 1) {
                    $('zero_modal_configure_product_total').html(`${data.total}${currency_unit}`);
                } else {
                    getResult(data.msg, '', 'error');
                }
                submitButton.removeAttribute('data-kt-indicator');
                submitButton.disabled = false;
            }
        });
    }, 2000)
}
// create order
function KTUsersCreateOrder(type, price, product_id) {
    const submitButton = document.querySelector('[data-kt-users-action="submit"]');
    submitButton.setAttribute('data-kt-indicator', 'on');
    submitButton.disabled = true;
    switch (type) {
        case 1: //产品新购
            setTimeout(function () {
                $.ajax({
                    type: "POST",
                    url: `/user/order/create_order/${type}`,
                    dataType: "json",
                    data: {
                        product_id: product_id,
                        product_price: price,
                        coupon_code: $("#zero_coupon_code").val(),
                    },
                    success: function (data) {
                        if (data.ret == 1) {
                            $(location).attr('href', `/user/order/${data.order_no}`);
                        } else {
                            getResult(data.msg, '', 'error');
                            submitButton.removeAttribute('data-kt-indicator');
                            submitButton.disabled = false;
                        }
                    }
                });
            }, 2000)
            break;
        case 2:  // 充值账户
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
                            $(location).attr('href', `/user/order/${data.order_no}`);
                        } else {
                            getResult(data.msg, '', 'error');
                            submitButton.removeAttribute('data-kt-indicator');
                            submitButton.disabled = false;
                        }
                    }
                });
            }, 2000)
            break;
        case 3: // 产品续费
            $.ajax({
                type: "POST",
                url: "/user/order/create_order/"+type,
                dataType: "json",
                data: {},
                success: function(data){
                    if (data.ret == 1) {
                        $(location).attr('href', `/user/order/${data.order_no}`);
                    } else {
                        getResult(data.msg, '', 'error');
                    }
                }
            });
            break;
        default:
            getResult('请求错误', '', 'error');
    }
}

//pay for order
function KTUsersPayOrder(order_no) {
    const submitButton = document.querySelector('[data-kt-users-action="submit"]');
    submitButton.setAttribute('data-kt-indicator', 'on');
    submitButton.disabled = true;
    let payment_id = $("#payment_method a.active").attr("data-payment-id");
    let orderNo = order_no;
    
    setTimeout(() => {
        $.ajax({
            type: "POST",
            url: "/user/order/pay_order",
            dataType: "json",
            data: {payment_id, order_no: orderNo},
            success: function (data) {
                if (data.ret == 1) {
                    $(location).attr('href', data.url);
                } else if (data.ret == 2){
                    Swal.fire({
                        text: data.msg,
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {confirmButton: "btn btn-primary"}
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
    }, 2000)  
}

// ticket
function KTUsersTicket(type, id, status) {
    const submitButton = document.querySelector('[data-kt-users-action="submit"]');
    submitButton.setAttribute('data-kt-indicator', 'on');
    submitButton.disabled = true;
    var text = editors.getData();
    switch (type) {
        case 'create_ticket':
            setTimeout(function () {
                $.ajax({
                    type: "POST",
                    url: "/user/ticket/create",
                    dataType: "json",
                    data: {
                        title: $("#zero_create_ticket_title").val(),
                        comment: text,
                        type: $("#zero_create_ticket_type").val()
                    },
                    success: function (data) {
                        if (data.ret == 1) {
                            $(location).attr('href', `/user/ticket/view/${data.id}`);
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
                    url: "/user/ticket/update",
                    dataType: "json",
                    data: {
                        id,
                        status,
                        comment: text
                    },
                    success: function (data) {
                        if (data.ret == 1) {
                            location.reload();
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
                    const info = data.info;
                    const qrcodeHtml = `<div class="pb-3" align="center" id="qrcode${nodeid}"></div>`;
                    var content = data.url;
                    switch (data.type) {
                        case 2:                           
                            // 循环设置HTML内容
                            const selectors_vmess = {
                                '#zero_modal_vmess_node_info_remark': 'remark',
                                '#zero_modal_vmess_node_info_address': 'address',
                                '#zero_modal_vmess_node_info_port': 'port',
                                '#zero_modal_vmess_node_info_aid': 'aid',
                                '#zero_modal_vmess_node_info_uuid': 'uuid',
                                '#zero_modal_vmess_node_info_net': 'net',
                                '#zero_modal_vmess_node_info_path': 'path',
                                '#zero_modal_vmess_node_info_host': 'host',
                                '#zero_modal_vmess_node_info_servicename': 'servicename', 
                                '#zero_modal_vmess_node_info_type': 'type',
                                '#zero_modal_vmess_node_info_security': 'security'
                            }
                            
                            for (let selector in selectors_vmess) {
                                $(selector).html(info[selectors_vmess[selector]]);
                              }
                            // 生成QRCode
                            $('#zero_modal_vmess_node_info_qrcode').html(qrcodeHtml);                           
                            $("#zero_modal_vmess_node_info").modal('show'); 
                            break;
                        case 4:
                            const selectors_trojan = {
                                '#zero_modal_trojan_node_info_remark': 'remark', 
                                '#zero_modal_trojan_node_info_address': 'address',
                                '#zero_modal_trojan_node_info_port': 'port',
                                '#zero_modal_trojan_node_info_uuid': 'uuid',
                                '#zero_modal_trojan_node_info_sni': 'sni',
                                '#zero_modal_trojan_node_info_security': 'security',
                                '#zero_modal_trojan_node_info_flow': 'flow',
                            };
                            
                            for (let selector in selectors_trojan) {
                                $(selector).html(info[selectors_trojan[selector]]);
                              }
                            $("#zero_modal_trojan_node_info_qrcode").html(qrcodeHtml);
                            $("#zero_modal_trojan_node_info").modal('show');
                            break;
                        case 3:
                            const selectors_vless = {
                                '#zero_modal_vless_node_info_remark': 'remark',
                                '#zero_modal_vless_node_info_address': 'address',
                                '#zero_modal_vless_node_info_port': 'port',
                                '#zero_modal_vless_node_info_uuid': 'uuid',
                                '#zero_modal_vless_node_info_net': 'net',
                                '#zero_modal_vless_node_info_path': 'path',
                                '#zero_modal_vless_node_info_host': 'host',
                                '#zero_modal_vless_node_info_servicename': 'servicename',
                                '#zero_modal_vless_node_info_type': 'type',
                                '#zero_modal_vless_node_info_security': 'security',
                                '#zero_modal_vless_node_info_flow': 'flow',
                                '#zero_modal_vless_node_info_sni': 'sni',
                              }
                              
                            for (let selector in selectors_vless) {
                            $(selector).html(info[selectors_vless[selector]]);
                            }
                            $("#zero_modal_vless_node_info_qrcode").html(qrcodeHtml);
                            $("#zero_modal_vless_node_info").modal('show');
                            break;
                        case 1:
                            const selectors_ss = {
                                '#zero_modal_shadowsocks_node_info_remark': 'remark',
                                '#zero_modal_shadowsocks_node_info_address': 'address',
                                '#zero_modal_shadowsocks_node_info_port': 'port',
                                '#zero_modal_shadowsocks_node_info_method': 'method',
                                '#zero_modal_shadowsocks_node_info_passwd': 'passwd',
                            }

                            for (let selector in selectors_ss) {
                                $(selector).html(info[selectors_ss[selector]]);
                            }
                            $("#zero_modal_shadowsocks_node_info_qrcode").html(qrcodeHtml);
                            $("#zero_modal_shadowsocks_node_info").modal('show');
                            break;
                    }
                    $("#qrcode"  + nodeid).qrcode({
                        width: 200,
                        height: 200,
                        render: "canvas",
                        text: content
                    });
                    Swal.close();
				} else {                   
					getResult(data.msg, "", "error");
				}
			}
		});
    } else {
        getResult("权限不足", "", "error");
    }
}

// withdraw 
function KTUsersWithdrawCommission(type){
    switch (type) {
        case 1:
            $.post("/user/withdraw_commission", {
                commission: $('#withdraw_commission_amount').val(),
                type: $("#withdraw_type a.active").attr("data-type")
                }, function(data) {
                getResult(data.msg, '', (data.ret == 1) ? 'success' : 'error');
                }, "json");
            break;
        case 2:
            $.post("/user/withdraw_account_setting", {
                acc: $('#withdraw_account_value').val(),
                method: $('#withdraw_method').val()
                }, function(data) {
                getResult(data.msg, '', (data.ret == 1) ? 'success' : 'error');
                }, "json");
        default:
            0;
    }
}

//import sub url
function oneclickImport(client, subLink) {
   
    quanx_config = {
        "server_remote": [
            `${subLink}, tag=${webName}`
        ]
    }
    var sublink = {
      surfboard: `surfboard:///install-config?url=${encodeURIComponent(subLink)}`,
      quantumult: `quantumult://configuration?server=${btoa(subLink).replace(/=/g, '')}&filter=YUhSMGNITTZMeTl0ZVM1dmMyOWxZMjh1ZUhsNkwzSjFiR1Z6TDNGMVlXNTBkVzExYkhRdVkyOXVaZw`,
      shadowrocket: `shadowrocket://add/sub://${btoa(subLink)}`,
      surge4: `surge4:///install-config?url=${encodeURIComponent(subLink)}`,
      clash: `clash://install-config?url=${encodeURIComponent(subLink)}`,
      sagernet: `sn://subscription?url=${encodeURIComponent(subLink)}`,
      quantumultx: `quantumult-x:///add-resource?remote-resource=${encodeURIComponent(JSON.stringify(quanx_config))}`,
    }

    Swal.fire({
        title: i18next.t('confirm importing subscription link'),
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