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
            scrollCollapse: true,
            scrollX: true,
            order: [[3, 'desc']],
            stateSave: true,
            select: {
                style: 'multi',
                selector: 'td:first-child input[type="checkbox"]',
                className: 'row-selected'
            },
            ajax: {
                url: "/user/ajax_data/table/order",
                type: "POST"
            },
            
            columns: [
                { data: 'order_total' },
                { data: 'order_status' },
                { data: 'order_type' },
                { data: 'created_at' },
                { data: 'action' },
            ],
            
            columnDefs: [
                { targets: -1, className: 'text-end' },
                { orderable: false, targets: '_all' }
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
            scrollX: true,
            scrollCollapse: true,
            order: [[3, 'desc']],
            stateSave: true,
            select: {
                style: 'multi',
                selector: 'td:first-child input[type="checkbox"]',
                className: 'row-selected'
            },
            ajax: {
                url: "/user/ajax_data/table/ticket",
                type: "POST"
            },
            
            columns: [
                { data: 'id' },
                { data: 'type'},
                { data: 'title' },
                { data: 'status' },
                { data: 'created_at' },
                { data: 'updated_at'},
                { data: 'action'},
            ],
            columnDefs: [
                { targets: -1, className: 'text-end' },
                { orderable: false, targets: '_all' }
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
            scrollX: true,
            scrollCollapse: true,
            order: [[2, 'desc']],
            stateSave: true,
            select: {
                style: 'multi',
                selector: 'td:first-child input[type="checkbox"]',
                className: 'row-selected'
            },
            ajax: {
                url: "/user/ajax_data/table/loginlog",
                type: "POST"
            },
            
            columns: [
                { data: 'ip' },
                { data: 'location' },
                { data: 'created_at' },
            ],

            columnDefs: [
                { targets: -1, className: 'text-end' },
                { orderable: false, targets: '_all' }
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
            scrollX: true,
            scrollCollapse: true,
            order: [[2, 'desc']],
            stateSave: true,
            select: {
                style: 'multi',
                selector: 'td:first-child input[type="checkbox"]',
                className: 'row-selected'
            },
            ajax: {
                url: "/user/ajax_data/table/uselog",
                type: "POST"
            },
            
            columns: [
                { data: 'ip' },
                { data: 'location' },
                { data: 'created_at' },
            ],

            columnDefs: [
                { targets: -1, className: 'text-end' },
                { orderable: false, targets: '_all' }
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
            scrollX: true,
            scrollCollapse: true,
            order: [[2, 'desc']],
            stateSave: true,
            select: {
                style: 'multi',
                selector: 'td:first-child input[type="checkbox"]',
                className: 'row-selected'
            },
            ajax: {
                url: "/user/ajax_data/table/sublog",
                type: "POST"
            },
            
            columns: [
                { data: 'request_ip' },
                { data: 'location'},
                { data: 'created_at' },
            ],
            columnDefs: [
                { targets: -1, className: 'text-end' },
                { orderable: false, targets: '_all' }   
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
            scrollX: true,
            scrollCollapse: true,
            order: [[2, 'desc']],
            stateSave: true,
            select: {
                style: 'multi',
                selector: 'td:first-child input[type="checkbox"]',
                className: 'row-selected'
            },
            ajax: {
                url: "/user/ajax_data/table/trafficlog",
                type: "POST"
            },
            
            columns: [
                { data: 'node_name' },
                { data: 'traffic' },
                { data: 'created_at' },
            ],

            columnDefs: [
                { targets: -1, className: 'text-end' },
                { orderable: false, targets: '_all' }
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
            scrollX: true,
            scrollCollapse: true,
            order: [[0, 'desc']],
            stateSave: true,
            select: {
                style: 'multi',
                selector: 'td:first-child input[type="checkbox"]',
                className: 'row-selected'
            },
            ajax: {
                url: "/user/ajax_data/table/ban_rule",
                type: "POST"
            },
            columns: [
                { data: 'name' },
                { data: 'text' },
            ],

            columnDefs: [
                { targets: -1, className: 'text-end' },
                { orderable: false, targets: '_all' }
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
            scrollX: true,
            scrollCollapse: true,
            order: [[2, 'desc']],
            stateSave: true,
            select: {
                style: 'multi',
                selector: 'td:first-child input[type="checkbox"]',
                className: 'row-selected'
            },
            ajax: {
                url: "/user/ajax_data/table/user_baned_log",
                type: "POST"
            },
            columns: [
                { data: 'node_name' },
                { data: 'rule_name' },
                { data: 'created_at'},
            ],

            columnDefs: [
                { targets: -1, className: 'text-end' },
                { orderable: false, targets: '_all' }
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
            scrollX: true,
            scrollCollapse: true,
            order: [[2, 'desc']],
            stateSave: true,
            select: {
                style: 'multi',
                selector: 'td:first-child input[type="checkbox"]',
                className: 'row-selected'
            },
            ajax: {
                url: "/user/ajax_data/table/get_commission_log",
                type: "POST"
            },
            
            columns: [
                { data: 'order_amount' },
                { data: 'get_amount' },
                { data: 'created_at' },
            ],

            columnDefs: [
                { targets: -1, className: 'text-end' },
                { orderable: false, targets: '_all' }
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