<?php

namespace App\Controllers;

use App\Models\User;
use App\Clients\Universal;
use App\Services\NodeService;
use Slim\Http\ServerRequest;

class SubsController
{
    public function subscribe(ServerRequest $request)
    {
        $flag = $request->getParam('flag') ?? ($_SERVER['HTTP_USER_AGENT'] ?? '');
        $token = $request->getParam('token');
        $flag = strtolower($flag);
        $user = User::where('subscription_token', $token)->first();
        $node_service = new NodeService();
        $servers = $node_service->getAllNodes($user);
        $node_service->recordLog($user);
        if ($flag) {
            foreach (array_reverse(glob(dirname(__FILE__, 3).'/src/Clients'. '/*.php')) as $sub_file) {
                $sub_file = 'App\\Clients\\'. basename($sub_file, '.php');
                $class = new $sub_file($user, $servers);
                if (stripos($flag, $class->flag) !== false) {
                   die($class->handle());
                }
            }
        }

        $class = new Universal($user, $servers);
        die($class->handle());
    }
}