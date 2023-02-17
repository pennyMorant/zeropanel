<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminController;
use App\Models\{
    Ip,
    SigninIp,
    TrafficLog,
    UserSubscribeLog,
    Node
};
use App\Utils\{
    QQWry,
    Tools
};
use Slim\Http\{
    Request,
    Response
};

class RecordController extends AdminController
{
    /**
     * 后台在线 IP 页面
     *
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function recordIndex($request, $response, $args)
    {
        $table_config_alive['total_column'] = array(
            'id'        => 'ID',
            'userid'    => '用户ID',
            'nodeid'    => '节点ID',
            'node_name' => '节点名',
            'ip'        => 'IP',
            'location'  => '归属地',
            'datetime'  => '时间'
        );
        $table_config_alive['ajax_url'] = 'record/ajax/alive';
        $table_config_signin['total_column'] = array(
            'id'        => 'ID',
            'userid'    => '用户ID',
            'ip'        => 'IP',
            'location'  => '归属地',
            'datetime'  => '时间',
            'type'      => '类型'
        );
        $table_config_signin['ajax_url'] = 'record/ajax/signin';
        $table_config_subscribe['total_column'] = array(
            'id'                  => 'ID',
            'user_id'             => '用户ID',
            'email'               => '用户邮箱',
            'subscribe_type'      => '类型',
            'request_ip'          => 'IP',
            'location'            => '归属地',
            'request_time'        => '时间',
            'request_user_agent'  => 'User-Agent'
        );
        $table_config_subscribe['ajax_url'] = 'record/ajax/subscribe';
        $table_config_traffic['total_column'] = array(
            'id'              => 'ID',
            'user_id'         => '用户ID',
            'node_name'       => '使用节点',
            'rate'            => '倍率',
            'origin_traffic'  => '实际使用流量',
            'traffic'         => '结算流量',
            'datetime'        => '记录时间'
        );
        $table_config_traffic['ajax_url'] = 'record/ajax/traffic';
        $this->view()
            ->assign('table_config_alive', $table_config_alive)
            ->assign('table_config_signin', $table_config_signin)
            ->assign('table_config_subscribe', $table_config_subscribe)
            ->assign('table_config_traffic', $table_config_traffic)
            ->display('admin/record.tpl');
        return $response;
    }

    /**
     * 后台在线 IP 页面 AJAX
     *
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function recordAjax($request, $response, $args)
    {
        $type = $args['type'];
        switch ($type) {
            case 'alive':
                $query = Ip::getTableDataFromAdmin(
                    $request,
                    static function (&$order_field) {
                        if (in_array($order_field, ['name'])) {
                            $order_field = 'userid';
                        }
                        if (in_array($order_field, ['node_name'])) {
                            $order_field = 'nodeid';
                        }
                        if (in_array($order_field, ['location'])) {
                            $order_field = 'ip';
                        }
                    },
                    static function ($query) {
                        $query->where('datetime', '>=', time() - 60);
                    }
                );
        
                $data  = [];
                $QQWry = new QQWry();
                foreach ($query['datas'] as $value) {
                    /** @var Ip $value */
        
                    $tempdata              = [];
                    $tempdata['id']        = $value->id;
                    $tempdata['userid']    = $value->userid;
                    $tempdata['nodeid']    = $value->nodeid;
                    $tempdata['node_name'] = $value->node_name();
                    $tempdata['ip']        = Tools::getRealIp($value->ip);
                    $tempdata['location']  = $value->location($QQWry);
                    $tempdata['datetime']  = $value->datetime();
                    $data[] = $tempdata;
                    
                }
                $total = Ip::count();
                break;
            case 'signin':
                $query = SigninIp::getTableDataFromAdmin(
                    $request,
                    static function (&$order_field) {
                        if (in_array($order_field, ['name'])) {
                            $order_field = 'userid';
                        }
                        if (in_array($order_field, ['location'])) {
                            $order_field = 'ip';
                        }
                    }
                );
        
                $data  = [];
                $QQWry = new QQWry();
                foreach ($query['datas'] as $value) {
                    /** @var SigninIp $value */
        
                    if ($value->user() == null) {
                        SigninIp::user_is_null($value);
                        continue;
                    }
                    $tempdata              = [];
                    $tempdata['id']        = $value->id;
                    $tempdata['userid']    = $value->userid;
                    $tempdata['ip']        = $value->ip;
                    $tempdata['location']  = $value->location($QQWry);
                    $tempdata['datetime']  = $value->datetime();
                    $tempdata['type']      = $value->type();
        
                    $data[] = $tempdata;
                    
                }
                $total = SigninIp::count();
                break;
            case "subscribe":
                $query = UserSubscribeLog::getTableDataFromAdmin(
                    $request,
                    static function (&$order_field) {
                        if (in_array($order_field, ['location'])) {
                            $order_field = 'request_ip';
                        }
                    },
                    
                );
        
                $data  = [];
                $QQWry = new QQWry();
                foreach ($query['datas'] as $value) {
                    /** @var UserSubscribeLog $value */
        
                    if ($value->user() == null) {
                        UserSubscribeLog::user_is_null($value);
                        continue;
                    }
                    $tempdata                       = [];
                    $tempdata['id']                 = $value->id;
                    $tempdata['user_id']            = $value->user_id;
                    $tempdata['email']              = $value->email;
                    $tempdata['subscribe_type']     = $value->subscribe_type;
                    $tempdata['request_ip']         = $value->request_ip;
                    $tempdata['location']           = $value->location($QQWry);
                    $tempdata['request_time']       = $value->request_time;
                    $tempdata['request_user_agent'] = $value->request_user_agent;
        
                    $data[] = $tempdata;
                    
                }
                $total = UserSubscribeLog::count();
                break;
            case 'traffic':
                $query = TrafficLog::getTableDataFromAdmin($request);
                $data = [];
                foreach ($query['datas'] as $value) {
                    $node                       = Node::where('id', $value->node_id)->first();
                    $tempdata                   = [];
                    $tempdata['id']             = $value->id;
                    $tempdata['user_id']        = $value->user_id;
                    $tempdata['node_name']      = $node->name;
                    $tempdata['rate']           = $value->rate;
                    $tempdata['origin_traffic'] = Tools::flowAutoShow($value->u + $value->d);
                    $tempdata['traffic']        = $value->traffic;
                    $tempdata['datetime']       = date('Y-m-d H:i:s', $value->datetime);
                    $data[] = $tempdata;
                    
                }
                $total = TrafficLog::count();
                break;
        }
        return $response->withJson([
            'draw'            => $request->getParam('draw'),
            'recordsTotal'    => $total,
            'recordsFiltered' => $query['count'],
            'data'            => $data,
        ]);
    }
}