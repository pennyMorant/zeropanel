<?php

namespace App\Middleware;

use App\Models\Setting;
use Slim\Factory\AppFactory;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

final class WebAPI implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
        $key = $request->getQueryParams()['key'] ?? null;
        if (is_null($key)) {
            // 未提供 key
            return AppFactory::determineResponseFactory()->createResponse(401)->withJson(
                [
                    'ret' => 0,
                    'data' => 'Invalid request.',
                ]
            );
        }
        $backend_token = Setting::obtain('website_backend_token');
        if ($key != $backend_token) {
            // key 不存在
            return AppFactory::determineResponseFactory()->createResponse(401)->withJson([
                'ret' => 0,
                'data' => 'Invalid request.',
            ]);
        }

        if (!$_ENV['WebAPI']) {
            // 主站不提供 WebAPI
            return AppFactory::determineResponseFactory()->createResponse(401)->withJson([
                'ret' => 0,
                'data' => 'Invalid request.',
            ]);
        }
        return $handler->handle($request);
    }
}
