<?php

namespace App\Middleware;

use App\Services\Auth as AuthService;
use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class Auth
{
    public function __invoke(Request $request, RequestHandler $handler)
    {
        $user = AuthService::getUser();
        if (!$user->isLogin) {
            $response = new Response();
            return $response->withStatus(302)->withHeader('Location', '/auth/signin');
        }
        $enablePages = array('/user/disable', '/user/logout');
        if ($user->enable == 0 && !in_array($_SERVER['REQUEST_URI'], $enablePages) && !strpos($_SERVER['REQUEST_URI'], '/ticket')) {
            $response = new Response();
            return $response->withStatus(302)->withHeader('Location', '/user/disable');
        }
        return $handler->handle($request);
    }
}