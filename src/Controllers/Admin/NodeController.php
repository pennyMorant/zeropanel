<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminController;
use App\Models\Node;
use App\Utils\{
    Tools,
    Telegram,
    CloudflareDriver
};
use App\Services\Config;
use Exception;
use Slim\Http\Response;
use Slim\Http\ServerRequest;

class NodeController extends AdminController
{
    public function index(ServerRequest $request, Response $response, $args)
    {
        $table_config['total_column'] = [
            
            'id'                      => 'ID',
            'online'                  => 'Online',
            'name'                    => '节点名称',
            'onlineuser'              => '在线人数',           
            'sort'                    => '类型',
            'node_ip'                 => '节点IP',
            'node_class'              => '节点等级',
            'node_speedlimit'         => '速度',
            'status'                    => '显示与隐藏',
            'action'                  => '操作',
        ];
        $table_config['ajax_url'] = 'node/ajax';

        $this->view()
            ->assign('table_config', $table_config)
            ->display('admin/node/node.tpl');
        return $response;
    }

    public function createNodeIndex(ServerRequest $request, Response $response, $args)
    {
        $this->view()->display('admin/node/create.tpl');
        return $response;
    }

    public function createNode(ServerRequest $request, Response $response, $args): Response
    {
        $node                   = new Node();
        $node->name             = $request->getParam('name');
        $node->server           = trim($request->getParam('server'));
        $node->traffic_rate     = $request->getParam('traffic_rate');
        $node->status           = 0;
        $node->node_group       = $request->getParam('node_group');
        $node->node_speedlimit  = $request->getParam('node_speedlimit');
        $node->flag             = $request->getParam('flag');
        $node->sort             = $request->getParam('sort');

        if ($request->getParam('custom_config') !== null) {
            $node->custom_config = json_encode($request->getParam('custom_config'));
        } else {
            $node->custom_config = '{}';
        }
        
        $req_node_ip = trim($request->getParam('node_ip'));
        $success = true;
        $server_list = explode(';', $node->server);
        if (Tools::isIPv4($req_node_ip)) {
            $success = $node->changeNodeIp($req_node_ip);
        } else {
            $success = $node->changeNodeIp($server_list[0]);
        }

        if (! $success) {
            return $response->withJson([
                'ret' => 0,
                'msg' => '获取节点IP失败，请检查您输入的节点地址是否正确！',
            ]);
        }
        
        $node->node_class                 = $request->getParam('node_class');
        $node->node_sort                  = (int)$request->getParam('node_sort');
        $node->node_traffic_limit       = $request->getParam('node_traffic_limit') * 1024 * 1024 * 1024;
        $node->node_traffic_limit_reset_date    = $request->getParam('node_traffic_limit_reset_date');

        $node->save();

        if ($_ENV['cloudflare_enable'] == true) {
            $domain_name = explode('.' . $_ENV['cloudflare_name'], $node->server);
            CloudflareDriver::updateRecord($domain_name[0], $node->node_ip);
        }

        return $response->withJson([
            'ret' => 1,
            'msg' => '节点添加成功'
        ]);
    }

    public function updateNodeIndex(ServerRequest $request, Response $response, $args)
    {
        $id = $args['id'];
        $node = Node::find($id);
        $this->view()
            ->assign('node', $node)
            ->display('admin/node/edit.tpl');
        return $response;
    }

    public function updateNode(ServerRequest $request, Response $response, $args): Response
    {
        $id                     = $request->getParam('id');
        $node                   = Node::find($id);
        $node->name             = $request->getParam('name');
        $node->node_group       = $request->getParam('node_group');
        $node->server           = trim($request->getParam('server'));
        $node->traffic_rate     = $request->getParam('traffic_rate');
        $node->node_speedlimit  = $request->getParam('node_speedlimit');
        $node->sort             = $request->getParam('sort');

        if ($request->getParam('custom_config') != null) {
            $node->custom_config = json_encode($request->getParam('custom_config'));
        } else {
            $node->custom_config = '{}';
        }
        
        $req_node_ip = trim($request->getParam('node_ip'));

        $success = true;
        $server_list = explode(';', $node->server);
        if (Tools::isIPv4($req_node_ip)) {
            $success = $node->changeNodeIp($req_node_ip);
        } else {
            $success = $node->changeNodeIp($server_list[0]);
        }
        if (! $success) {
            return $response->withJson([
                'ret' => 0,
                'msg' => '更新节点IP失败，请检查您输入的节点地址是否正确！',
            ]);
        }
        $node->flag                     = $request->getParam('flag');
        $node->node_class                 = $request->getParam('node_class');
        $node->node_sort                  = (int)$request->getParam('node_sort');
        $node->node_traffic_limit       = $request->getParam('node_traffic_limit') * 1024 * 1024 * 1024;
        $node->node_traffic_limit_reset_date    = $request->getParam('node_traffic_limit_reset_date');

        $node->save();

        return $response->withJson([
            'ret' => 1,
            'msg' => '修改成功'
        ]);
        
    }

    public function nodeAjax(ServerRequest $request, Response $response, $args): Response
    {
        $query = Node::getTableDataFromAdmin(
            $request,
            static function (&$order_field) {
                if (in_array($order_field, ['action'])) {
                    $order_field = 'id';
                }
                if (in_array($order_field, ['name'])) {
                    $order_field = 'id';
                }
            }
        );

        $data = $query['datas']->map(function($rowData) {
            $type = "'node'";
            return [
                'id'    => $rowData->id,
                'online'    => $rowData->online == 1 ? '<span class="badge badge-circle badge-success badge-sm"></span>' : '<span class="badge badge-circle badge-danger badge-sm"></span>',
                'name'  =>  $rowData->name,
                'onlineuser'    =>  $rowData->getNodeOnlineUserCount(),
                'sort'  =>  $rowData->sort,
                'node_ip'   =>  $rowData->node_ip,
                'node_class'    =>  $rowData->node_class,
                'node_speedlimit'   =>  $rowData->node_speedlimit,
                'status'    =>  $rowData->status(),
                'action'    =>  '<div class="btn-group dropstart"><a class="btn btn-light-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false">操作</a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="/admin/node/update/'.$rowData->id.'">编辑</a></li>
                                        <li><a class="dropdown-item" type="button" onclick="zeroAdminDelete('.$type.', '.$rowData->id.')">删除</a></li>
                                    </ul>
                                </div>',
            ];
        })->toArray();

        return $response->withJson([
            'draw'            => $request->getParam('draw'),
            'recordsTotal'    => Node::count(),
            'recordsFiltered' => $query['count'],
            'data'            => $data,
        ]);
    }

    public function updateNodeStatus(ServerRequest $request, Response $response, $args): Response
    {
        $id = $request->getParam('id');
        $status = $request->getParam('status');
        $node = Node::find($id);
        $node->status = $status;
        $node->save();
        return $response->withJson([
            'ret'   => 1,
            'msg'   => 'success'
        ]);
    }

    public function deleteNode(ServerRequest $request, Response $response, $args)
    {
        $id = $request->getParam('id');
        $node = Node::find($id);
        $node->delete();
        return $response->withJson([
            'ret' => 1,
            'msg' => '删除成功'
        ]);
    }

}
