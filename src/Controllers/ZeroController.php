<?php

namespace App\Controllers;

use App\Models\{
    Ip, 
    Node,
    User,
    Ticket,
    Order,
    Payback,
    SigninIp,
    TrafficLog,
    UserSubscribeLog,
    Setting,
    DetectRule,
    DetectLog,
    Withdraw
};
use App\Utils\{
    URL, 
    Tools,
    Telegram
};
use Pkly\I18Next\I18n;
use Slim\Http\Response;
use Slim\Http\ServerRequest;
use Exception;

class ZeroController extends BaseController
{
    public function withdrawCommission(ServerRequest $request, Response $response, array $args)
    {
        $user = $this->user;
        if (is_null($user) || !$user->isLogin) {
            return $response->withJson([
                'ret' => -1
            ]);
        }

        $commission = (int) trim($request->getParam('commission'));         # 金额
        $type  = (int) trim($request->getParam('type'));    # 1:转余额 2:提现

        if (!is_numeric($commission)) {
            return $response->withJson([
                'ret' => 0,
                'msg' => '非法金额'
            ]);
        }

        if ($commission > $user->commission) {
            return $response->withJson([
                'ret' => 0,
                'msg' => '可提现余额不足'
            ]);
        }

        # 提现
        if ($type === 2) {
            # 检查是否有提现账号
            if (!$user->withdraw_account) {
                return $response->withJson([
                    'ret' => 0,
                    'msg' => '还未设置提现账号'
                ]);
            }
            $withdraw_minimum_amount = Setting::obtain('withdraw_minimum_amount');
            if ($withdraw_minimum_amount !== 0 && $commission < $withdraw_minimum_amount) {
                return $response->withJson([
                    'ret' => 0,
                    'msg' => '提现金额需大于' . $withdraw_minimum_amount
                ]);
            }
        }

        # 创建提现记录
        $withdraw           = new Withdraw();
        $withdraw->userid   = $user->id;
        $withdraw->type     = $type;
        $withdraw->total    = $commission;
        $withdraw->status   = ($type === 1 ? 1 : 0);
        $withdraw->datetime = time();
        if (!$withdraw->save()) {
            return $response->withJson([
                'ret' => 0,
                'msg' => '创建提现申请失败,请联系客服'
            ]);
        }

        # 扣除用户返利余额
        $user->commission = bcsub($user->commission, $commission, 2);

        # 转余额
        if ($type === 1){
            if ($commission <= 0) {
                $withdraw->delete();
                return $response->withJson([
                    'ret' => 0,
                    'msg' => '提现金额需要大于0'
                ]);
            }
            $user->money        = bcadd($user->money, $commission, 2);
        }

        if (!$user->save()){
            return $response->withJson([
                'ret' => 0,
                'msg' => '发生错误,请联系客服'
            ]);
        }
        $text = '提现提醒' . PHP_EOL .
            '------------------------------' . PHP_EOL .
            '用户：' . $user->email . '  #' . $user->id . PHP_EOL .
            '提现类型：' . $type === 1 ? '提现到余额' : '提现到其他账户' . PHP_EOL .
            '提现金额：' . $commission . PHP_EOL .
            '提现时间：' . date('Y-m-d H:i:s', time());
        $sendAdmin = Setting::obtain('telegram_admin_id');
        $admin_telegram_id = User::where('id', $sendAdmin)->where('is_admin', '1')->value('telegram_id');
        if (!is_null($admin_telegram_id)) {
            Telegram::pushToAdmin($text, $admin_telegram_id);
        }

        $res['ret'] = 1;
        $res['msg'] = ($type === 1 ? '已提现至账号余额' : '提现申请成功' );
        return $response->withJson($res);
    }

    public function withdrawAccountSettings(ServerRequest $request, Response $response, array $args)
    {
        $user = $this->user;
        if (is_null($user) || !$user->isLogin) {
            $res['ret'] = -1;
            return $response->withJson($res);
        }

        $account   = trim($request->getParam('acc'));   # 账号
        $type  = trim($request->getParam('method'));  # 类型

        if ($type !== Setting::obtain('withdraw_method')) {
            $res['ret'] = 0;
            $res['msg'] = '不支持该账号类型提现';
            return $response->withJson($res);
        }
        if (!$account) {
            $res['ret'] = 0;
            $res['msg'] = '提现账号不能留空';
            return $response->withJson($res);
        }

        $user->withdraw_account = $account;
        $user->save();
        return $response->withJson([
            'ret' => 1,
            'msg' => '设置成功'
        ]);
    }

