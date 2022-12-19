<?php

declare(strict_types=1);

use App\Controllers\ZeroController;
use App\Controllers\HomeController;
use Slim\App as SlimApp;
use App\Middleware\{Guest, Admin, Auth, WebAPI};
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (SlimApp $app) {
    // Home
    $app->get('/',          App\Controllers\HomeController::class . ':index');
    $app->get('/404',       App\Controllers\HomeController::class . ':page404');
    $app->get('/405',       App\Controllers\HomeController::class . ':page405');
    $app->get('/500',       App\Controllers\HomeController::class . ':page500');

    $app->post('/notify',               App\Controllers\HomeController::class . ':notify');

    // Telegram
    $app->post('/telegram_callback',    App\Controllers\HomeController::class . ':telegram');

    // User Center
    $app->group('/user', function (Group $group) {
        $group->get('',                          App\Controllers\UserController::class . ':index');
        $group->get('/',                         App\Controllers\UserController::class . ':index');

        $group->post('/getusertrafficinfo',      App\Controllers\UserController::class . ':getUserTrafficUsage');
        $group->get('/tutorial',                 App\Controllers\UserController::class . ':tutorial');
        $group->get('/referral',                 App\Controllers\UserController::class . ':referral');
        $group->get('/profile',                  App\Controllers\UserController::class . ':profile');
        $group->get('/record',                   App\Controllers\UserController::class . ':record');
        $group->get('/ban',                      App\Controllers\UserController::class . ':ban');
        $group->get('/shared_account',           App\Controllers\UserController::class . ':SharedAccount');
        // 订单系统
        $group->get('/order',                           App\Controllers\OrderController::class . ':order');
        $group->get('/order/{no}',                      App\Controllers\OrderController::class . ':orderDetails');
        $group->post('/order/create_order/{type}',       App\Controllers\OrderController::class . ':createOrder');
        $group->post('/order/pay_order',                App\Controllers\OrderController::class . ':processOrder');
        $group->post('/verify_coupon',            App\Controllers\OrderController::class . ':verifyCoupon');

        $group->get('/disable',                  App\Controllers\UserController::class . ':disable');
        $group->get('/node',                     App\Controllers\User\NodeController::class . ':node');

        $group->get('/product',                  App\Controllers\User\ProductController::class . ':product');

        $group->get('/ticket',                   App\Controllers\User\TicketController::class . ':ticket');
        $group->get('/ticket/create',            App\Controllers\User\TicketController::class . ':ticketCreate');
        $group->post('/ticket',                  App\Controllers\User\TicketController::class . ':ticketAdd');
        $group->get('/ticket/{id}/view',         App\Controllers\User\TicketController::class . ':ticketView');
        $group->put('/ticket/{id}',              App\Controllers\User\TicketController::class . ':ticketUpdate');
        
        $group->post('/update_email',            App\Controllers\UserController::class . ':updateEmail');
        $group->post('/update_name',             App\Controllers\UserController::class . ':updateUserName');
        $group->post('/update_password',         App\Controllers\UserController::class . ':updatePassword');
        $group->post('/send',                    App\Controllers\AuthController::class . ':sendVerify');
        $group->post('/reset_sub_link',          App\Controllers\UserController::class . ':resetSubLink');
        $group->get('/reset_referral_code',      App\Controllers\UserController::class . ':resetReferralCode');
        $group->post('/mail',                    App\Controllers\UserController::class . ':updateMail');
        $group->post('/reset_uuid',              App\Controllers\UserController::class . ':resetUUID');
        $group->post('/reset_passwd',            App\Controllers\UserController::class . ':resetPasswd');
        $group->post('/enable_notify',           App\Controllers\UserController::class . ':enableNotify');
        $group->get('/sys',                      App\Controllers\UserController::class . ':sys');
        $group->get('/trafficlog',               App\Controllers\UserController::class . ':trafficLog');
        $group->post('/kill',                    App\Controllers\UserController::class . ':handleKill');
        $group->get('/logout',                   App\Controllers\UserController::class . ':logout');
        $group->get('/backtoadmin',              App\Controllers\UserController::class . ':backToAdmin');
        $group->post('/code',                    App\Controllers\UserController::class . 'redeemCode');
        $group->get('/unbind_telegram',          App\Controllers\UserController::class . ':unbindTelegram');
        
        

        // getUserAllURL
        $group->get('/getUserAllURL',            App\Controllers\UserController::class . ':getUserAllURL');

        $group->post('/code/f2fpay',             App\Services\Payment::class . ':purchase');

        //Reconstructed Payment System
        $group->post('/payment/purchase',        App\Services\Payment::class . ':purchase');
        $group->get('/payment/return',           App\Services\Payment::class . ':returnHTML');

        # Zero
        
        $group->get('/nodeinfo/{id}',            App\Controllers\ZeroController::class . ':NodeInfo');
        $group->get('/money',                    App\Controllers\ZeroController::class . ':getmoney');
        $group->get('/ajax_data/table/{name}',   App\Controllers\ZeroController::class . ':ajaxDatatable');
        $group->get('/ajax_data/chart/{name}',   App\Controllers\ZeroController::class . ':ajaxDataChart');
        $group->delete('/ajax_data/delete',      App\Controllers\ZeroController::class . ':ajaxDatatableDelete');

        // Agent
        $group->get('/agent',                        App\Zero\Agent::class . ':pages');
        $group->get('/agent/adduser',                App\Zero\Agent::class . ':addUser');
        $group->get('/agent/view/{id}',              App\Zero\Agent::class . ':editUser');
        $group->get('/agent/ajax_data/table/{name}',      App\Zero\Agent::class . ':ajaxDatatable');
        $group->get('/agent/ajax_data/chart/{name}',      App\Zero\Agent::class . ':ajaxChart');
        $group->post('/agent/adduser',               App\Zero\Agent::class . ':addUserSave');
        $group->post('/agent/view/{id}',             App\Zero\Agent::class . ':editUserSave');
        $group->post('/agent/withdraw_commission',            App\Zero\Agent::class . ':withdraw');
        $group->post('/agent/withdraw_account_setting',  App\Zero\Agent::class . ':withdrawAccountSettings');
        $group->post('/agent_data/process/{name}',   App\Zero\Agent::class . ':ajaxDatatableProcess');
        
        $group->post('/purchase_sales_agent',        App\Zero\Agent::class . ':purchaseSalesAgent');

        $group->delete('/agent_data/delete',         App\Zero\Agent::class . ':delete');
    })->add(new Auth());

    $app->group('/payment', function (Group $group) {
        $group->get('/notify',                  App\Services\Payment::class . ':notify');
        $group->post('/notify',                 App\Services\Payment::class . ':notify');
        $group->get('/notify/{type}',           App\Services\Payment::class . ':notify');
        $group->post('/notify/{type}',          App\Services\Payment::class . ':notify');
        $group->get('/notify/{type}/{method}',  App\Services\Payment::class . ':notify');
        $group->post('/notify/{type}/{method}', App\Services\Payment::class . ':notify');
        $group->post('/status',                 App\Services\Payment::class . ':getStatus');
    });

    // Auth
    $app->group('/auth', function (Group $group) {
        $group->get('/signin',           App\Controllers\AuthController::class . ':login');
        $group->post('/qrcode_check',    App\Controllers\AuthController::class . ':qrcode_check');
        $group->post('/login',           App\Controllers\AuthController::class . ':loginHandle');
        $group->post('/qrcode_login',    App\Controllers\AuthController::class . ':qrcode_loginHandle');
        $group->get('/signup',           App\Controllers\AuthController::class . ':signUp');
        $group->post('/register',        App\Controllers\AuthController::class . ':registerHandle');
        $group->post('/send',            App\Controllers\AuthController::class . ':sendVerify');
        $group->get('/logout',           App\Controllers\AuthController::class . ':logout');
        $group->get('/login_getCaptcha', App\Controllers\AuthController::class . ':getCaptcha');
    })->add(new Guest());

    // Password
    $app->group('/password', function (Group $group) {
        $group->get('/reset',            App\Controllers\PasswordController::class . ':reset');
        $group->post('/reset',           App\Controllers\PasswordController::class . ':handleReset');
        $group->get('/token/{token}',    App\Controllers\PasswordController::class . ':token');
        $group->post('/token/{token}',   App\Controllers\PasswordController::class . ':handleToken');
    })->add(new Guest());

    // Admin
    $app->group('/admin', function (Group $group) {
        $group->get('',                          App\Controllers\AdminController::class . ':index');
        $group->get('/',                         App\Controllers\AdminController::class . ':index');

        $group->get('/log/{name}',               App\Controllers\Admin\LogController::class . ':index');
        $group->post('/log/ajax/{name}',         App\Controllers\Admin\LogController::class . ':ajax');
        // Node Mange
        $group->get('/node',                     App\Controllers\Admin\NodeController::class . ':index');

        $group->get('/node/create',              App\Controllers\Admin\NodeController::class . ':create');
        $group->post('/node',                    App\Controllers\Admin\NodeController::class . ':add');
        $group->get('/node/{id}/edit',           App\Controllers\Admin\NodeController::class . ':edit');
        $group->put('/node/{id}',                App\Controllers\Admin\NodeController::class . ':update');
        $group->delete('/node',                  App\Controllers\Admin\NodeController::class . ':delete');
        $group->post('/node/ajax',               App\Controllers\Admin\NodeController::class . ':ajax');


        $group->get('/ticket',                   App\Controllers\Admin\TicketController::class . ':index');
        $group->get('/ticket/{id}/view',         App\Controllers\Admin\TicketController::class . ':show');
        $group->put('/ticket/{id}',              App\Controllers\Admin\TicketController::class . ':update');
        $group->post('/ticket/ajax',             App\Controllers\Admin\TicketController::class . ':ajax');

        // Shop Mange
        $group->get('/shop',                     App\Controllers\Admin\ProductController::class . ':index');
        $group->post('/shop/ajax',               App\Controllers\Admin\ProductController::class . ':ajaxShop');

        $group->get('/bought',                   App\Controllers\Admin\ProductController::class . ':bought');
        $group->delete('/bought',                App\Controllers\Admin\ProductController::class . ':deleteBoughtGet');
        $group->post('/bought/ajax',             App\Controllers\Admin\ProductController::class . ':ajaxBought');
        
        $group->get('/order',                   App\Controllers\Admin\OrderController::class . ':index');
        $group->post('/order/ajax',             App\Controllers\Admin\OrderController::class . ':ajaxOrder');

        $group->get('/shop/create',              App\Controllers\Admin\ProductController::class . ':create');
        $group->post('/shop',                    App\Controllers\Admin\ProductController::class . ':add');
        $group->get('/shop/{id}/edit',           App\Controllers\Admin\ProductController::class . ':edit');
        $group->put('/shop/{id}',                App\Controllers\Admin\ProductController::class . ':update');
        $group->delete('/shop',                  App\Controllers\Admin\ProductController::class . ':deleteGet');
        

        // Ann Mange
        $group->get('/announcement',             App\Controllers\Admin\AnnController::class . ':index');
        $group->get('/announcement/create',      App\Controllers\Admin\AnnController::class . ':create');
        $group->post('/announcement',            App\Controllers\Admin\AnnController::class . ':add');
        $group->get('/announcement/{id}/edit',   App\Controllers\Admin\AnnController::class . ':edit');
        $group->put('/announcement/{id}',        App\Controllers\Admin\AnnController::class . ':update');
        $group->delete('/announcement',          App\Controllers\Admin\AnnController::class . ':delete');
        $group->post('/announcement/ajax',       App\Controllers\Admin\AnnController::class . ':ajax');

        // IP Mange
        $group->get('/login',                    App\Controllers\Admin\IpController::class . ':index');
        $group->get('/alive',                    App\Controllers\Admin\IpController::class . ':alive');
        $group->post('/login/ajax',              App\Controllers\Admin\IpController::class . ':ajaxLogin');
        $group->post('/alive/ajax',              App\Controllers\Admin\IpController::class . ':ajaxAlive');


        // User Mange
        $group->get('/user',                     App\Controllers\Admin\UserController::class . ':index');
        $group->get('/user/{id}/edit',           App\Controllers\Admin\UserController::class . ':edit');
        $group->put('/user/{id}',                App\Controllers\Admin\UserController::class . ':update');
        $group->delete('/user',                  App\Controllers\Admin\UserController::class . ':delete');
        $group->post('/user/changetouser',       App\Controllers\Admin\UserController::class . ':changetouser');
        $group->post('/user/ajax',               App\Controllers\Admin\UserController::class . ':ajax');
        $group->post('/user/create',             App\Controllers\Admin\UserController::class . ':createNewUser');
        $group->post('/user/buy',                App\Controllers\Admin\UserController::class . ':buy');


        $group->get('/coupon',                   App\Controllers\AdminController::class . ':coupon');
        $group->post('/coupon',                  App\Controllers\AdminController::class . ':addCoupon');
        $group->post('/coupon/ajax',             App\Controllers\AdminController::class . ':ajaxCoupon');

        $group->get('/profile',                  App\Controllers\AdminController::class . ':profile');
        $group->get('/invite',                   App\Controllers\AdminController::class . ':invite');
        $group->post('/invite',                  App\Controllers\AdminController::class . ':addInvite');
        $group->post('/chginvite',               App\Controllers\AdminController::class . ':chgInvite');
        $group->get('/sys',                      App\Controllers\AdminController::class . ':sys');
        $group->get('/logout',                   App\Controllers\AdminController::class . ':logout');
        $group->post('/payback/ajax',            App\Controllers\AdminController::class . ':ajaxPayBack');

        // Subscribe Log Mange
        $group->get('/subscribe',                App\Controllers\Admin\SubscribeLogController::class . ':index');
        $group->post('/subscribe/ajax',          App\Controllers\Admin\SubscribeLogController::class . ':ajaxSubscribeLog');
       
        // 设置中心
        $group->get('/setting',                  App\Controllers\Admin\SettingController::class . ':index');
        $group->post('/setting',                 App\Controllers\Admin\SettingController::class . ':save');
        $group->post('/setting/email',           App\Controllers\Admin\SettingController::class . ':test');
        $group->post('/setting/payment',         App\Controllers\Admin\SettingController::class . ':payment');

        // admin 增加收入和新用户统计
        $group->post('/api/analytics/income',     App\Controllers\AdminController::class . ':getIncome');
        $group->post('/api/analytics/new-users',  App\Controllers\AdminController::class . ':newUsers');

        // Agent
        $group->group('/agent', function (Group $group) {
            $group->get('/take_log',             App\Controllers\Admin\AgentController::class . ':takeLog');
            $group->put('/take_update/{mode}',   App\Controllers\Admin\AgentController::class . ':takeUpdate');
            $group->post('/take_ajax',           App\Controllers\Admin\AgentController::class . ':ajaxTake');
        });
    })->add(new Admin());

    // webapi
    $app->group('/mod_mu', function (Group $group) {
        $group->get('/nodes/{id}/info',      App\Controllers\WebAPI\NodeController::class . ':getInfo');
        $group->get('/users',                App\Controllers\WebAPI\UserController::class . ':index');
        $group->post('/users/traffic',       App\Controllers\WebAPI\UserController::class . ':addTraffic');
        $group->post('/users/aliveip',       App\Controllers\WebAPI\UserController::class . ':addAliveIp');
        $group->post('/nodes/{id}/info',     App\Controllers\WebAPI\NodeController::class . ':info');
        $group->get('/nodes',                App\Controllers\WebAPI\NodeController::class . ':getAllInfo');
        $group->post('/nodes/config',        App\Controllers\WebAPI\NodeController::class . ':getConfig');
        $group->get('/func/ping',            App\Controllers\WebAPI\FuncController::class . ':ping');
    })->add(new WebAPI());

    $app->group('/link', function (Group $group) {
        $group->get('/{token}',          App\Controllers\LinkController::class . ':GetContent');
    });
    
    // 通用订阅
    $app->group('/sub', function (Group $group) {
        $group->get('/{token}/{subtype}',    App\Controllers\SubController::class . ':getContent');
    });

    $app->group('/user', function (Group $group) {
        $group->post('/doiam',           App\Services\Payment::class . ':purchase');
    })->add(new Auth());

    $app->group('/doiam', function (Group $group) {
        $group->post('/callback/{type}', App\Services\Payment::class . ':notify');
        $group->get('/return/alipay',    App\Services\Payment::class . ':returnHTML');
        $group->post('/status',          App\Services\Payment::class . ':getStatus');
    });
    
};