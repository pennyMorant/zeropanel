<?php

namespace App\Controllers\Admin;

use App\Controllers\{
    AdminController
};
use App\Models\{
    User, 
    Withdraw,
    Payback
};
use Slim\Http\Response;
use Slim\Http\ServerRequest;

class CommissionController extends AdminController
{
    public function commissionIndex(ServerRequest $request, Response $response, array $args)
    {
        $table_config['total_column'] = [
            'id' => 'ID',
            'type' => '提现类型', 
            'userid' => '用户', 
            'total' => '金额', 
            'status' => '状态',
            'datetime' => '时间',
            'action' => '操作'
        ];
        $table_config_commission['total_column'] = [
            'id'              => 'ID',
            'total'           => '原始金额',
            'userid'   => '发起用户ID',
            'ref_by'     => '获利用户ID',
            'ref_get'         => '佣金',
            'datetime'        => '时间'
        ];
        $table_config_commission['ajax_url'] = '/admin/commission/ajax';

        $table_config['ajax_url'] = '/admin/commission/withdraw/ajax';
        $this->view()
            ->assign('table_config', $table_config)
            ->assign('table_config_commission', $table_config_commission)
            ->display('admin/commission.tpl');
        return $response;
    }

    public function withdrawAjax(ServerRequest $request, Response $response, array $args)
    {
        $query = Withdraw::getTableDataFromAdmin(
            $request,
            static function (&$order_field) {
                if (in_array($order_field, ['action'])) {
                    $order_field = 'id';
                }
            }
        );
        
        $data = $query['datas']->map(function($rowData) {
            $mark_done = "'mark_done'";
            $go_back = "'go_back'";
            return [
                'id'    => $rowData->id,
                'userid'    =>  $rowData->userid,
                'total' =>  $rowData->total,
                'type'  =>  $rowData->type === 1 ? '提现至余额' : '提现至USDT',
                'status'    =>  $rowData->status(),
                'datetime'  =>  date('Y-m-d H:i:s', $rowData->datetime),
                'action'    =>  '<div class="btn-group dropstart"><a class="btn btn-light-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false">操作</a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" type="button" onclick="zeroAdminUpdateWithdrawCommission('.$mark_done.', '.$rowData->id.')">完成</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="zeroAdminUpdateWithdrawCommission('.$go_back.', '.$rowData->id.')">拒绝</a></li>
                                    </ul>
                                </div>',
            ];
        })->toArray();

        return $response->withJson([
            'draw'            => $request->getParam('draw'),
            'recordsTotal'    => Withdraw::count(),
            'recordsFiltered' => $query['count'],
            'data'            => $data,
        ]);
    }

    public function updateWithdrawCommission(ServerRequest $request, Response $response, array $args)
    {
        $mode = $request->getParam('mode');
        $id   = $request->getParam('id');

        switch ($mode) {
            case 'mark_done': 
                $withdraw = Withdraw::find($id);
                $withdraw->status = 1;
                if (!$withdraw->save()) {
                    $res['ret'] = 0;
                    $res['msg'] = '标记失败';
                    return $response->withJson($res);   
                }
                $res['ret'] = 1;
                $res['msg'] = '标记成功';
                return $response->withJson($res);
            case 'go_back': 
                $withdraw = Withdraw::find($id);
                $withdraw->status = -1;
                $withdraw->save();
                $go_user = User::find($withdraw->userid);
                $go_user->commission = bcadd($go_user->commission, $withdraw->total, 2);
                if (!$go_user->save()) {
                    $res['ret'] = 0;
                    $res['msg'] = '退回失败';
                    return $response->withJson($res);
                }
                $res['ret'] = 1;
                $res['msg'] = '退回成功';
                return $response->withJson($res);
        }

    }

    public function commissionAjax(ServerRequest $request, Response $response, array $args)
    {
        $query = Payback::getTableDataFromAdmin(
            $request
        );
        $data = $query['datas']->map(function($rowData) {
            return [
                'id'    =>  $rowData->id,
                'total' =>  $rowData->total,
                'userid'    =>  $rowData->userid,
                'ref_by'    =>  $rowData->ref_by,
                'ref_get'   =>  $rowData->ref_get,
                'datetime'  =>  $rowData->date('Y-m-d H:i:s', $rowData->datetime),
            ];
        })->toArray();

        return $response->WithJson([
            'draw'              => $request->getParam('draw'),
            'recordsTotal'      => Payback::count(),
            'recordsFiltered'   => $query['count'],
            'data'              => $data
        ]);
    }
    

}