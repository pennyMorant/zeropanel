<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminController;
use App\Models\Payment;
use App\Models\Setting;
use Slim\Http\Response;
use Slim\Http\ServerRequest;
use Ramsey\Uuid\Uuid;

final class PaymentController extends AdminController
{
    public function paymentIndex(ServerRequest $request, Response $response, array $args): Response
    {
        $table_config['total_column'] = [
            'id'      => 'ID',
            'name'    => '名称',
            'gateway' => '网关',
            'enable'  => '启用',
            'action'  => '操作',
        ];
        $table_config['ajax_url'] = 'payment/ajax';
        $this->view()
            ->assign('table_config', $table_config)
            ->display('admin/payment.tpl');
        return $response;
    }

    public function createPayment(ServerRequest $request, Response $response, array $args): Response
    {
        if (empty(Setting::obtain('website_url'))) {
            return $response->withJson([
                'ret'   => 0,
                'msg'   => '请设置站点URL'
            ]);
        }

        $postData                = $request->getParsedBody();
        $payment                 = new Payment();
        $payment->name           = $postData['payment_name'];
        $payment->gateway        = $postData['payment_gateway'];
        $payment->config         = json_encode($postData['payment_config']);
        $payment->icon           = $postData['payment_icon'] ?: NULL;
        $payment->percent_fee    = $postData['payment_percent_fee'] ?: NULL;
        $payment->fixed_fee      = $postData['payment_fixed_fee'] ?: NULL;
        $payment->recharge_bonus = $postData['payment_recharge_bonus'] ?: NULL;
        $payment->notify_domain  = $postData['payment_notify_domain'] ?: NULL;
        $payment->enable         = 0;
        $payment->sort           = 0;
        $payment->uuid           = Uuid::uuid5(Uuid::NAMESPACE_DNS,  $postData['payment_name']. '|' . time());
        $payment->created_at     = time();
        $payment->updated_at     = time();

        $payment->save();

        return $response->withJson([
            'ret'   => 1,
            'msg'   => '成功'
        ]);
    }

    public function updatePayment(ServerRequest $request, Response $response, array $args): Response
    {
        if (empty(Setting::obtain('website_url'))) {
            return $response->withJson([
                'ret'   => 0,
                'msg'   => '请设置站点URL'
            ]);
        }
        
        $putData                = $request->getParsedBody();
        $payment                 = Payment::find($putData['id']);
        $payment->name           = $putData['payment_name'];
        $payment->gateway        = $putData['payment_gateway'];
        $payment->config         = json_encode($putData['payment_config']);
        $payment->icon           = $putData['payment_icon'] ?: NULL;
        $payment->percent_fee    = $putData['payment_percent_fee'] ?: NULL;
        $payment->fixed_fee      = $putData['payment_fixed_fee'] ?: NULL;
        $payment->recharge_bonus = $putData['payment_recharge_bonus'] ?: NULL;
        $payment->notify_domain  = $putData['payment_notify_domain'] ?: NULL;
        $payment->uuid           = $payment->uuid ?: Uuid::uuid5(Uuid::NAMESPACE_DNS,  $putData['payment_name']. '|' . time());
        $payment->updated_at     = time();
        $payment->save();

        return $response->withJson([
            'ret' => 1,
            'msg' => '成功'
        ]);
    }

    public function paymentAjax(ServerRequest $request, Response $response, array $args): Response
    {
        $query = Payment::getTableDataFromAdmin(
            $request,
            static function (&$order_field) {
                if (in_array($order_field, ['gateway'])) {
                    $order_field = 'id';
                }
            }
        );

        $data = $query['datas']->map(function($rowData) {
            return [
                'id'      => $rowData->id,
                'name'    => $rowData->name,
                'gateway' => $rowData->gateway,
                'enable'  => $rowData->enable(),
                'action'  => '<div class="btn-group dropstart"><a class="btn btn-light-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false">操作</a>
                                    <ul    class = "dropdown-menu">
                                    <li><a class = "dropdown-item" type = "button" onclick = "zeroAdminPaymentGetInfo(\'request\', ' . $rowData->id . ')">编辑</a></li>
                                    <li><a class = "dropdown-item" type = "button" onclick = "zeroAdminDelete(\'payment\', ' . $rowData->id . ')">删除</a></li>
                                    </ul>
                                </div>',
            ];
        })->toArray();

        return $response->withJson([
            'draw'            => $request->getParsedBodyParam('draw'),
            'recordsTotal'    => Payment::count(),
            'recordsFiltered' => $query['count'],
            'data'            => $data,
        ]);
    }

    public function getPaymentConfig(ServerRequest $request, Response $response, array $args): Response
    {
        $id = $request->getParsedBodyParam('id');
        $payment = Payment::find($id);
        $data = [
            'payment_name'          => $payment->name,
            'payment_icon'          => $payment->icon,
            'payment_notify_domain' => $payment->notify_domain,
            'payment_percent_fee'   => $payment->percent_fee,
            'payment_fixed_fee'     => $payment->fixed_fee,
            'payment_recharge_bonus'=> $payment->recharge_bonus,
            'payment_gateway'       => $payment->gateway,
            'payment_config'        => json_decode($payment->config),
        ];
        return $response->withJson($data);
    }

    public function deletePayment(ServerRequest $request, Response $response, array $args): Response
    {
        $id = $request->getParsedBodyParam('id');
        $payment = Payment::find($id);
        $payment->delete();
        return $response->withJson([
            'ret' => 1,
            'msg' => 'success'
        ]);
    }

    public function enablePayment(ServerRequest $request, Response $response, array $args): Response
    {
        $id              = $request->getParsedBodyParam('id');
        $payment         = Payment::find($id);
        $payment->enable = $request->getParsedBodyParam('status');
        $payment->save();
        return $response->withJson([
            'ret' => 1,
            'msg' => 'success'
        ]);
    }
}