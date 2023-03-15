<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminController;
use App\Models\{
    Order
};
use Pkly\I18Next\I18n;
use Slim\Http\{
    Request,
    Response
};

class OrderController extends AdminController
{
    /**
     *
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function index($request, $response, $args)
    {
        $table_config['total_column'] = array(
            'id'            => 'ID',
            'user_id'       => '用户ID',
            'order_total'   => '金额',
            'order_status'  => '状态',
            'no'            => '订单号',
            'created_time'  => '时间',
            'order_payment' => '支付方式',
            'order_type'    => '订单类型',
            'action'        => '操作',
        );
        $table_config['default_show_column'] = array_keys($table_config['total_column']);
        $table_config['ajax_url'] = 'order/ajax';
        $this->view()
            ->assign('table_config', $table_config)
            ->display('admin/order.tpl');
        return $response;
    }

    /**
     * 
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function ajaxOrder($request, $response, $args)
    {
        $trans = I18n::get();
        $query = Order::getTableDataFromAdmin(
            $request,
            static function (&$order_field) {
                if (in_array($order_field, ['order_payment'])) {
                    $order_field = 'id';
                }
            }
        );

        $data  = [];
        foreach ($query['datas'] as $value) {
            /** @var Code $value */
            /** 充值记录作为对账，用户不存在也不应删除 */
            $tempdata                         = [];
            $tempdata['id']                   = $value->id;
            $tempdata['user_id']              = $value->user_id;
            $tempdata['order_total']          = $value->order_total;
            $tempdata['order_status']         = $value->status();
            $tempdata['no']                   = $value->no;
            $tempdata['created_time']         = date('Y-m-d H:i:s', $value->created_time);
            $tempdata['order_payment']        = $value->payment();
            $tempdata['order_type']           = $value->order_type == 1 ? $trans->t('purchase product') : $trans->t('add credit');
            $tempdata['action']               = '<div class="btn-group dropstart"><a class="btn btn-light-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false">操作</a>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" type="button" onclick="completeOrder(' . $value->id . ')">标记完成</a></li>
                                                        <li><a class="dropdown-item" type="button" onclick="deleteOrder(' . $value->id . ')">删除</a></li>
                                                    </ul>
                                                </div>';
            
            $data[] = $tempdata;
        }

        return $response->withJson([
            'draw'            => $request->getParam('draw'),
            'recordsTotal'    => Order::count(),
            'recordsFiltered' => $query['count'],
            'data'            => $data,
        ]);
    }

    public function completeOrder($request, $response, $args)
    {
        $order_id = $request->getParam('order_id');
        $order = Order::find($order_id);
        $order_no = $order->no;
        $order->finshOrder($order_no);
        return $response->withJson([
            'ret' => 1,
            'msg' => 'success'
        ]);
    }

    public function deleteOrder($request, $response, $args)
    {
        $order_id = $request->getParam('order_id');
        $order = Order::find($order_id);
        $order->delete();
        return $response->withJson([
            'ret' => 1,
            'msg' => 'success'
        ]);
    }
}