    public function nodeInfo(ServerRequest $request, Response $response, array $args)
    {
        $user = $this->user;
        $emoji = (bool)Setting::obtain('enable_subscribe_emoji');
        if (!$user->isLogin) {
            $res = ['ret' => -1, 'msg' => '登录状态已失效'];
            return $response->withJson($res);
        }

        $id   = $args['id'];
        $node = Node::find($id);
        if (is_null($node)) {
            $res = ['ret' => 0, 'msg' => '节点错误,请刷新页面重新获取'];
            return $response->withJson($res);
        }
        if ($user->class < $node->node_class) {
            $res = ['ret' => 0, 'msg' => I18n::get()->t('insufficient permissions')];
            return $response->withJson($res);
        }

        switch ($node->node_type) {
            case '1':
                $info = $node->getShadowsocksConfig($user, $node->custom_config);
                $res = [
                    'ret' => 1,
                    'type' => 1,
                    'info' => $info,
                    'url' => URL::getShadowsocksURL($user, $node, $emoji)
                ];
                break;
            case '2':
                $info = $node->getVmessConfig($user, $node->custom_config);
                $res = [
                    'ret' => 1,
                    'type' => 2,
                    'info' => $info,
                    'url' => URL::getVmessURL($user, $node, $emoji),
                ];
                break;
            case '3':
                $info = $node->getVlessConfig($user, $node->custom_config);
                $res = [
                    'ret' => 1,
                    'type' => 3,
                    'info' => $info,
                    'url' => URL::getVlessURL($user, $node, $emoji),
                ];
                break;
            case '4':
                $info = $node->getTrojanConfig($user, $node->custom_config);
                $res = [
                    'ret' => 1,
                    'type' => 4,
                    'info' => $info,
                    'url' => URL::getTrojanURL($user, $node, $emoji),
                ];
                break;
            default:
                $res = [
                    'ret' => 0,
                    'msg' => '该节点暂不支持查看配置',
                ];
                break;
        }

        return $response->withJson($res);

    }

    public function ajaxDataChart(ServerRequest $request, Response $response, array $args)
    {
        $name = $args['name'];
        $user = $this->user;
        switch ($name) {
            case "traffic":               
                 // 获取7天内的起始和结束时间戳
                 $time_a = strtotime(date('Y-m-d', $_SERVER['REQUEST_TIME']) . " -6 days");
                 $time_b = $time_a + 86400 * 8;
                // 查询7天内按日期分组的流量数据，并转换成GB 
                $traffic = TrafficLog::where('user_id', $user->id)
                    ->whereBetween('datetime', [$time_a, $time_b])
                    ->selectRaw('DATE(FROM_UNIXTIME(datetime)) as x, ROUND(SUM((u) + (d)) / 1024 / 1024 / 1024, 2) as y')
                    ->groupBy('x')->pluck('y', 'x');
                // 把日期和流量数据添加到数组中，并补充没有流量数据的日期
                $dates = array_map(function ($i) {
                    return date('Y-m-d', $_SERVER['REQUEST_TIME'] - $i * 86400);
                }, range(6, 0));

                $trafficData = array_fill_keys($dates, '0');
                $trafficData = array_replace($trafficData, $traffic->toArray());

                $datas = array_map(function ($date, $flow) {
                    return [
                        'x' => $date,
                        'y' => $flow,
                        'name' => I18n::get()->t('traffic'),
                    ];
                }, array_keys($trafficData), $trafficData);
                return $response->withJson($datas);
                break;
            default:
                return 0;
        }
    }

