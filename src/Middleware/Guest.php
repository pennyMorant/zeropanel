<?php
namespace App\Middleware;

use App\Services\Auth as AuthService;
use Slim\Factory\AppFactory;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class Guest
{
    public function __invoke(Request $request, RequestHandler $handler)
    {
        $user = AuthService::getUser();
        if ($user->isLogin) {
            return AppFactory::determineResponseFactory()->createResponse(302)->withHeader('Location', '/user/dashboard');
        } 
        return $handler->handle($request);
    }
}
