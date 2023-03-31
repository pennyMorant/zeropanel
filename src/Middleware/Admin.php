<?php

namespace App\Middleware;

use App\Services\Auth as AuthService;
use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class Admin
{
    public function __invoke(Request $request, RequestHandler $handler)
    {
        $user = AuthService::getUser();
        if (!$user->isLogin) {
            $response = new Response();
            return $response->withStatus(302)->withHeader('Location', '/auth/signin');
        }
        if (!$user->is_admin) {
            $response = new Response();
            return $response->withStatus(302)->withHeader('Location', '/user/dashboard');
        }
        return $handler->handle($request);
    }
}