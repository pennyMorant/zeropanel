<?php

declare(strict_types=1);

define('BASE_PATH', __DIR__ . '/..');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/.config.php';
require __DIR__ . '/../app/i18next.php';

use App\Services\Boot;
use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
Boot::bootDb();

$containerBuilder = new ContainerBuilder();
$container = $containerBuilder->build();

AppFactory::setContainer($container);
$app = AppFactory::create();
$app->addBodyParsingMiddleware();
(require __DIR__ . '/../app/middleware.php')($app);
(require __DIR__ . '/../src/Route/V1/AdminRoute.php')($app);
(require __DIR__ . '/../src/Route/V1/ClientRoute.php')($app);
(require __DIR__ . '/../src/Route/V1/GuestRoute.php')($app);
(require __DIR__ . '/../src/Route/V1/UserRoute.php')($app);
(require __DIR__ . '/../src/Route/V1/PassRoute.php')($app);
(require __DIR__ . '/../src/Route/V1/WebAPIRoute.php')($app);
$app->run();