<?php

declare(strict_types=1);
use Slim\App;
use Slim\Routing\RouteCollectorProxy as Group;
use App\Middleware\Auth;
use App\Controllers\UserController;
use App\Controllers\OrderController;
use App\Controllers\User\NodeController;
use App\Controllers\User\ProductController;
use App\Controllers\User\TicketController;
use App\Controllers\ZeroController;
use App\Zero\Agent;

return function (App $app) {
    $app->group('/user', function (Group $group) {
        $group->get('/dashboard',                       UserController::class . ':index');
        $group->get('/knowledge',                       UserController::class . ':knowledge');
        $group->get('/referral',                        UserController::class . ':referral');
        $group->get('/profile',                         UserController::class . ':profile');
        $group->get('/record',                          UserController::class . ':record');
        $group->get('/ban',                             UserController::class . ':ban');
        // 订单系统
        $group->get('/order',                           OrderController::class . ':order');
        $group->get('/order/{no}',                      OrderController::class . ':orderDetails');
        $group->post('/order/create_order/{type}',      OrderController::class . ':createOrder');
        $group->post('/order/pay_order',                OrderController::class . ':processOrder');
        $group->post('/verify_coupon',                  OrderController::class . ':verifyCoupon');

        // node
        $group->get('/node',                            NodeController::class . ':nodeIndex');
        $group->get('/nodeinfo/{id}',                   ZeroController::class . ':nodeInfo');

        // product
        $group->get('/product',                         ProductController::class . ':productIndex');
        $group->post('/product/getinfo',                ProductController::class . ':getProductInfo');

        // ticket
        $group->get('/ticket',                          TicketController::class . ':ticketIndex');
        $group->post('/ticket/create',                  TicketController::class . ':createTicket');
        $group->get('/ticket/view/{id}',                TicketController::class . ':ticketViewIndex');
        $group->put('/ticket/update',                   TicketController::class . ':updateTicket');
        
        // update profile
        $group->post('/update_profile/{type}',          UserController::class . ':updateProfile');
        $group->post('/enable_notify',                  UserController::class . ':enableNotify');
        $group->post('/kill',                           UserController::class . ':handleKill');
        $group->get('/logout',                          UserController::class . ':logout');
        
        // table
        $group->post('/ajax_data/table/{name}',          ZeroController::class . ':ajaxDatatable');
        $group->post('/ajax_data/chart/{name}',          ZeroController::class . ':ajaxDataChart');
        $group->delete('/ajax_data/delete',             ZeroController::class . ':ajaxDatatableDelete');
        $group->post('/withdraw_commission',            ZeroController::class . ':withdrawCommission');
        $group->post('/withdraw_account_setting',       ZeroController::class . ':withdrawAccountSettings');

        // Agent
        $group->get('/agent/ajax_data/table/{name}',        Agent::class . ':ajaxDatatable');
        $group->get('/agent/ajax_data/chart/{name}',        Agent::class . ':ajaxChart');
        $group->post('/agent/withdraw_commission',          Agent::class . ':withdraw');
        $group->post('/agent/withdraw_account_setting',     Agent::class . ':withdrawAccountSettings');
        $group->post('/agent_data/process/{name}',          Agent::class . ':ajaxDatatableProcess');
    })->add(new Auth());

    $app->map(['GET', 'POST'], '/user/verify/email/{action}',           UserController::class . ':verifyEmail');
};