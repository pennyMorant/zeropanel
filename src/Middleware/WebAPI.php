<?php

namespace App\Middleware;

use App\Services\Config;
use App\Models\Node;
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

        if (!in_array($key, Config::getMuKey())) {
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

        if ($_ENV['checkNodeIp'] === true) {
            if ($_SERVER['REMOTE_ADDR'] != '127.0.0.1') {
                $node = Node::where('node_ip', 'LIKE', $_SERVER['REMOTE_ADDR'] . '%')->first();
                if (is_null($node)) {
                    $response = new Response();
                    $response->getBody()->write(json_encode([
                        'ret'  => 0,
                        'data' => 'IP is invalid. Now, your IP address is ' . $_SERVER['REMOTE_ADDR']
                    ]));
                    return $response;
                }
            }
        }
        return $handler->handle($request);
    }
}
