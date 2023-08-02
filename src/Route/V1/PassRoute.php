<?php

use Slim\App;
use Slim\Routing\RouteCollectorProxy as Group;
use App\Middleware\Auth;
use App\Middleware\Guest;
use App\Controllers\AuthController;
use App\Controllers\PasswordController;

return function (App $app) {
    $app->group('/auth', function (Group $group) {
        $group->get('/signin', AuthController::class . ':signInIndex');
        $group->post('/signin', AuthController::class . ':signInHandle');
        $group->get('/signup', AuthController::class . ':signUpIndex');
        $group->post('/signup', AuthController::class . ':signUpHandle');
    })->add(new Guest());

    $app->group('/password', function (Group $group) {
        $group->get('/reset', PasswordController::class . ':resetIndex');
        $group->post('/reset', PasswordController::class . ':handleReset');
        $group->get('/token', PasswordController::class . ':tokenIndex');
        $group->post('/token', PasswordController::class . ':handleToken');
    })->add(new Guest());
};