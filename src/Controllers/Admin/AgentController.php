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
use App\Utils\{ 
    DatatablesHelper 
};
use Ozdemir\Datatables\Datatables;
use Slim\Http\{
    Request,
    Response
};

class AgentController extends AdminController
{
    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function index($request, $response, $args)
    {
        $table_config['total_column'] = array( 
            'id' => 'ID',
            'type' => '提现类型', 
            'userid' => '用户', 
            'total' => '金额', 
            'status' => '状态',
            'datetime' => '时间',
            'action' => '操作'
        );
        $table_config_commission['total_column'] = array(
            'id'              => 'ID',
            'total'           => '原始金额',
            'userid'   => '发起用户ID',
            'ref_by'     => '获利用户ID',
            'commission'         => '佣金',
            'datetime'        => '时间'
        );
        $table_config_commission['ajax_url'] = '/admin/agent/commission/ajax';

        $table_config['ajax_url'] = '/admin/agent/withdraw/ajax';
        $this->view()
            ->assign('table_config', $table_config)
            ->assign('table_config_commission', $table_config_commission)
            ->display('admin/commission.tpl');
        return $response;
    }

    /**
     *
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function withdrawAjax($request, $response, $args)
    {
        $query = Withdraw::getTableDataFromAdmin(
            $request,
            static function (&$order_field) {
                if (in_array($order_field, ['action'])) {
                    $order_field = 'id';
                }
            }
        );
        $mark_done = "'mark_done'";
        $go_back = "'go_back'";
        $data  = [];
        foreach ($query['datas'] as $value) {
            /** @var Ann $value */

            $tempdata            = [];
            $tempdata['id']      = $value->id;
            $tempdata['userid']    = $value->userid;
            $tempdata['total'] = $value->total;
            $tempdata['type'] = $value->type == 1 ? '提现至余额' : '提现至USDT';
            $tempdata['status'] = $value->status();
            $tempdata['datetime'] = date('Y-m-d H:i:s', $value->datetime);
            $tempdata['action']                   = '<div class="btn-group dropstart"><a class="btn btn-light-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false">操作</a>
                                                        <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item" type="button" onclick="zeroAdminUpdateWithdrawCommission('.$mark_done.', '.$value->id.')">完成</a></li>
                                                            <li><a class="dropdown-item" href="#" onclick="zeroAdminUpdateWithdrawCommission('.$go_back.', '.$value->id.')">拒绝</a></li>
                                                        </ul>
                                                    </div>';
            $data[] = $tempdata;
        }

        return $response->withJson([
            'draw'            => $request->getParam('draw'),
            'recordsTotal'    => Withdraw::count(),
            'recordsFiltered' => $query['count'],
            'data'            => $data,
        ]);
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function updateWithdrawCommission($request, $response, $args)
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

    public function commissionAjax($request, $response, $args)
    {
        $query = Payback::getTableDataFromAdmin(
            $request
        );
        $data = [];
        foreach ($query['datas'] as $value) {
            $tempdata                   = [];
            $tempdata['id']             = $value->id;
            $tempdata['total']          = $value->total;
            $tempdata['userid']         = $value->userid;
            $tempdata['ref_by']         = $value->ref_by;
            $tempdata['ref_get']        = $value->ref_get;
            $tempdata['datetime']       = date('Y-m-d H:i:s', $value->datetime);
            $data[] = $tempdata;
        }
        return $response->WithJson([
            'draw'              => $request->getParam('draw'),
            'recordsTotal'      => Payback::count(),
            'recordsFiltered'   => $query['count'],
            'data'              => $data
        ]);
    }
    

}