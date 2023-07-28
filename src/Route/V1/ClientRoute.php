<?php

declare(strict_types=1);

use Slim\App;
use Slim\Routing\RouteCollectorProxy as Group;
use App\Controllers\SubsController;

return function (App $app) {
    $app->group('/api', function (Group $group) {
        $group->get('/v1/client/subscribe', SubsController::class . ':subscribe');
    });
};