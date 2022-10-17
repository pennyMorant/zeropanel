<?php

declare(strict_types=1);
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/.config.php';
require __DIR__ . '/../config/appprofile.php';
require __DIR__ . '/../app/predefine.php';
require __DIR__ . '/../app/envload.php';
require __DIR__ . '/../config/.zeroconfig.php';

// TODO: legacy boot function
use App\Services\Boot;
use DI\ContainerBuilder;
use Slim\Factory\AppFactory;

require __DIR__ . '/../app/i18next.php';

Boot::setTime();
Boot::bootDb();

$containerBuilder = new ContainerBuilder();
$container = $containerBuilder->build();

AppFactory::setContainer($container);
$app = AppFactory::create();

/** @var closure $middleware */
$middleware = require __DIR__ . '/../app/middleware.php';
$middleware($app);

/** @var closure $routes */
$routes = require __DIR__ . '/../app/routes.php';
$routes($app);

$app->run();
