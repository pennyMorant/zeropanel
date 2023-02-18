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
use Slim\Http\{
    Request,
    Response
};

class NodeController extends AdminController
{
    /**
     * 后台节点页面
     * 
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function index($request, $response, $args)
    {
        $table_config['total_column'] = array(
            
            'id'                      => 'ID',
            'online'                  => 'Online',
            'name'                    => '节点名称',
            'onlineuser'              => '在线人数',           
            'sort'                    => '类型',
            //'server'                  => '节点地址',
            //'outaddress'              => '出口地址',
            'node_ip'                 => '节点IP',
            //'info'                    => '节点信息',
            //'flag'                    => '旗帜',
            //'traffic_rate'            => '流量比率',
            //'node_group'              => '节点群组',
            'node_class'              => '节点等级',
            //'node_sort'               => '节点排序',
            'node_speedlimit'         => '速度',
            //'node_bandwidth'          => '已走流量/GB',
            //'node_bandwidth_limit'    => '流量限制/GB',
            //'bandwidthlimit_resetday' => '流量重置日',
            //'node_heartbeat'          => '上一次活跃时间',
            'status'                    => '显示与隐藏',
            'action'                  => '操作',
        );
        $table_config['default_show_column'] = array('op', 'id', 'name', 'sort');
        $table_config['ajax_url'] = 'node/ajax';

        $this->view()
            ->assign('table_config', $table_config)
            ->display('admin/node/node.tpl');
        return $response;
    }

    /**
     * 后台创建节点页面
     * 
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function createNodeIndex($request, $response, $args)
    {
        $this->view()->display('admin/node/create.tpl');
        return $response;
    }

    /**
     * 后台添加节点
     * 
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function createNode($request, $response, $args)
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
        $node->node_bandwidth_limit       = $request->getParam('node_bandwidth_limit') * 1024 * 1024 * 1024;
        $node->bandwidthlimit_resetday    = $request->getParam('bandwidthlimit_resetday');

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

    /**
     * 后台编辑节点页面
     * 
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function updateNodeIndex($request, $response, $args)
    {
        $id = $args['id'];
        $node = Node::find($id);
        $this->view()
            ->assign('node', $node)
            ->display('admin/node/edit.tpl');
        return $response;
    }

    /**
     * 后台更新节点页面
     * 
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function updateNode($request, $response, $args)
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
        $node->node_bandwidth_limit       = $request->getParam('node_bandwidth_limit') * 1024 * 1024 * 1024;
        $node->bandwidthlimit_resetday    = $request->getParam('bandwidthlimit_resetday');

        $node->save();

        return $response->withJson([
            'ret' => 1,
            'msg' => '修改成功'
        ]);
        
    }

    /**
     * 后台删除节点
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function delete($request, $response, $args)
    {
        $id = $request->getParam('id');
        $node = Node::find($id);

        if (!$node->delete()) {
            return $response->withJson([
                'ret' => 0,
                'msg' => '删除失败'
            ]);
        }

        return $response->withJson([
            'ret' => 1,
            'msg' => '删除成功'
        ]);
    }

    /**
     * 后台节点页面AJAX
     * 
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function nodeAjax($request, $response, $args)
    {
        $query = Node::getTableDataFromAdmin(
            $request,
            static function (&$order_field) {
                if (in_array($order_field, ['action'])) {
                    $order_field = 'id';
                }
                if (in_array($order_field, ['outaddress'])) {
                    $order_field = 'server';
                }
            }
        );

        $data  = [];
        foreach ($query['datas'] as $value) {
            /** @var Node $value */

            $tempdata                            = [];
            
            $tempdata['id']                      = $value->id;
            $tempdata['online']                  = $value->online == 1 ? '<span class="badge badge-circle badge-success badge-sm"></span>' : '<span class="badge badge-circle badge-danger badge-sm"></span>';
            $tempdata['name']                    = $value->name;
            $tempdata['onlineuser']              = $value->get_node_online_user_count();
            $tempdata['sort']                    = $value->sort();
            //$tempdata['server']                  = $value->server;
            //$tempdata['outaddress']              = $value->getOutAddress();
            $tempdata['node_ip']                 = $value->node_ip;
            //$tempdata['info']                    = $value->info;
            //$tempdata['flag']                    = $value->flag;
            //$tempdata['traffic_rate']            = $value->traffic_rate;
            //$tempdata['node_group']              = $value->node_group;
            //$tempdata['node_sort']               = $value->node_sort;
            $tempdata['node_class']              = $value->node_class;
            $tempdata['node_speedlimit']         = $value->node_speedlimit == 0 ? '不限速' : $value->node_speedlimit . 'Mbps';
            //$tempdata['node_bandwidth']          = Tools::flowToGB($value->node_bandwidth);
            //$tempdata['node_bandwidth_limit']    = Tools::flowToGB($value->node_bandwidth_limit);
            //$tempdata['bandwidthlimit_resetday'] = $value->bandwidthlimit_resetday;
            //$tempdata['node_heartbeat']          = $value->node_heartbeat();
            $tempdata['status']                  = $value->status();
            $tempdata['action']                  = '<div class="dropdown"><a class="btn btn-light-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false">操作</a>
                                                        <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item" href="/admin/node/update/'.$value->id.'">编辑</a></li>
                                                            <li><a class="dropdown-item" href="#" onclick="KTAdminNode("'.$value->id.'")>删除</a></li>
                                                        </ul>
                                                    </div>';
            $data[] = $tempdata;
        }
        return $response->withJson([
            'draw'            => $request->getParam('draw'),
            'recordsTotal'    => Node::count(),
            'recordsFiltered' => $query['count'],
            'data'            => $data,
        ]);
    }

    /**
     * 
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function updateNodeStatus($request, $response, $args)
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
}
