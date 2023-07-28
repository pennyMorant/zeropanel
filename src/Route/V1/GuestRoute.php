<?php

use Slim\App;
use Slim\Routing\RouteCollectorProxy as Group;
use App\Middleware\Auth;
use App\Controllers\HomeController;
use App\Controllers\Guest\PaymentController;

return function (App $app) {
    $app->group('/payment', function (Group $group) {
        //Reconstructed Payment System
        $group->get('/return/{order_no}', PaymentController::class . ':return')->add(new Auth());
        $group->map(['GET', 'POST'], '/notify/{method}/{uuid}', PaymentController::class . ':notify');
    });
    
    $app->post('/telegram_callback', HomeController::class . ':telegram');
    $app->get('/', HomeController::class . ':index');
    $app->get('/404', HomeController::class . ':page404');
    $app->get('/405', HomeController::class . ':page405');
    $app->get('/500', HomeController::class . ':page500');
};