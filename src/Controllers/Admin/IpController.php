<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminController;
use App\Models\{
    Ip,
    SigninIp
};
use App\Utils\{
    QQWry,
    Tools
};
use Slim\Http\{
    Request,
    Response
};

class IpController extends AdminController
{
    /**
     * 后台登录记录页面
     *
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function index($request, $response, $args)
    {
        $table_config['total_column'] = array(
            'id'        => 'ID',
            'userid'    => '用户ID',
            'name' => '用户名',
            'ip'        => 'IP',
            'location'  => '归属地',
            'datetime'  => '时间',
            'type'      => '类型'
        );
        $table_config['default_show_column'] = array_keys($table_config['total_column']);
        $table_config['ajax_url'] = 'login/ajax';
        $this->view()
            ->assign('table_config', $table_config)
            ->display('admin/ip/login.tpl');
        return $response;
    }

    /**
     * 后台登录记录页面 AJAX
     *
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function ajaxLogin($request, $response, $args)
    {
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
            $tempdata['name'] = $value->name();
            $tempdata['ip']        = $value->ip;
            $tempdata['location']  = $value->location($QQWry);
            $tempdata['datetime']  = $value->datetime();
            $tempdata['type']      = $value->type();

            $data[] = $tempdata;
        }

        return $response->withJson([
            'draw'            => $request->getParam('draw'),
            'recordsTotal'    => SigninIp::count(),
            'recordsFiltered' => $query['count'],
            'data'            => $data,
        ]);
    }

    /**
     * 后台在线 IP 页面
     *
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function alive($request, $response, $args)
    {
        $table_config['total_column'] = array(
            'id'        => 'ID',
            'userid'    => '用户ID',
            'name' => '用户名',
            'nodeid'    => '节点ID',
            'node_name' => '节点名',
            'ip'        => 'IP',
            'location'  => '归属地',
            'datetime'  => '时间',
            // 'is_node'   => '是否为中转连接'
        );
        $table_config['default_show_column'] = array_keys($table_config['total_column']);
        $table_config['ajax_url'] = 'alive/ajax';
        $this->view()
            ->assign('table_config', $table_config)
            ->display('admin/ip/alive.tpl');
        return $response;
    }

    /**
     * 后台在线 IP 页面 AJAX
     *
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function ajaxAlive($request, $response, $args)
    {
        $query = Ip::getTableDataFromAdmin(
            $request,
            static function (&$order_field) {
                if (in_array($order_field, ['name'])) {
                    $order_field = 'userid';
                }
                if (in_array($order_field, ['node_name', 'is_node'])) {
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
            $tempdata['name'] = $value->name();
            $tempdata['nodeid']    = $value->nodeid;
            $tempdata['node_name'] = $value->node_name();
            $tempdata['ip']        = Tools::getRealIp($value->ip);
            $tempdata['location']  = $value->location($QQWry);
            $tempdata['datetime']  = $value->datetime();
            // $tempdata['is_node']   = $value->is_node();

            $data[] = $tempdata;
        }

        return $response->withJson([
            'draw'            => $request->getParam('draw'),
            'recordsTotal'    => Ip::count(),
            'recordsFiltered' => $query['count'],
            'data'            => $data,
        ]);
    }
}