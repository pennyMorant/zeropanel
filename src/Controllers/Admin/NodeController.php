<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminController;
use App\Models\Node;
use App\Utils\{
    Tools,
};
use Slim\Http\Response;
use Slim\Http\ServerRequest;

class NodeController extends AdminController
{
    public function index(ServerRequest $request, Response $response, array $args): Response
    {
        $table_config['total_column'] = [
            
            'id'                      => 'ID',
            'online'                  => 'Online',
            'name'                    => '节点名称',
            'online_user'             => '在线人数',           
            'type'                    => '类型',
            'node_ip'                 => '节点IP',
            'node_class'              => '节点等级',
            'node_speedlimit'         => '速度',
            'status'                  => '显示与隐藏',
            'action'                  => '操作',
        ];
        $table_config['ajax_url'] = 'node/ajax';

        $this->view()
            ->assign('table_config', $table_config)
            ->display('admin/node/node.tpl');
        return $response;
    }

    public function createNodeIndex(ServerRequest $request, Response $response, array $args): Response
    {
        $this->view()->display('admin/node/create.tpl');
        return $response;
    }

    public function createNode(ServerRequest $request, Response $response, array $args): Response
    {
        $node                   = new Node();
        $postData               = $request->getParsedBody();
        $node->name             = $postData['name'];
        $node->server           = trim($postData['server']);
        $node->traffic_rate     = $postData['traffic_rate'] ?: 1;
        $node->status           = 0;
        $node->node_group       = $postData['node_group'] ?: 0;
        $node->node_speedlimit  = $postData['node_speedlimit'] ?: 0;
        $node->node_flag        = $postData['node_flag'];
        $node->node_type        = $postData['node_type'];

        if (!is_null($postData['custom_config'])) {
            $node->custom_config = json_encode($postData['custom_config']);
        } else {
            $node->custom_config = '{}';
        }
        
        $req_node_ip = trim($postData['node_ip']);
        $success = true;
        
        if (Tools::isIP($req_node_ip) === 'v4' || Tools::isIP($req_node_ip) === 'v6') {
            $success = $node->changeNodeIp($req_node_ip);
        } else {
            $success = $node->changeNodeIp($node->server);
        }

        if (!$success) {
            return $response->withJson([
                'ret' => 0,
                'msg' => '获取节点IP失败，请检查您输入的节点地址是否正确！',
            ]);
        }
        
        $node->node_class                       = $postData['node_class'] ?: 0;
        $node->node_sort                        = $postData['node_sort'] ?: 0;
        $node->node_traffic_limit               = $postData['node_traffic_limit'] * 1024 * 1024 * 1024;
        $node->node_traffic_limit_reset_date    = $postData['node_traffic_limit_reset_date'];
        $node->save();

        return $response->withJson([
            'ret' => 1,
            'msg' => '节点添加成功'
        ]);
    }

    public function updateNodeIndex(ServerRequest $request, Response $response, array $args): Response
    {
        $id = $args['id'];
        $node = Node::find($id);
        $this->view()
            ->assign('node', $node)
            ->display('admin/node/edit.tpl');
        return $response;
    }

    public function updateNode(ServerRequest $request, Response $response, array $args): Response
    {
        $putData = $request->getParsedBody();
        $id                     = $putData['id'];
        $node                   = Node::find($id);
        $node->name             = $putData['name'];
        $node->node_group       = $putData['node_group'] ?: 0;
        $node->server           = trim($putData['server']);
        $node->traffic_rate     = $putData['traffic_rate'] ?: 1;
        $node->node_speedlimit  = $putData['node_speedlimit'] ?: 0;
        $node->node_type        = $putData['node_type'];

        if (!is_null($putData['custom_config'])) {
            $node->custom_config = json_encode($putData['custom_config']);
        } else {
            $node->custom_config = '{}';
        }
        
        $req_node_ip = trim($putData['node_ip']);

        $success = true;
        if (Tools::isIP($req_node_ip) === 'v4' || Tools::isIP($req_node_ip) === 'v6') {
            $success = $node->changeNodeIp($req_node_ip);
        } else {
            $success = $node->changeNodeIp($node->server);
        }
        if (!$success) {
            return $response->withJson([
                'ret' => 0,
                'msg' => '更新节点IP失败，请检查您输入的节点地址是否正确！',
            ]);
        }
        $node->node_flag                        = $putData['node_flag'];
        $node->node_class                       = $putData['node_class'] ?: 0;
        $node->node_sort                        = $putData['node_sort'] ?: 0;
        $node->node_traffic_limit               = $putData['node_traffic_limit'] * 1024 * 1024 * 1024;
        $node->node_traffic_limit_reset_date    = $putData['node_traffic_limit_reset_date'];

        $node->save();

        return $response->withJson([
            'ret' => 1,
            'msg' => '修改成功'
        ]);
        
    }

    public function nodeAjax(ServerRequest $request, Response $response, array $args): Response
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
                'id'              => $rowData->id,
                'online'          => $rowData->online == 1 ? '<span class="badge badge-circle badge-success badge-sm"></span>' : '<span class="badge badge-circle badge-danger badge-sm"></span>',
                'name'            => $rowData->name,
                'online_user'     => $rowData->getNodeOnlineUserCount(),
                'type'            => $rowData->nodeType(),
                'node_ip'         => $rowData->node_ip,
                'node_class'      => $rowData->node_class,
                'node_speedlimit' => $rowData->node_speedlimit == 0 ? '无限制' : $rowData->node_speedlimit,
                'status'          => $rowData->status(),
                'action'          => '<div class="btn-group dropstart"><a class="btn btn-light-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false">操作</a>
                                    <ul    class = "dropdown-menu">
                                    <li><a class = "dropdown-item" href = "node/update/'.$rowData->id.'">编辑</a></li>
                                    <li><a class = "dropdown-item" type = "button" onclick = "zeroAdminDelete('.$type.', '.$rowData->id.')">删除</a></li>
                                    </ul>
                                </div>',
            ];
        })->toArray();

        return $response->withJson([
            'draw'            => $request->getParsedBodyParam('draw'),
            'recordsTotal'    => Node::count(),
            'recordsFiltered' => $query['count'],
            'data'            => $data,
        ]);
    }

    public function updateNodeStatus(ServerRequest $request, Response $response, array $args): Response
    {
        $id = $request->getParsedBodyParam('id');
        $status = $request->getParsedBodyParam('status');
        $node = Node::find($id);
        $node->status = $status;
        $node->save();
        return $response->withJson([
            'ret'   => 1,
            'msg'   => 'success'
        ]);
    }

    public function deleteNode(ServerRequest $request, Response $response, array $args): Response
    {
        $id = $request->getParsedBodyParam('id');
        $node = Node::find($id);
        $node->delete();
        return $response->withJson([
            'ret' => 1,
            'msg' => '删除成功'
        ]);
    }

}
