<?php

namespace App\Controllers\User;

use App\Controllers\UserController;
use App\Models\{
    Node,
    User,
    Ann
};
use App\Utils\{
    URL,
    Tools,
    DatatablesHelper
};
use Slim\Http\{
    Request,
    Response
};
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
    public function node($request, $response, $args)
    {
        $user        = $this->user;

        $user_group = ($user->node_group != 0 ? [0, $user->node_group] : [0]);
        if ($user->is_admin == 0) {
            $servers = Node::where('status' ,1)
            ->where('sort', '!=', '9') // 我也不懂为什么
            ->whereIn('node_group', $user_group) // 筛选用户所在分组的服务器
            ->orderBy('name', 'asc')
            ->get();
        } else if ($user->is_admin == 1) {
            $servers = Node::where('status' ,1)
            ->where('sort', '!=', '9') // 我也不懂为什么
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
        $nodes       = Node::where('status', 1)->orderBy('node_class')->orderBy('name')->get();

        $array_nodes = [];

        foreach ($nodes as $node) {
            if ($user->is_admin == 0 && $node->node_group != $user->node_group && $node->node_group != 0) {
                continue;
            }

            $array_node               = [];
            $array_node['raw_node']   = $node;
            $array_node['id']         = $node->id;
            $array_node['class']      = $node->node_class;
            $array_node['name']       = $node->name;
            $array_node['sort']       = $node->sort;
            $array_node['group']      = $node->node_group;
            $array_node['online_user']    = $node->getNodeOnlineUserCount();
            $array_node['online']         = $node->get_node_online_status();
            $array_node['latest_load']    = $node->get_node_latest_load_text();
            $array_node['traffic_used']   = (int) Tools::flowToGB($node->node_bandwidth);
            $array_node['traffic_limit']  = (int) Tools::flowToGB($node->node_traffic);
            $array_node['bandwidth']      = $node->get_node_speedlimit();
            $array_node['traffic_rate']   = $node->traffic_rate;
            $array_node['flag']         = $node->flag;

            $array_nodes[] = $array_node;
        }

        $this->view()
            ->assign('class', $class)
            ->assign('servers', $servers)
            ->assign('min_node_class', $min_node_class)
            ->assign('nodes', $array_nodes)
            ->assign('user', $user)
            ->assign('anns', Ann::where('date', '>=', date('Y-m-d H:i:s', time() - 7 * 86400))->orderBy('date', 'desc')->get())
            ->registerClass('URL', URL::class)
            ->display('user/node.tpl');
        return $response;
    }
}