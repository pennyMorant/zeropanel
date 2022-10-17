<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminController;
use App\Models\DetectBanLog;
use Slim\Http\{
    Request,
    Response
};

class DetectBanLogController extends AdminController
{
    /**
     * 后台审计封禁记录
     * 
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function index($request, $response, $args)
    {
        $table_config['total_column'] = array(
            'id'                => 'ID',
            'user_id'           => '用户ID',
            'name'         => '用户名',
            'email'             => '用户邮箱',
            'detect_number'     => '违规次数',
            'ban_time'          => '封禁时长(分钟)',
            'start_time'        => '统计开始时间',
            'end_time'          => '统计结束以及封禁开始时间',
            'ban_end_time'      => '封禁结束时间',
            'all_detect_number' => '累计违规次数'
        );
        $table_config['default_show_column'] = array_keys($table_config['total_column']);
        $table_config['ajax_url'] = 'ban/ajax';
        $this->view()
            ->assign('table_config', $table_config)
            ->display('admin/detect/ban.tpl');
        return $response;
    }

    /**
     * 后台审计封禁AJAX
     * 
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function ajaxLog($request, $response, $args)
    {
        $query = DetectBanLog::getTableDataFromAdmin(
            $request,
            static function (&$order_field) {
                if (in_array($order_field, ['ban_end_time'])) {
                    $order_field = 'end_time';
                }
            }
        );

        $data  = [];
        foreach ($query['datas'] as $value) {
            /** @var DetectBanLog $value */

            if ($value->user() == null) {
                DetectBanLog::user_is_null($value);
                continue;
            }
            $tempdata                         = [];
            $tempdata['id']                   = $value->id;
            $tempdata['user_id']              = $value->user_id;
            $tempdata['name']            = $value->name;
            $tempdata['email']                = $value->email;
            $tempdata['detect_number']        = $value->detect_number;
            $tempdata['ban_time']             = $value->ban_time;
            $tempdata['start_time']           = $value->start_time();
            $tempdata['end_time']             = $value->end_time();
            $tempdata['ban_end_time']         = $value->ban_end_time();
            $tempdata['all_detect_number']    = $value->all_detect_number;

            $data[] = $tempdata;
        }

        return $response->withJson([
            'draw'            => $request->getParam('draw'),
            'recordsTotal'    => DetectBanLog::count(),
            'recordsFiltered' => $query['count'],
            'data'            => $data,
        ]);
    }
}