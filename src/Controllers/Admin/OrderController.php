<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminController;
use App\Models\{
    Order
};
use Pkly\I18Next\I18n;
use Slim\Http\Response;
use Slim\Http\ServerRequest;

class OrderController extends AdminController
{
    /**
     *
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function index(ServerRequest $request, Response $response, $args)
    {
        $table_config['total_column'] = [
            'id'            => 'ID',
            'user_id'       => '用户ID',
            'order_total'   => '金额',
            'order_status'  => '状态',
            'order_no'            => '订单号',
            'created_time'  => '时间',
            'order_payment' => '支付方式',
            'order_type'    => '订单类型',
            'action'        => '操作',
        ];
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
    public function ajaxOrder(ServerRequest $request, Response $response, $args): Response
    {
        $query = Order::getTableDataFromAdmin(
            $request,
            static function (&$order_field) {
                if (in_array($order_field, ['order_payment'])) {
                    $order_field = 'id';
                }
            }
        );

        $data = $query['datas']->map(function($rowData) {
            $trans = I18n::get();
            return [
                'id'    =>  $rowData->id,
                'user_id'   =>  $rowData->user_id,
                'order_total'   =>  $rowData->order_total,
                'order_status'  =>  $rowData->status(),
                'order_no'  =>  $rowData->order_no,
                'created_time'  =>  date('Y-m-d H:i:s', $rowData->created_time),
                'order_payment' =>  $rowData->payment(),
                'order_type'    =>  $rowData->order_type == 1 ? $trans->t('purchase product') : $trans->t('add credit'),
                'action'    =>  '<div class="btn-group dropstart"><a class="btn btn-light-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false">操作</a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" type="button" onclick="completeOrder(' . $rowData->id . ')">标记完成</a></li>
                                        <li><a class="dropdown-item" type="button" onclick="deleteOrder(' . $rowData->id . ')">删除</a></li>
                                    </ul>
                                </div>',
            ];
        })->toArray();

        return $response->withJson([
            'draw'            => $request->getParam('draw'),
            'recordsTotal'    => Order::count(),
            'recordsFiltered' => $query['count'],
            'data'            => $data,
        ]);
    }

    public function completeOrder(ServerRequest $request, Response $response, $args): Response
    {
        $order_id = $request->getParam('order_id');
        $order = Order::find($order_id);
        $order_no = $order->order_no;
        $order->finshOrder($order_no);
        return $response->withJson([
            'ret' => 1,
            'msg' => 'success'
        ]);
    }

    public function deleteOrder(ServerRequest $request, Response $response, $args): Response
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