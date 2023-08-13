<?php

declare(strict_types=1);
use Slim\App;
use Slim\Routing\RouteCollectorProxy as Group;
use App\Middleware\WebAPI;
use App\Controllers\WebAPI\NodeController;
use App\Controllers\WebAPI\UserController;
use App\Controllers\WebAPI\FuncController;

return function (App $app) {
    $app->group('/api/v1/server', function (Group $group) {
        $group->get('/nodes/{id}/info',      NodeController::class . ':getInfo');
        $group->get('/users',                UserController::class . ':index');
        $group->post('/users/traffic',       UserController::class . ':addTraffic');
        $group->post('/users/aliveip',       UserController::class . ':addAliveIp');
        $group->post('/users/detectlog',     UserController::class . ':addDetectLog');
        $group->post('/nodes/{id}/info',     NodeController::class . ':info');
        $group->get('/nodes',                NodeController::class . ':getAllInfo');
        $group->post('/nodes/config',        NodeController::class . ':getConfig');
        $group->get('/func/detect_rules',    FuncController::class . ':getDetectLogs');
    })->add(new WebAPI());
};