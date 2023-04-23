<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminController;
use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use App\Controllers\OrderController as UserOrder;
use Exception;
use Pkly\I18Next\I18n;
use Slim\Http\Response;
use Slim\Http\ServerRequest;

class OrderController extends AdminController
{
    public function index(ServerRequest $request, Response $response, array $args)
    {
        $table_config['total_column'] = [
            'id'            => 'ID',
            'user_id'       => '用户ID',
            'order_total'   => '金额',
            'order_status'  => '状态',
            'order_no'      => '订单号',
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

    public function ajaxOrder(ServerRequest $request, Response $response, array $args): Response
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

            return [
                'id'            => $rowData->id,
                'user_id'       => $rowData->user_id,
                'order_total'   => $rowData->order_total,
                'order_status'  => $rowData->status(),
                'order_no'      => $rowData->order_no,
                'created_time'  => date('Y-m-d H:i:s', $rowData->created_time),
                'order_payment' => $rowData->payment(),
                'order_type'    => $rowData->orderType(),
                'action'        => '<div class="btn-group dropstart"><a class="btn btn-light-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false">操作</a>
                                    <ul    class = "dropdown-menu">
                                    <li><a class = "dropdown-item" type = "button" onclick = "completeOrder(' . $rowData->id . ')">标记完成</a></li>
                                    <li><a class = "dropdown-item" type = "button" onclick = "zeroAdminDelete(\'order\', ' . $rowData->id . ')">删除</a></li>
                                    <li><a class = "dropdown-item" href = "/'. Setting::obtain('website_admin_path') . '/order/' . $rowData->order_no . '">详细</a></li>
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

    public function orderDetailIndex(ServerRequest $request, Response $response, array $args): Response
    {
        $id = $args['no'];
        $order = Order::where('order_no', $id)->first();
        if (!is_null($order->product_id) && $order->order_type != '2') {
            $product = Product::find($order->product_id);
            $product_name = $product->name;
            $order_type = [
                1   =>  I18n::get()->t('purchase product') .  ': ' . $product_name . '-' . $product->productPeriod($order->product_price),
                3   =>  I18n::get()->t('renewal product') .': ' . $product_name . '-' . $product->productPeriod($order->product_price),
                4   =>  I18n::get()->t('upgrade product') .': ' . $product_name . '-' . $product->productPeriod($order->product_price),
            ];
        } else {
            $product_name = '';
            $product = [];
            $order_type = [
                2   =>  I18n::get()->t('add credit') .': ' . $order->order_total,
            ];
        }

        
        $this->view()
            ->assign('order', $order)
            ->assign('order_type', $order_type)      
            ->display('admin/order_detail.tpl');
        return $response;
    }

    public function completeOrder(ServerRequest $request, Response $response, array $args): Response
    {
        $order_id = $request->getParam('order_id');
        $order    = Order::find($order_id);
        $order_no = $order->order_no;
        UserOrder::execute($order_no);
        return $response->withJson([
            'ret' => 1,
            'msg' => 'success'
        ]);
    }

    public function deleteOrder(ServerRequest $request, Response $response, array $args): Response
    {
        $order_id = $request->getParam('id');
        $order    = Order::find($order_id);
        $order->delete();
        return $response->withJson([
            'ret' => 1,
            'msg' => 'success'
        ]);
    }

    public function createOrder(ServerRequest $request, Response $response, array $args): Response
    {
        $user_id        = $request->getParam('id');
        $product_id     = $request->getParam('product_id');
        $product_period = $request->getParam('product_period');
        $order_total    = $request->getParam('order_total');
        $user           = User::find($user_id);
        $product        = Product::find($product_id);
        $order          = new Order();

        try {
            if (is_null($product->productPrice($product_period))) {
                throw new \Exception('选定的产品周期的价格未设置');
            }
            $order->order_no       = UserOrder::createOrderNo();
            $order->user_id        = $user->id;
            $order->product_id     = $product->id;
            $order->order_type     = 1;
            $order->product_price  = $product->productPrice($product_period);
            $order->product_period = $product_period;
            $order->order_total    = ($order_total == '' ? $order->product_price : $order_total);
            $order->order_status   = 1;
            $order->created_time   = time();
            $order->updated_time   = time();
            $order->expired_time   = time() + 600;
            $order->execute_status = 0;
            $order->save();
        } catch (\Exception $e) {
            return $response->withJson([
                'ret' => 0,
                'msg' => $e->getMessage(),
            ]);
        }

        return $response->withJson([
            'ret' => 1,
            'msg'   =>  '成功'
        ]);
    }
}