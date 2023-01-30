
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
            order: [[3, 'desc']],
            stateSave: true,
            select: {
                style: 'multi',
                selector: 'td:first-child input[type="checkbox"]',
                className: 'row-selected'
            },
            ajax: {
                url: "/user/ajax_data/table/order",
            },
            
            columns: [
                { data: 'order_total' },
                { data: 'order_status' },
                { data: 'order_type' },
                { data: 'created_time' },
                { data: 'no' },
                { data: 'action'},
            ],
            
            columnDefs: [],           
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
            
            columns: [
                { data: 'id' },
                { data: 'title' },
                { data: 'status' },
                { data: 'datetime' },
                { data: 'action'},
            ],
            columnDefs: [],
            
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