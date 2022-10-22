<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminController;
use App\Models\{
    Node,
    TrafficLog
};
use App\Utils\Tools;
use Slim\Http\{
    Request,
    Response
};

class LogController extends AdminController
{
    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function index($request, $response, $args)
    {
        $name = $args['name'];
        switch ($name) {
            case 'traffic':
                $table_config['total_column'] = array(
                    'id'              => 'ID',
                    'user_id'         => '用户ID',
                    'node_name'       => '使用节点',
                    'rate'            => '倍率',
                    'origin_traffic'  => '实际使用流量',
                    'traffic'         => '结算流量',
                    'datetime'        => '记录时间'
                );
                $table_config['default_show_column'] = array_keys($table_config['total_column']);
                $table_config['ajax_url'] = 'ajax/traffic';


                $this->view()
                    ->assign('table_config', $table_config)
                    ->display('admin/trafficlog.tpl');
                return $response;
            break;
        }
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function ajax($request, $response, $args)
    {
        $name = $args['name'];
        switch ($name) {
            case "traffic":
                $query = TrafficLog::getTableDataFromAdmin($request);
                $data = [];
                foreach ($query['datas'] as $value) {
                    $tempdata                   = [];
                    $tempdata['id']             = $value->id;
                    $tempdata['user_id']        = $value->user_id;
                    $node                       = Node::where('id', $value->node_id)->first();
                    $tempdata['node_name']      = $node->name;
                    $tempdata['rate']           = $value->rate;
                    $tempdata['origin_traffic'] = Tools::flowAutoShow($value->u + $value->d);
                    $tempdata['traffic']        = $value->traffic;
                    $tempdata['datetime']       = date('Y-m-d H:i:s', $value->datetime);
                    $data[] = $tempdata;
                }
                return $response->WithJson([
                    'draw'              => $request->getParam('draw'),
                    'recordsTotal'      => TrafficLog::count(),
                    'recordsFiltered'   => $query['count'],
                    'data'              => $data
                ]);
            }
        
    }
}
