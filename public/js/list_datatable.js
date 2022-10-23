
// Class definition
var KTDatatablesOrderSide = function () {
    // Shared variables
    var table;
    var dt;

    // Private functions
    var initDatatable = function () {
        dt = $("#zero_order_table").DataTable({
            searchDelay: 500,
            processing: true,
            serverSide: true,
            order: [[4, 'desc']],
            stateSave: true,
            select: {
                style: 'multi',
                selector: 'td:first-child input[type="checkbox"]',
                className: 'row-selected'
            },
            ajax: {
                url: "/user/ajax_data/table/order",
            },
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.12.1/i18n/zh.json",
            },
            columns: [
                { data: 'no' },
                { data: 'order_total' },
                { data: 'order_status' },
                { data: 'order_type' },
                { data: 'created_time' },
                { data: null},
            ],
            
            columnDefs: [
                {
                    targets: -1,
                    orderable: false,
                    className: 'text-end',
                    render: function (data) {
                        return `<a class="btn btn-sm btn-light-primary" href="/user/order/${data.no}" >详情</a>`;
                    },
                },
                {
                    targets: 2,
                    render: function (data, type, row) {
                        var date = Date.now();
                        if (data == 'paid') {
                            return '<div class="badge font-weight-bold badge-light-success fs-6">支付成功</div>';
                        } else if (data == 'pending') {
                            return `<div class="badge font-weight-bold badge-light-warning fs-6">等待支付</div>`;
                        } else if (data == 'invalid') {
                            return `<div class="badge font-weight-bold badge-light-danger fs-6">订单失效</div>`;
                        }
                    },
                },
                
            ],
            
        });

    }
    return {
        init: function () {
            initDatatable();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTDatatablesOrderSide.init();
});

// ticket table
var KTDatatablesTicketSide = function () {
    // Shared variables
    var table;
    var dt;

    // Private functions
    var initDatatable = function () {
        dt = $("#zero_ticket_table").DataTable({
            searchDelay: 500,
            processing: true,
            serverSide: true,
            order: [[2, 'desc']],
            stateSave: true,
            select: {
                style: 'multi',
                selector: 'td:first-child input[type="checkbox"]',
                className: 'row-selected'
            },
            ajax: {
                url: "/user/ajax_data/table/ticket",
            },
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.12.1/i18n/zh.json",
            },
            columns: [
                { data: 'id' },
                { data: 'title' },
                { data: 'status' },
                { data: 'datetime' },
                { data: null},
            ],
            columnDefs: [
                {
                    targets: 2,
                    render: function (data) {
                        if (data == 1) {
                            return '<div class="badge font-weight-bold badge-light-success fs-6">活跃</div>';
                        } else {
                            return '<div class="badge font-weight-bold badge-light fs-6">关闭</div>';
                        }
                    },
                },
                {
                    targets: -1,
                    render: function (data) {
                        return `<a class="btn btn-sm btn-light-primary" href="/user/ticket/${data.id}/view" >详情</a>`;
                    },
                },
                
            ],
            
        });

        table = dt.$;

    }
    return {
        init: function () {
            initDatatable();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTDatatablesTicketSide.init();
});

//登录记录
var KTDatatablesSigninLogSide = function () {
    var table;
    var dt;

    var initDatatable = function () {
        dt = $("#zero_signin_log_table").DataTable({
            searchDelay: 500,
            processing: true,
            serverSide: true,
            order: [[2, 'desc']],
            stateSave: true,
            select: {
                style: 'multi',
                selector: 'td:first-child input[type="checkbox"]',
                className: 'row-selected'
            },
            ajax: {
                url: "/user/ajax_data/table/loginlog",
            },
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.12.1/i18n/zh.json",
            },
            columns: [
                { data: 'ip' },
                { data: 'location' },
                { data: 'datetime' },
            ],
        });

    }
    return {
        init: function () {
            initDatatable();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTDatatablesSigninLogSide.init();
});