    public function ajaxDatatable(ServerRequest $request, Response $response, array $args)
    {
        $name = $args['name'];                        # 得到表名
        $user = $this->user;                          # 得到用户
        $sort = $request->getParam('order')[0]['dir'];             # 得到排序方法
        $field = $request->getParam('order')[0]['column'];            
        $sort_field = $request->getParam('columns')[$field]['data'];                                             # 得到排序字段
        if (is_null($user) || !$user->isLogin) {
            return 0;
        }
        
        switch ($name) {
            case 'ticket':
                $querys = Ticket::query()->where('userid', $user->id)->orderBy($sort_field, $sort);
                $query = Ticket::getTableDataFromAdmin($request, null, null, $querys);
                
                $data = $query['datas']->map(function($rowData) {
                    $trans = I18n::get();
                    $type = "'ticket'";
                    $comments = json_decode($rowData->content, true);
                    foreach ($comments as $comment) {
                        $last_updated = date('Y-m-d H:i:s', $comment['datetime']);
                    }
                    return [
                        'id'    => $rowData->id,
                        'type'  =>  $rowData->type,
                        'title' =>  $rowData->title,
                        'status'    =>  $rowData->status(),
                        'datetime'  =>  date('Y-m-d H:i:s', $rowData->datetime),
                        'last_updated'  =>  $last_updated,
                        'action'    =>  '<a class="btn btn-sm btn-light-primary" href="/user/ticket/view/'.$rowData->id.'">' . $trans->t('details') . '</a>',
                    ];
                })->toArray();

                $recordsTotal = $query['count'];
                $recordsFiltered = $query['count'];
                break;
            case 'order':
                $querys = Order::query()->where('user_id', $user->id)->orderBy($sort_field, $sort);
                $query = Order::getTableDataFromAdmin($request, null, null, $querys);

                $data = $query['datas']->map(function($rowData) {
                    $trans = I18n::get();

                    return [
                        'order_total'   =>  $rowData->order_total,
                        'order_status'  =>  $rowData->status(),
                        'order_no'  =>  $rowData->order_no,
                        'created_time'  =>  date('Y-m-d H:i:s', $rowData->created_time),
                        'order_type'    =>  $rowData->orderType(),
                        'action'    =>  '<a class="btn btn-sm btn-light-primary" href="/user/order/'.$rowData->order_no.'">' . $trans->t('details') . '</a>',
                    ];
                })->toArray();

                $recordsTotal = $query['count'];
                $recordsFiltered = $query['count'];
                break;
            case 'loginlog':
                $time = $_SERVER['REQUEST_TIME'] - 86400 * 7;
                $querys = SigninIp::query()->where('userid', $user->id)->where('type', 0)->where('datetime', '>', $time)->orderBy($sort_field, $sort);
                $query = SigninIp::getTableDataFromAdmin($request, null, null, $querys);

                $data = $query['datas']->map(function($rowData) {
                    return [
                        'ip'    =>  $rowData->ip,
                        'location'  =>  Tools::getIPLocation($rowData->ip),
                        'datetime'  =>  date('Y-m-d H:i:s', $rowData->datetime),
                    ];
                })->toArray();
                $recordsTotal = $query['count'];
                $recordsFiltered = $query['count'];
                break;
            case 'uselog':
                $time = $_SERVER['REQUEST_TIME'] - 86400 * 7;               
                $querys = Ip::query()->where('userid', $user->id)->where('datetime', '>', $time)->orderBy($sort_field, $sort);
                $query = Ip::getTableDataFromAdmin($request, null, null, $querys);
                $data = $query['datas']->map(function($rowData) {
                    return [
                        'ip'    =>  $rowData->ip,
                        'location'  =>  Tools::getIPLocation($rowData->ip),
                        'datetime'  => date('Y-m-d H:i:s', $rowData->datetime),
                    ];
                })->toArray();

                $recordsTotal = $query['count'];
                $recordsFiltered = $query['count'];
                break;
            case 'sublog':
                $querys = UserSubscribeLog::query()->where('user_id', $user->id)->orderBy($sort_field, $sort);
                $query = UserSubscribeLog::getTableDataFromAdmin($request, null, null, $querys);

                $data = $query['datas']->map(function($rowData) {
                    return [
                        'subscribe_type'    => $rowData->subscribe_type,
                        'request_ip'    =>  $rowData->request_ip,
                        'location'  =>  Tools::getIPLocation($rowData->request_ip),
                        'request_time'  =>  date('Y-m-d H:i:s', $rowData->request_time),
                    ];
                })->toArray();
                $recordsTotal = $query['count'];
                $recordsFiltered = $query['count'];
                break;
            case 'trafficlog':
                $querys = TrafficLog::query()->where('user_id', $user->id)->where('datetime', '>', time() - 7 * 86400)->orderBy($sort_field, $sort);
                $query = TrafficLog::getTableDataFromAdmin($request, null, null, $querys);

                $data = $query['datas']->map(function($rowData) {
                    return [
                        'node_name'   =>  $rowData->node()->name,
                        'traffic'   =>  $rowData->traffic,
                        'datetime'  =>  date('Y-m-d H:i:s', $rowData->datetime),
                    ];
                })->toArray();

                $recordsTotal = $query['count'];
                $recordsFiltered = $query['count'];
                break;
            case 'user_baned_log':
                $querys = DetectLog::query()->orderBy($sort_field, 'desc')->where('user_id', $user->id);
                $query = DetectLog::getTableDataFromAdmin($request, null, null, $querys);

                $data = $query['datas']->map(function($rowData) {
                    return [
                        'node_name' =>  $rowData->node()->name,
                        'rule_name' =>  $rowData->detectRule()->name,
                        'datetime'  =>  date('Y-m-d H:i:s', $rowData->datetime),
                    ];
                })->toArray();

                $recordsTotal = $query['count'];
                $recordsFiltered = $query['count'];
                break;               
            case 'ban_rule':
                $querys = DetectRule::query()->orderBy($sort_field, $sort);
                $query = DetectRule::getTableDataFromAdmin($request, null, null, $querys);

                $data = $query['datas']->map(function($rowData) {
                    return [
                        'name'  =>  $rowData->name,
                        'text'  =>  $rowData->text,
                    ];
                })->toArray();

                $recordsTotal = $query['count'];
                $recordsFiltered = $query['count'];
                break;
            case 'get_commission_log':
                $querys = Payback::where('ref_by', '=', $user->id)->orderBy($sort_field, $sort);
                $query = Payback::getTableDataFromAdmin($request, null, null, $querys);

                $data = $query['datas']->map(function($rowData) {
                    return [
                        'total'     =>  $rowData->total,
                        'ref_get'   =>  $rowData->ref_get,
                        'datetime'  =>  date('Y-m-d H:i:s', $rowData->datetime),
                    ];
                })->toArray();

                $recordsTotal = $query['count'];
                $recordsFiltered = $query['count'];
                break;
            default:
                return 0;
                break;
        }

        return $response->withJson([
            'draw'            => $request->getParam('draw'),
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $data,
        ]);
    }

