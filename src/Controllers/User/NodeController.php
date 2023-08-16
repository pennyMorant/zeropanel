<?php

namespace App\Controllers\User;

use App\Controllers\UserController;
use App\Models\Node;
use Slim\Http\Response;
use Slim\Http\ServerRequest;

class NodeController extends UserController
{
    public function nodeIndex(ServerRequest $request, Response $response, array $args)
    {
        $user        = $this->user;
        $user_group = ($user->node_group != 0 ? [0, $user->node_group] : [0]);
        if ($user->is_admin == 0) {
            $servers = Node::where('status' ,1)
            ->whereIn('node_group', $user_group) // 筛选用户所在分组的服务器
            ->orderBy('name', 'asc')
            ->get();
        } else if ($user->is_admin == 1) {
            $servers = Node::where('status' ,1)
            ->orderBy('name', 'asc')
            ->get();
        }

        $class = Node::select('node_class')
        ->orderBy('node_class', 'asc')
        ->distinct()
        ->get();
        if (!$class->isEmpty()) {
            $min_node_class = min($class->toArray())['node_class'];
        } else {
            $min_node_class = 0;
        }

        $this->view()
            ->assign('class', $class)
            ->assign('servers', $servers)
            ->assign('min_node_class', $min_node_class)
            ->display('user/node.tpl');
        return $response;
    }
}