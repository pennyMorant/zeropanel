<?php

declare(strict_types=1);

use Slim\App as SlimApp;
use App\Models\Setting;
use App\Controllers\Admin\NodeController;
use App\Controllers\Admin\TicketController;
use App\Controllers\Admin\ProductController;
use App\Controllers\Admin\OrderController;
use App\Controllers\Admin\AnnController;
use App\Controllers\Admin\BanController;
use App\Controllers\Admin\RecordController;
use App\Controllers\Admin\UserController;
use App\Controllers\Admin\CouponController;
use App\Controllers\Admin\SettingController;
use App\Controllers\Admin\CommissionController;
use App\Controllers\Admin\PaymentController;
use App\Middleware\{
    Guest, 
    Admin, 
    Auth, 
    WebAPI
};
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (SlimApp $app) {
    // Home
    $app->get('/',          App\Controllers\HomeController::class . ':index');
    $app->get('/404',       App\Controllers\HomeController::class . ':page404');
    $app->get('/405',       App\Controllers\HomeController::class . ':page405');
    $app->get('/500',       App\Controllers\HomeController::class . ':page500');

    // Telegram
    $app->post('/telegram_callback',                    App\Controllers\HomeController::class . ':telegram');

    // User Center
    $app->group('/user', function (Group $group) {
        $group->get('/dashboard',                       App\Controllers\UserController::class . ':index');
        $group->get('/tutorial',                        App\Controllers\UserController::class . ':tutorial');
        $group->get('/referral',                        App\Controllers\UserController::class . ':referral');
        $group->get('/profile',                         App\Controllers\UserController::class . ':profile');
        $group->get('/record',                          App\Controllers\UserController::class . ':record');
        $group->get('/ban',                             App\Controllers\UserController::class . ':ban');
        // 订单系统
        $group->get('/order',                           App\Controllers\OrderController::class . ':order');
        $group->get('/order/{no}',                      App\Controllers\OrderController::class . ':orderDetails');
        $group->post('/order/create_order/{type}',      App\Controllers\OrderController::class . ':createOrder');
        $group->post('/order/pay_order',                App\Controllers\OrderController::class . ':processOrder');
        $group->post('/verify_coupon',                  App\Controllers\OrderController::class . ':verifyCoupon');

        $group->get('/node',                            App\Controllers\User\NodeController::class . ':node');

        $group->get('/product',                         App\Controllers\User\ProductController::class . ':product');
        $group->post('/product/getinfo',                App\Controllers\User\ProductController::class . ':getProductInfo');
        $group->post('/product/renewal',                App\Controllers\User\ProductController::class . ':renewalProduct');

        $group->get('/ticket',                          App\Controllers\User\TicketController::class . ':ticketIndex');
        $group->post('/ticket/create',                  App\Controllers\User\TicketController::class . ':createTicket');
        $group->get('/ticket/view/{id}',                App\Controllers\User\TicketController::class . ':ticketViewIndex');
        $group->put('/ticket/update',                   App\Controllers\User\TicketController::class . ':updateTicket');
        
        $group->post('/update_profile/{type}',          App\Controllers\UserController::class . ':updateProfile');
        $group->post('/mail',                           App\Controllers\UserController::class . ':updateMail');
        $group->post('/enable_notify',                  App\Controllers\UserController::class . ':enableNotify');
        $group->get('/trafficlog',                      App\Controllers\UserController::class . ':trafficLog');
        $group->post('/kill',                           App\Controllers\UserController::class . ':handleKill');
        $group->get('/logout',                          App\Controllers\UserController::class . ':logout');
        
        

        // getUserAllURL
        $group->get('/getUserAllURL',                   App\Controllers\UserController::class . ':getUserAllURL');
        
        $group->get('/nodeinfo/{id}',                   App\Controllers\ZeroController::class . ':nodeInfo');
        $group->get('/money',                           App\Controllers\ZeroController::class . ':getmoney');
        $group->get('/ajax_data/table/{name}',          App\Controllers\ZeroController::class . ':ajaxDatatable');
        $group->get('/ajax_data/chart/{name}',          App\Controllers\ZeroController::class . ':ajaxDataChart');
        $group->delete('/ajax_data/delete',             App\Controllers\ZeroController::class . ':ajaxDatatableDelete');
        $group->post('/withdraw_commission',            App\Controllers\ZeroController::class . ':withdrawCommission');
        $group->post('/withdraw_account_setting',       App\Controllers\ZeroController::class . ':withdrawAccountSettings');

        // Agent
        $group->get('/agent/ajax_data/table/{name}',        App\Zero\Agent::class . ':ajaxDatatable');
        $group->get('/agent/ajax_data/chart/{name}',        App\Zero\Agent::class . ':ajaxChart');
        $group->post('/agent/withdraw_commission',          App\Zero\Agent::class . ':withdraw');
        $group->post('/agent/withdraw_account_setting',     App\Zero\Agent::class . ':withdrawAccountSettings');
        $group->post('/agent_data/process/{name}',          App\Zero\Agent::class . ':ajaxDatatableProcess');
    })->add(new Auth());

    $app->group('/payment', function (Group $group) {
        //Reconstructed Payment System
        $group->get('/return',                                   App\Controllers\Guest\PaymentController::class . ':return')->add(new Auth());
        $group->map(['GET', 'POST'], '/notify/{method}/{uuid}',  App\Controllers\Guest\PaymentController::class . ':notify');
    });

    // Auth
    $app->group('/auth', function (Group $group) {
        $group->get('/signin',              App\Controllers\AuthController::class . ':signInIndex');
        $group->post('/signin',             App\Controllers\AuthController::class . ':signInHandle');
        $group->get('/signup',              App\Controllers\AuthController::class . ':signUpIndex');
        $group->post('/signup',             App\Controllers\AuthController::class . ':signUpHandle');
    })->add(new Guest());

    // Password
    $app->group('/password', function (Group $group) {
        $group->get('/reset',            App\Controllers\PasswordController::class . ':resetIndex');
        $group->post('/reset',           App\Controllers\PasswordController::class . ':handleReset');
        $group->get('/token',            App\Controllers\PasswordController::class . ':tokenIndex');
        $group->post('/token',           App\Controllers\PasswordController::class . ':handleToken');
    })->add(new Guest());

    // Admin
    $admin_path = Setting::obtain('website_admin_path');
    $app->group('/' . $admin_path, function (Group $group) {
        $group->get('/dashboard',                   App\Controllers\AdminController::class . ':index');

        // admin 增加收入和新用户统计
        $group->post('/ajax_data/chart/{name}',        App\Controllers\AdminController::class . ':AjaxDataChart');

        // Node Mange
        $group->group('/node', function($node) {
            $node->get('',                        NodeController::class . ':index');
            $node->get('/create',                 NodeController::class . ':createNodeIndex');
            $node->post('/create',                NodeController::class . ':createNode');
            $node->get('/update/{id}',            NodeController::class . ':updateNodeIndex');
            $node->put('/update',                 NodeController::class . ':updateNode');
            $node->delete('/delete',              NodeController::class . ':deleteNode');
            $node->post('/ajax',                  NodeController::class . ':nodeAjax');
            $node->put('/update/status',          NodeController::class . ':updateNodeStatus');
        });

        //ticket
        $group->group('/ticket', function($ticket) {
            $ticket->get('',                       TicketController::class . ':ticketIndex');
            $ticket->post('/create',               TicketController::class . ':createTicket');
            $ticket->get('/view/{id}',             TicketController::class . ':ticketViewIndex');
            $ticket->put('/update',                TicketController::class . ':updateTicket');
            $ticket->post('/ajax',                 TicketController::class . ':ticketAjax');
            $ticket->delete('/delete',             TicketController::class . ':deleteTicket');
            $ticket->put('/close',                 TicketController::class . ':closeTicket');
        });

        // Product Mange
        $group->group('/product', function($product) {
            $product->get('',                     ProductController::class . ':index');
            $product->post('/ajax',               ProductController::class . ':productAjax');
            $product->get('/create',              ProductController::class . ':createProductIndex');
            $product->post('/create',             ProductController::class . ':createProduct');
            $product->get('/update/{id}',         ProductController::class . ':updateProductIndex');
            $product->put('/update',              ProductController::class . ':updateProduct');
            $product->delete('/delete',           ProductController::class . ':deleteProduct');
            $product->put('/update/status',       ProductController::class . ':updateProductStatus');
            $product->post('/getinfo',            ProductController::class . ':getProductInfo');
        });

        // order
        $group->group('/order', function($order) {
            $order->get('',                   OrderController::class . ':index');
            $order->get('/{no}',              OrderController::class . ':orderDetailIndex');
            $order->post('/ajax',             OrderController::class . ':ajaxOrder');
            $order->delete('/delete',         OrderController::class . ':deleteOrder');
            $order->put('/complete',          OrderController::class . ':completeOrder');
        });

        // news
        $group->group('/news', function($news) {
            $news->get('',               AnnController::class . ':index');
            $news->post('/create',       AnnController::class . ':createNews');
            $news->put('/update',        AnnController::class . ':updateNews');
            $news->delete('/delete',     AnnController::class . ':deleteNews');
            $news->post('/ajax',         AnnController::class . ':ajax');
            $news->post('/request',      AnnController::class . ':requestNews');
        });

        // Detect Mange
        $group->group('/ban', function($ban) {
            $ban->get('',                      BanController::class . ':index');
            $ban->post('/rule/create',         BanController::class . ':createBanRule');
            $ban->put('/rule/update',          BanController::class . ':updateBanRule');
            $ban->post('/detect/record/ajax',  BanController::class . ':detectRuleRecordAjax');
            $ban->post('/rule/ajax',           BanController::class . ':banRuleAjax');
            $ban->post('/record/ajax',         BanController::class . ':banRecordAjax');
            $ban->post('/rule/request',        BanController::class . ':requestBanRule');
            $ban->delete('/rule/delete',       BanController::class . ':deleteBanRule');
        });

        // record Mange
        $group->group('/record', function($record) {
            $record->get('',                    RecordController::class . ':recordIndex');
            $record->post('/ajax/{type}',       RecordController::class . ':recordAjax');
        });

        // User Mange
        $group->group('/user', function($user) {
            $user->get('',                        UserController::class . ':index');
            $user->get('/update/{id}',            UserController::class . ':updateUserIndex');
            $user->put('/update',                 UserController::class . ':updateUser');
            $user->delete('/delete',              UserController::class . ':deleteUser');
            $user->post('/ajax',                  UserController::class . ':ajax');
            $user->post('/create',                UserController::class . ':createNewUser');
            $user->post('/buy',                   UserController::class . ':buy');
            $user->put('/update/status/{type}',   UserController::class . ':updateUserStatus');
        });

        $group->group('/coupon', function($coupon) {
            $coupon->get('',                   CouponController::class . ':couponIndex');
            $coupon->post('/create',           CouponController::class . ':createCoupon');
            $coupon->post('/ajax',             CouponController::class . ':couponAjax');
        });

        // 设置中心
        $group->group('/setting', function($setting) {
            $setting->get('',                  SettingController::class . ':index');
            $setting->post('',                 SettingController::class . ':save');
            $setting->post('/email',           SettingController::class . ':test');
            $setting->post('/payment',         SettingController::class . ':payment');
        });

        // 佣金
        $group->group('/commission', function($commission) {
            $commission->get('',                      CommissionController::class . ':commissionIndex');
            $commission->put('/withdraw/update',      CommissionController::class . ':updateWithdrawCommission');
            $commission->post('/withdraw/ajax',       CommissionController::class . ':withdrawAjax');
            $commission->post('/ajax',                CommissionController::class . ':commissionAjax');
        });

        //payment
        $group->group('/payment', function($payment) {
            $payment->get('',                       PaymentController::class . ':paymentIndex');
            $payment->post('/create',               PaymentController::class . ':createPayment');
            $payment->post('/update',               PaymentController::class . ':updatePayment');
            $payment->post('/ajax',                 PaymentController::class . ':paymentAjax');
            $payment->get('/config',                PaymentController::class . ':getPaymentConfig');
            $payment->delete('/delete',             PaymentController::class . ':deletePayment');
            $payment->put('/enable',                PaymentController::class . ':enablePayment');
        });
    })->add(new Admin());

    // webapi
    $app->group('/mod_mu', function (Group $group) {
        $group->get('/nodes/{id}/info',      App\Controllers\WebAPI\NodeController::class . ':getInfo');
        $group->get('/users',                App\Controllers\WebAPI\UserController::class . ':index');
        $group->post('/users/traffic',       App\Controllers\WebAPI\UserController::class . ':addTraffic');
        $group->post('/users/aliveip',       App\Controllers\WebAPI\UserController::class . ':addAliveIp');
        $group->post('/users/detectlog',     App\Controllers\WebAPI\UserController::class . ':addDetectLog');
        $group->post('/nodes/{id}/info',     App\Controllers\WebAPI\NodeController::class . ':info');
        $group->get('/nodes',                App\Controllers\WebAPI\NodeController::class . ':getAllInfo');
        $group->post('/nodes/config',        App\Controllers\WebAPI\NodeController::class . ':getConfig');
        $group->get('/func/ping',            App\Controllers\WebAPI\FuncController::class . ':ping');
        $group->get('/func/detect_rules',    App\Controllers\WebAPI\FuncController::class . ':getDetectLogs');
    })->add(new WebAPI());

    $app->group('/link', function (Group $group) {
        $group->get('/{token}',          App\Controllers\LinkController::class . ':GetContent');
    });  
};