    public function ajaxDatatableDelete(ServerRequest $request, Response $response, array $args)
    {
        $name = $request->getParam('name');
        $id = $request->getParam('id');
        $mode = $request->getParam('mode');

        switch ($name) {
            case 'order':
                $table = Order::find($id);

                if($table->status === 1) {
                    $res = ['ret' => 0, 'msg' =>'已到账的订单无法删除'];
                    return $response->getBody()->write(json_encode($res, JSON_UNESCAPED_UNICODE));
                }

                if ($table->userid !== $this->user->id) {
                    $res = ['ret' => 0, 'msg' =>'非法操作'];
                    return $response->getBody()->write(json_encode($res, JSON_UNESCAPED_UNICODE));
                }

                break;
            case('subscribe_log'):
                $table = UserSubscribeLog::find($id);

                if($table->user_id !== $this->user->id) {
                    $res = ['ret' => 0, 'msg' =>'非法操作'];
                    return $response->getBody()->write(json_encode($res, JSON_UNESCAPED_UNICODE));
                }
                break;
            default:
                $res = ['ret' => 0, 'msg' =>'删除失败'];
                return $response->getBody()->write(json_encode($res, JSON_UNESCAPED_UNICODE));
                break;
        }

        if (!$table->delete()) {
            $res = ['ret' => 0, 'msg' =>'删除失败'];
            return $response->getBody()->write(json_encode($res, JSON_UNESCAPED_UNICODE));
        }
        $res = ['ret' => 1, 'msg' =>'删除成功'];
        return $response->getBody()->write(json_encode($res, JSON_UNESCAPED_UNICODE));
    }
}