<?php

namespace App\Middleware;

use App\Services\Auth as AuthService;
use Slim\Factory\AppFactory;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class Admin
{
    public function __invoke(Request $request, RequestHandler $handler)
    {
        $user = AuthService::getUser();
        if (!$user->isLogin) {
            return AppFactory::determineResponseFactory()->createResponse(302)->withHeader('Location', '/auth/signin');
        }
        if (!$user->is_admin) {
            return AppFactory::determineResponseFactory()->createResponse(302)->withHeader('Location', '/user/dashboard');
        }
        return $handler->handle($request);
    }
}