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
use App\Controllers\Admin\KnowledgeController;
use App\Middleware\Admin;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (SlimApp $app) {
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
            $order->post('/create',           OrderController::class . ':createOrder');
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
            $user->put('/update/status/{type}',   UserController::class . ':updateUserStatus');
        });

        $group->group('/coupon', function($coupon) {
            $coupon->get('',                   CouponController::class . ':couponIndex');
            $coupon->post('/create',           CouponController::class . ':createCoupon');
            $coupon->post('/ajax',             CouponController::class . ':couponAjax');
            $coupon->delete('/delete',         CouponController::class . ':deleteCoupon');
        });

        // 设置中心
        $group->group('/setting', function($setting) {
            $setting->get('',                  SettingController::class . ':index');
            $setting->post('',                 SettingController::class . ':save');
            $setting->post('/email',           SettingController::class . ':test');
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
            $payment->post('/getinfo',              PaymentController::class . ':getInfoPayment');
            $payment->delete('/delete',             PaymentController::class . ':deletePayment');
            $payment->put('/enable',                PaymentController::class . ':enablePayment');
        });

        // knowledge 
        $group->group('/knowledge', function($knowledge) {
            $knowledge->get('',                       KnowledgeController::class . ':knowledgeIndex');
            $knowledge->post('/ajax',                 KnowledgeController::class . ':knowledgeAjax');
            $knowledge->post('/create',               KnowledgeController::class . ':createKnowledge');
            $knowledge->post('/update',               KnowledgeController::class . ':updateKnowledge');
            $knowledge->post('/getinfo',              KnowledgeController::class . ':getInfoKnowledge');
            $knowledge->delete('/delete',             KnowledgeController::class . ':deleteKnowledge');
        });
    })->add(new Admin());
};