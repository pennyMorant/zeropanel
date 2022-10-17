<?php

declare(strict_types=1);

use Slim\App;
use App\Error\HtmlError;

return static function (App $app) {
    if ($_ENV['debug'] == true) {
        $app->add(new Zeuxisoo\Whoops\Slim\WhoopsMiddleware());
    } else {
        $errorMiddleware = $app->addErrorMiddleware(false, true, true);
        // Get the default error handler and register my custom error renderer.
        $errorHandler = $errorMiddleware->getDefaultErrorHandler();
        $errorHandler->registerErrorRenderer('text/html', HtmlError::class);
    }
};
