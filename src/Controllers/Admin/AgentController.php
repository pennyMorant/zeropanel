<?php

namespace App\Controllers\Admin;

use App\Controllers\{
    AdminController
};
use App\Models\{
    User, 
    Paytake 
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
    public function takeLog($request, $response, $args)
    {
        $table_config['total_column'] = array(
            'op' => '操作', 
            'id' => 'ID',
            'type' => '提现类型', 
            'userid' => '用户', 
            'total' => '金额', 
            'status' => '状态',
            'datetime' => '时间',
        );
        $table_config['default_show_column'] = array(
            'op', 
            'id',
            'type', 
            'userid',
            'total',
            'status',
            'datetime',
        );

        $table_config['ajax_url'] = '/admin/agent/take_ajax';
        $this->view()->assign('table_config', $table_config)->display('admin/agent/take_log.tpl');
        return $response;
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function ajaxTake($request, $response, $args)
    {
        $datatables = new Datatables(new DatatablesHelper());
        $datatables->query('Select id as op,id,type,userid,total,status,datetime from payback_take_log');

        $datatables->edit('op', static function ($data) {
            if ($data['status'] === 0) {
                $text = '<a class="btn btn-brand" id="mark_done" value="' . $data['id'] . '" href="javascript:void(0);" onClick="mark_done_modal_show(\'' . $data['id'] . '\')">标记完成</a>
                       <a class="btn btn-brand-accent" id="go_back" value="' . $data['id'] . '" href="javascript:void(0);" onClick="go_back_modal_show(\'' . $data['id'] . '\')">拒绝</a>';
            } else if ($data['status'] === 1) {
                $text = '该申请已结转';
            } else if ($data['status'] === -1) {
                $text = '该申请已原路退回';
            }
            return  $text;
        });

        $datatables->edit('type', static function ($data) {
            return $data['type'] === 1 ? '转至钱包余额' : '转账提现';
        });

        $datatables->edit('userid', static function ($data) {
            $take_user = User::find($data['userid']);
            $text = 'ID: ' . $take_user->id . '<br />邮箱：' . $take_user->email . '<br />代理：' . ($take_user->agent ? '是' : '否');

            if ($data['type'] === '转账提现') {
                $text .= '<br />提现账号：' . $take_user->config['take_account']['acc'] . '<br />账号类型：' . $take_user->config['take_account']['type'];
            }
            return $text;
        });

        $datatables->edit('status', static function ($data) {
            $status = [
               -1 => '已退回',
                0 => '处理中',
                1 => '已完成',
            ];
            return $status[$data['status']];
        });

        $datatables->edit('datetime', static function ($data) {
            return date('Y-m-d H:i:s', $data['datetime']);
        });

        $body = $response->getBody();
        $body->write($datatables->generate());
        return $response;
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function takeUpdate($request, $response, $args)
    {
        $mode = $args['mode'];
        $id   = $request->getParam('id');

        switch ($mode) {
            case 'mark_done': 
                $paytake = Paytake::find($id);
                $paytake->status = 1;
                if (!$paytake->save()) {
                    $res['ret'] = 0;
                    $res['msg'] = '标记失败';
                    return $response->withJson($res);   
                }
                $res['ret'] = 1;
                $res['msg'] = '标记成功';
                return $response->withJson($res);
            case 'go_back': 
                $paytake = Paytake::find($id);
                $paytake->status = -1;
                $paytake->save();
                $go_user = User::find($paytake->userid);
                $go_user->commission = bcadd($go_user->commission, $paytake->total, 2);
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

}