// used log
var KTDatatablesUsedLogSide = function () {
    var table;
    var dt;

    var initDatatable = function () {
        dt = $("#zero_used_log_table").DataTable({
            searchDelay: 500,
            processing: true,
            serverSide: true,
            order: [[2, 'desc']],
            stateSave: true,
            select: {
                style: 'multi',
                selector: 'td:first-child input[type="checkbox"]',
                className: 'row-selected'
            },
            ajax: {
                url: "/user/ajax_data/table/uselog",
            },
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.12.1/i18n/zh.json",
            },
            columns: [
                { data: 'ip' },
                { data: 'location' },
                { data: 'datetime' },
            ],
        });

    }
    return {
        init: function () {
            initDatatable();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTDatatablesUsedLogSide.init();
});

// sub log
var KTDatatablesSubscribeLogSide = function () {
    var table;
    var dt;

    var initDatatable = function () {
        dt = $("#zero_subscribe_log_table").DataTable({
            searchDelay: 500,
            processing: true,
            serverSide: true,
            order: [[4, 'desc']],
            stateSave: true,
            select: {
                style: 'multi',
                selector: 'td:first-child input[type="checkbox"]',
                className: 'row-selected'
            },
            ajax: {
                url: "/user/ajax_data/table/sublog",
            },
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.12.1/i18n/zh.json",
            },
            columns: [
                { data: 'subscribe_type' },
                { data: 'request_ip' },
                { data: 'location'},
                { data: 'request_user_agent'},
                { data: 'request_time' },
            ],
            columnDefs: [
                {
                    targets: 2,
                    orderable: false,
                },
                {
                    targets: 3,
                    orderable: false
                }
                
                
            ],
        });

    }
    return {
        init: function () {
            initDatatable();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTDatatablesSubscribeLogSide.init();
});

// traffic log
var KTDatatablesTrafficLogSide = function () {
    var table;
    var dt;

    var initDatatable = function () {
        dt = $("#zero_traffic_log_table").DataTable({
            searchDelay: 500,
            processing: true,
            serverSide: true,
            order: [[2, 'desc']],
            stateSave: true,
            select: {
                style: 'multi',
                selector: 'td:first-child input[type="checkbox"]',
                className: 'row-selected'
            },
            ajax: {
                url: "/user/ajax_data/table/trafficlog",
            },
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.12.1/i18n/zh.json",
            },
            columns: [
                { data: 'node_name' },
                { data: 'traffic' },
                { data: 'datetime' },
            ],
        });

    }
    return {
        init: function () {
            initDatatable();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTDatatablesTrafficLogSide.init();
});

// ban rule
var KTDatatablesBanRuleSide = function () {
    var table;
    var dt;

    var initDatatable = function () {
        dt = $("#zero_ban_rule_table").DataTable({
            searchDelay: 500,
            processing: true,
            serverSide: true,
            order: [[3, 'desc']],
            stateSave: true,
            select: {
                style: 'multi',
                selector: 'td:first-child input[type="checkbox"]',
                className: 'row-selected'
            },
            ajax: {
                url: "/user/ajax_data/table/ban_rule",
            },
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.12.1/i18n/zh.json",
            },
            columns: [
                { data: 'name' },
                { data: 'regex' },
                { data: 'text'},
                { data: 'type' },
            ],
        });

    }
    return {
        init: function () {
            initDatatable();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTDatatablesBanRuleSide.init();
});

// user baned log
var KTDatatablesUserBanedLogSide = function () {
    var table;
    var dt;

    var initDatatable = function () {
        dt = $("#zero_user_baned_log_table").DataTable({
            searchDelay: 500,
            processing: true,
            serverSide: true,
            order: [[5, 'desc']],
            stateSave: true,
            select: {
                style: 'multi',
                selector: 'td:first-child input[type="checkbox"]',
                className: 'row-selected'
            },
            ajax: {
                url: "/user/ajax_data/table/user_baned_log",
            },
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.12.1/i18n/zh.json",
            },
            columns: [
                { data: 'node_name' },
                { data: 'rule_name' },
                { data: 'rule_regex'},
                { data: 'rule_text' },
                { data: 'rule_type'},
                { data: 'datetime'},
            ],
        });

    }
    return {
        init: function () {
            initDatatable();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTDatatablesUserBanedLogSide.init();
});

