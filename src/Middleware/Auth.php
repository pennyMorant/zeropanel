<?php

namespace App\Middleware;

use App\Services\Auth as AuthService;
use Slim\Factory\AppFactory;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

final class Auth implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
        $user = AuthService::getUser();
        if (!$user->isLogin) {
            return AppFactory::determineResponseFactory()->createResponse(302)->withHeader('Location', '/auth/signin');
        }
        return $handler->handle($request);
    }
}