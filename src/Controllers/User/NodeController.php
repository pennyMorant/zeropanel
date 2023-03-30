<?php

namespace App\Controllers\User;

use App\Controllers\UserController;
use App\Models\{
    Node,
    User,
    Ann,
    Setting
};
use App\Utils\{
    URL,
    Tools,
    DatatablesHelper
};
use Slim\Http\Response;
use Slim\Http\ServerRequest;
use App\Zero\Zero;

/**
 *  User NodeController
 */
class NodeController extends UserController
{
    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function node(ServerRequest $request, Response $response, array $args)
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
        if (isset($class)) {
            $min_node_class = min($class->toArray())['node_class'];
        } else {
            $min_node_class = 0;
        }
        if (Setting::obtain('enable_permission_group') == true) {
            $permission_group = json_decode(Setting::obtain('permission_group_detail'), true);
        } else {
            $permission_group = [
                0   =>  'LV-0',
                1   =>  'LV-1', 
                2   =>  'LV-2', 
                3   =>  'LV-3', 
                4   =>  'LV-4', 
                5   =>  'LV-5', 
                6   =>  'LV-6', 
                7   =>  'LV-7',
                8   =>  'LV-8', 
                9   =>  'LV-9', 
                10  =>  'LV-10',
            ];
        }

        $this->view()
            ->assign('class', $class)
            ->assign('servers', $servers)
            ->assign('min_node_class', $min_node_class)
            ->assign('user', $user)
            ->assign('permission_group', $permission_group)
            ->assign('anns', Ann::where('date', '>=', date('Y-m-d H:i:s', time() - 7 * 86400))->orderBy('date', 'desc')->get())
            ->registerClass('URL', URL::class)
            ->display('user/node.tpl');
        return $response;
    }
}