<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminController;
use App\Models\DetectLog;
use App\Models\DetectRule;
use App\Models\DetectBanLog;
use App\Utils\Telegram;
use Slim\Http\Response;
use Slim\Http\ServerRequest;

class BanController extends AdminController
{
    /**
     * 后台审计规则
     * 
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function index(ServerRequest $request, Response $response, $args)
    {
        $table_config['total_column'] = array(
            
            'id'    => 'ID',
            'name'  => '名称',
            'text'  => '介绍',
            'regex' => '正则表达式',
            'type'  => '类型',
            'action'    => '操作',
        );
        $table_config_ban_record['total_column'] = array(
            'id'                => 'ID',
            'user_id'           => '用户ID',
            'detect_number'     => '违规次数',
            'ban_time'          => '封禁时长(分钟)',
            'end_time'          => '封禁开始时间',
            'ban_end_time'      => '封禁结束时间',
            'all_detect_number' => '累计违规次数'
        );
        $table_config_detect_record['total_column'] = array(
            'id'          => 'ID',
            'user_id'     => '用户ID',
            'node_id'     => '节点ID',
            'list_id'     => '规则ID',
            'datetime'    => '时间'
        );
        $table_config_detect_record['ajax_url'] = 'ban/detect/record/ajax';
        $table_config_ban_record['ajax_url'] = 'ban/record/ajax';
        $table_config['ajax_url'] = 'ban/rule/ajax';
        $this->view()
            ->assign('table_config', $table_config)
            ->assign('table_config_detect_record', $table_config_detect_record)
            ->assign('table_config_ban_record', $table_config_ban_record)
            ->display('admin/ban.tpl');
        return $response;
    }

    /**
     * 后台审计规则AJAX
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function banRuleAjax(ServerRequest $request, Response $response, $args): Response
    {
        $query = DetectRule::getTableDataFromAdmin(
            $request,
            static function (&$order_field) {
                if (in_array($order_field, ['action'])) {
                    $order_field = 'id';
                }
            }
        );

        $type_1 = "'request'";
        $type_2 = "'ban_rule'";
        $data  = [];
        foreach ($query['datas'] as $value) {
            /** @var DetectRule $value */

            $tempdata             = [];
            $tempdata['id']       = $value->id;
            $tempdata['name']     = $value->name;
            $tempdata['text']     = $value->text;
            $tempdata['regex']    = $value->regex;
            $tempdata['type']     = $value->type();
            $tempdata['action']   = '<div class="btn-group dropstart"><a class="btn btn-light-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false">操作</a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" onclick="zeroAdminUpdateBanRule('.$type_1.', '.$value->id.')">编辑</a></li>
                                            <li><a class="dropdown-item" type="button" onclick="zeroAdminDelete('.$type_2.', '.$value->id.')">删除</a></li>
                                        </ul>
                                    </div>';
            $data[] = $tempdata;
        }

        return $response->withJson([
            'draw'            => $request->getParam('draw'),
            'recordsTotal'    => DetectRule::count(),
            'recordsFiltered' => $query['count'],
            'data'            => $data,
        ]);
    }

    /**
     * 后台增加审计规则页面
     * 
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function createBanRule(ServerRequest $request, Response $response, $args): Response
    {
        $rule = new DetectRule();
        $rule->name = $request->getParam('name');
        $rule->text = $request->getParam('text');
        $rule->regex = $request->getParam('regex');
        $rule->type = $request->getParam('type');

        if (!$rule->save()) {
            return $response->withJson([
                'ret' => 0,
                'msg' => '添加失败'
            ]);
        }

        Telegram::SendMarkdown('有新的审计规则：' . $rule->name);
        return $response->withJson([
            'ret' => 1,
            'msg' => '添加成功'
        ]);
    }

    /**
     * 后台编辑审计规则页面
     * 
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function updateBanRule(ServerRequest $request, Response $response, $args): Response
    {
        $id = $request->getParam('id');
        $rule = DetectRule::find($id);

        $rule->name = $request->getParam('name');
        $rule->text = $request->getParam('text');
        $rule->regex = $request->getParam('regex');
        $rule->type = $request->getParam('type');

        if (!$rule->save()) {
            return $response->withJson([
                'ret' => 0,
                'msg' => '修改失败'
            ]);
        }
        Telegram::SendMarkdown('规则更新：' . PHP_EOL . $request->getParam('name'));
        return $response->withJson([
            'ret' => 1,
            'msg' => '修改成功'
        ]);
    }

    /**
     * 后台删除审计规则
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function deleteBanRule(ServerRequest $request, Response $response, $args): Response
    {
        $id = $request->getParam('id');
        $rule = DetectRule::find($id);
        $rule->delete();
        return $response->withJson([
            'ret' => 1,
            'msg' => '删除成功'
        ]);
    }


    /**
     * 后台用户触发审计规则AJAX
     * 
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function detectRuleRecordAjax(ServerRequest $request, Response $response, $args): Response
    {
        $query = DetectLog::getTableDataFromAdmin(
            $request,
            static function (&$order_field) {
                if (in_array($order_field, ['node_id'])) {
                    $order_field = 'node_id';
                }
                if (in_array($order_field, ['list_id'])) {
                    $order_field = 'list_id';
                }
                if (in_array($order_field, ['user_id'])) {
                    $order_field = 'user_id';
                }
            }
        );

        $data  = [];
        foreach ($query['datas'] as $value) {
            /** @var DetectLog $value */

            if ($value->rule() == null) {
                DetectLog::rule_is_null($value);
                continue;
            }
            if ($value->node() == null) {
                DetectLog::node_is_null($value);
                continue;
            }
            if ($value->user() == null) {
                DetectLog::user_is_null($value);
                continue;
            }
            $tempdata               = [];
            $tempdata['id']         = $value->id;
            $tempdata['user_id']    = $value->user_id;
            $tempdata['node_id']    = $value->node_id;
            $tempdata['list_id']    = $value->list_id;
            $tempdata['datetime']   = $value->datetime();

            $data[] = $tempdata;
        }

        return $response->withJson([
            'draw'            => $request->getParam('draw'),
            'recordsTotal'    => DetectLog::count(),
            'recordsFiltered' => $query['count'],
            'data'            => $data,
        ]);
    }

    /**
     * 后台审计封禁AJAX
     * 
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function banRecordAjax(ServerRequest $request, Response $response, $args): Response
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
            $tempdata['detect_number']        = $value->detect_number;
            $tempdata['ban_time']             = $value->ban_time;
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

    public function requestBanRule(ServerRequest $request, Response $response, $args): Response
    {
        $id = $request->getParam('id');
        $rule = DetectRule::find($id);
        return $response->withJson([
            'name'   => $rule->name,
            'id'    => $rule->id,
            'text'  => $rule->text,
            'regex' => $rule->regex,
            'type'  => $rule->type
        ]);
    }
}