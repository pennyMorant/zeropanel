<?php

namespace App\Middleware;

use App\Models\Setting;
use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class WebAPI
{
    public function __invoke(Request $request, RequestHandler $handler)
    {
        $key = $request->getParam('key');
        if (is_null($key)) {
            // 未提供 key
            $response = new Response();
            $response->getBody()->write(json_encode([
                'ret'  => 0,
                'data' => 'Your key is null.'
            ]));
            return $response;
        }
        $backend_token = Setting::obtain('website_backend_token');
        if ($key != $backend_token) {
            // key 不存在
            $response = new Response();
            $response->getBody()->write(json_encode([
                'ret'  => 0,
                'data' => 'Key is invalid'
            ]));
            return $response;
        }

        if ($_ENV['WebAPI'] === false) {
            // 主站不提供 WebAPI
            $response = new Response();
            $response->getBody()->write(json_encode([
                'ret'  => 0,
                'data' => 'WebAPI is disabled.'
            ]));
            return $response;
        }
        return $handler->handle($request);
    }
}