// commission
var KTDatatablesUserGetCommissionLogSide = function () {
    var table;
    var dt;

    var initDatatable = function () {
        dt = $("#zero_user_get_commission_log_table").DataTable({
            searchDelay: 500,
            processing: true,
            serverSide: true,
            order: [[1, 'desc']],
            stateSave: true,
            select: {
                style: 'multi',
                selector: 'td:first-child input[type="checkbox"]',
                className: 'row-selected'
            },
            ajax: {
                url: "/user/ajax_data/table/get_commission_log",
            },
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.12.1/i18n/zh.json",
            },
            columns: [
                { data: 'ref_get' },
                { data: 'datetime'},
            ],
        });

    }
    return {
        init: function () {
            initDatatable();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTDatatablesUserGetCommissionLogSide.init();
});

// agent user list
var KTDatatablesAgentUserListSide = function () {
    var table;
    var dt;

    var initDatatable = function () {
        dt = $("#zero_agent_user_list_table").DataTable({
            searchDelay: 500,
            processing: true,
            serverSide: true,
            order: [[0, 'desc']],
            stateSave: true,
            select: {
                style: 'multi',
                selector: 'td:first-child input[type="checkbox"]',
                className: 'row-selected'
            },
            ajax: {
                url: "/user/agent/ajax_data/table/agent_user",
            },
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.12.1/i18n/zh.json",
            },
            columns: [
                { data: 'id' },
                { data: 'name' },
                { data: 'email'},
                { data: 'money'},
                { data: 'unusedTraffic'},
                { data: 'class_expire'},
                { data: 'actions'},
            ],
            columnDefs: [
                {
                    targets: -1,
                    orderable: false,
                    className: 'text-end',
                    render: function (data, type, row) {
                        return `
                            <div>
                                <a class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-flip="top-end">
                                    Actions
                                    <span class="svg-icon svg-icon-5 m-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                                <path d="M6.70710678,15.7071068 C6.31658249,16.0976311 5.68341751,16.0976311 5.29289322,15.7071068 C4.90236893,15.3165825 4.90236893,14.6834175 5.29289322,14.2928932 L11.2928932,8.29289322 C11.6714722,7.91431428 12.2810586,7.90106866 12.6757246,8.26284586 L18.6757246,13.7628459 C19.0828436,14.1360383 19.1103465,14.7686056 18.7371541,15.1757246 C18.3639617,15.5828436 17.7313944,15.6103465 17.3242754,15.2371541 L12.0300757,10.3841378 L6.70710678,15.7071068 Z" fill="currentColor" fill-rule="nonzero" transform="translate(12.000003, 11.999999) rotate(-180.000000) translate(-12.000003, -11.999999)"></path>
                                            </g>
                                        </svg>
                                    </span>
                                </a>
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="/user/agent/view/${row.id}" class="menu-link px-3">
                                            Edit
                                        </a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3">
                                            Delete
                                        </a>
                                    </div>
                                </div>
                            </div>
                        `;
                    },
                },
            ]
        });

    }
    var handleSearchDatatable = function () {
        const filterSearch = document.querySelector('[data-kt-docs-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            dt.search(e.target.value).draw();
        });
    }
    return {
        init: function () {
            initDatatable();
            handleSearchDatatable();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTDatatablesAgentUserListSide.init();
});

// agent withdraw log
var KTDatatablesAgentWithdrawCommissionLogSide = function () {
    var table;
    var dt;

    var initDatatable = function () {
        dt = $("#zero_agent_withdraw_commission_log_table").DataTable({
            searchDelay: 500,
            processing: true,
            serverSide: true,
            order: [[4, 'desc']],
            stateSave: true,
            select: {
                style: 'multi',
                selector: 'td:first-child input[type="checkbox"]',
                className: 'row-selected'
            },
            ajax: {
                url: "/user/agent/ajax_data/table/agent_withdraw_commission_log",
            },
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.12.1/i18n/zh.json",
            },
            columns: [
                { data: 'id' },
                { data: 'total' },
                { data: 'type'},
                { data: 'status'},
                { data: 'datetime'},
            ],
        });

    }
    return {
        init: function () {
            initDatatable();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTDatatablesAgentWithdrawCommissionLogSide.init();
});