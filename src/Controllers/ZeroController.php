<?php

namespace App\Controllers;

use App\Services\{ 
    Config, 
    ZeroConfig
};
use App\Models\{
    Ip, 
    Node,
    Product,
    User,
    Link,
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
    Hash, 
    QQWry, 
    Check, 
    Tools,
    DatatablesHelper
};
use Pkly\I18Next\I18n;
use App\Zero\{
    Zero
};
use Slim\Http\{
    Request,
    Response
};
use voku\helper\AntiXSS;
use TelegramBot\Api\BotApi;
use Ozdemir\Datatables\Datatables;
use Exception;

class ZeroController extends BaseController
{
    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function withdrawCommission($request, $response, $args)
    {
        $user = $this->user;
        if ($user == null || !$user->isLogin) {
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
        if ($admin_telegram_id != null) {
            Telegram::PushToAdmin($text, $admin_telegram_id);
        }

        $res['ret'] = 1;
        $res['msg'] = ($type === 1 ? '已提现至账号余额' : '提现申请成功' );
        return $response->withJson($res);
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function withdrawAccountSettings($request, $response, $args)
    {
        $user = $this->user;
        if ($user == null || !$user->isLogin) {
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

    /**
     *
     * @param Request    $request
     * @param Response   $response
     * @param array      $args
     */
    public function nodeInfo($request, $response, $args)
    {
        $user = $this->user;
        $emoji = (bool)Setting::obtain('enable_subscribe_emoji');
        if (!$user->isLogin) {
            $res = ['ret' => -1, 'msg' => '登录状态已失效'];
            return $response->withJson($res);
        }

        $id   = $args['id'];
        $node = Node::find($id);
        if ($node == null) {
            $res = ['ret' => 0, 'msg' => '节点错误,请刷新页面重新获取'];
            return $response->withJson($res);
        }
        if ($user->class < $node->node_class) {
            $res = ['ret' => 0, 'msg' => I18n::get()->t('insufficient permissions')];
            return $response->withJson($res);
        }

        switch ($node->sort) {
            case '0':
                $info = $node->getShadowsocksConfig($user, $node->custom_config);
                $res = [
                    'ret' => 1,
                    'sort' => (int) $node->sort,
                    'info' => $info,
                    'url' => URL::getShadowsocksURL($user, $node, $emoji)
                ];
                break;
            case '11':
                $info = $node->getVmessConfig($user, $node->custom_config);
                $res = [
                    'ret' => 1,
                    'sort' => (int) $node->sort,
                    'info' => $info,
                    'url' => URL::getVmessURL($user, $node, $emoji),
                ];
                break;
            case '14':
                $info = $node->getTrojanConfig($user, $node->custom_config);
                $res = [
                    'ret' => 1,
                    'sort' => 14,
                    'info' => $info,
                    'url' => URL::getTrojanURL($user, $node, $emoji),
                ];
                break;
            case '15':
                $info = $node->getVlessConfig($user, $node->custom_config);
                $res = [
                    'ret' => 1,
                    'sort' => (int) $node->sort,
                    'info' => $info,
                    'url' => URL::getVlessURL($user, $node, $emoji),
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

    /**
     *
     * @param Request    $request
     * @param Response   $response
     * @param array      $args
     */
    public function ajaxDataChart($request, $response, $args)
    {
        $name = $args['name'];
        $user = $this->user;
        switch ($name) {
            case "traffic":
                $time_a = strtotime(date('Y-m-d',$_SERVER['REQUEST_TIME'])) + 86400;
                $time_b = $time_a + 86400;
                $datas = [];
                for ($i=0; $i < 8 ; $i++) {
                    $time_a -= 86400;
                    $time_b -= 86400;
                    $traffic = TrafficLog::select('*', TrafficLog::raw('SUM(u+d) as total'))->where('user_id', $user->id)->whereBetween('datetime', [$time_a, $time_b])->get();
                    //$total2   = TrafficLog::where('user_id', $user->id)->where('datetime', '>', $time_a)->where('datetime', '<', $time_b)->sum('d');
                    $total = $traffic->total < 1073741 ? 0 : $traffic->total;
                    $datas[] = [
                        'x'  => date('Y-m-d', $time_a),
                        'y' => substr(Tools::flowToGB($total), 0, 4),                      
                        'name' => I18n::get()->t('traffic'),
                    ];
                }
                return $response->withJson(array_reverse($datas));
        }
    }

    /**
     *
     * @param Request    $request
     * @param Response   $response
     * @param array      $args
     */
    public function ajaxDatatable($request, $response, $args)
    {
        $name = $args['name'];                        # 得到表名
        $user = $this->user;                          # 得到用户
        $sort = $request->getParam('order')[0]['dir'];             # 得到排序方法
        $field = $request->getParam('order')[0]['column'];            
        $sort_field = $request->getParam('columns')[$field]['data'];                                             # 得到排序字段
        if ($user == null || !$user->isLogin) {
            return 0;
        }

        $trans = I18n::get();

        switch ($name) {
            case 'ticket':
                $querys = Ticket::query()->where('userid', $user->id)->where('rootid', 0)->orderBy($sort_field, $sort);
                $query = User::getTableDataFromAdmin($request, null, null, $querys);
                $data = [];
                foreach ($query['datas'] as $value) {
                    $tempdata['id'] = $value->id;
                    $tempdata['title'] = $value->title;
                    $tempdata['status'] = $value->status();
                    $tempdata['datetime'] = date('Y-m-d H:i:s',$value->datetime);
                    $tempdata['action'] = '<a class="btn btn-sm btn-light-primary" href="/user/ticket/view/'.$value->id.'">' . $trans->t('details') . '</a>';
                    $data[] = $tempdata;
                }

                $recordsTotal = $query['count'];
                $recordsFiltered = $query['count'];
                break;
            case 'order':
                $querys = Order::query()->where('user_id', $user->id)->orderBy($sort_field, $sort);
                $query = User::getTableDataFromAdmin($request, null, null, $querys);
                $data = [];
                foreach ($query['datas'] as $value) {
                    $tempdata['no']                = $value->no;
                    $tempdata['order_total']       = $value->order_total;
                    $tempdata['order_status']      = $value->status();
                    $tempdata['order_type']        = $value->order_type == 1 ? $trans->t('purchase product') : $trans->t('add credit');
                    $tempdata['created_time']      = date('Y-m-d H:i:s', $value->created_time);
                    $tempdata['expired_time']      = $value->expired_time;
                    $tempdata['action']            = '<a class="btn btn-sm btn-light-primary" href="/user/order/'.$value->no.'">' . $trans->t('details') . '</a>';
                    $data[]                        = $tempdata;
                }

                $recordsTotal = $query['count'];
                $recordsFiltered = $query['count'];
                break;
            case 'loginlog':
                $time = $_SERVER['REQUEST_TIME'] - 86400 * 7;
                $querys = SigninIp::query()->where('userid', $user->id)->where('type', 0)->where('datetime', '>', $time)->orderBy($sort_field, $sort);
                $query = User::getTableDataFromAdmin($request, null, null, $querys);
                $data = [];
                foreach ($query['datas'] as $value) {
                    $tempdata['id']          = $value->id;
                    $tempdata['ip']          = $value->ip;
                    $tempdata['location']    = Tools::getIpInfo($value->ip);
                    $tempdata['datetime']    = date('Y-m-d H:i:s', $value->datetime);
                    $data[]                  = $tempdata;
                    
                }
                $recordsTotal = $query['count'];
                $recordsFiltered = $query['count'];
                break;
            case 'uselog':
                $time = $_SERVER['REQUEST_TIME'] - 86400 * 7;               
                $querys = Ip::query()->where('userid', $user->id)->where('datetime', '>', $time)->orderBy($sort_field, $sort);
                $query = User::getTableDataFromAdmin($request, null, null, $querys);
                $data = [];
                foreach ($query['datas'] as $value) {
                    $tempdata['id']          = $value->id;
                    $tempdata['ip']          = $value->ip;
                    $tempdata['location']    = Tools::getIpInfo($value->ip);
                    $tempdata['datetime']    = date('Y-m-d H:i:s', $value->datetime);
                    $data[]                  = $tempdata;
                }
                $recordsTotal = $query['count'];
                $recordsFiltered = $query['count'];
                break;
            case 'sublog':
                $querys = UserSubscribeLog::query()->where('user_id', $user->id)->orderBy($sort_field, $sort);
                $query = User::getTableDataFromAdmin($request, null, null, $querys);
                $data = [];
                foreach ($query['datas'] as $value) {
                    $tempdata['id'] = $value->id;
                    $tempdata['subscribe_type'] = $value->subscribe_type;
                    $tempdata['request_ip'] = $value->request_ip;
                    $tempdata['location'] = Tools::getIpInfo($value->request_ip);
                    $tempdata['request_time'] = $value->request_time;
                    $tempdata['request_user_agent'] = $value->request_user_agent;
                    $data[] = $tempdata;
                }
                $recordsTotal = $query['count'];
                $recordsFiltered = $query['count'];
                break;
            case 'trafficlog':
                $querys = TrafficLog::query()->where('user_id', $user->id)->where('datetime', '>', time() - 7 * 86400)->orderBy($sort_field, $sort);
                $query = User::getTableDataFromAdmin($request, null, null, $querys);
                $data = [];
                foreach ($query['datas'] as $value) {
                    $tempdata['id'] = $value->id;
                    $tempdata['node_id'] = $value->node_id;
                    $tempdata['node_name'] = $value->node()->name;
                    $tempdata['rate'] = $value->rate;
                    $tempdata['traffic'] = $value->traffic;
                    $tempdata['datetime'] = date('Y-m-d H:i:s', $value->date_time);
                    $data[] = $tempdata;
                }
                $recordsTotal = $query['count'];
                $recordsFiltered = $query['count'];
                break;
            case 'user_baned_log':
                $querys = DetectLog::query()->orderBy($sort_field, 'desc')->where('user_id', $user->id);
                $query = User::getTableDataFromAdmin($request, null, null, $querys);
                $data = [];
                foreach ($query['datas'] as $value) {
                    $tempdata['id'] = $value->id;
                    $tempdata['node_id'] = $value->node_id;
                    $tempdata['node_name'] = $value->Node()->name;
                    $tempdata['list_id'] = $value->list_id;
                    $tempdata['rule_name'] = $value->DetectRule()->name;
                    $tempdata['rule_text'] = $value->DetectRule()->text;
                    $tempdata['rule_regex'] = $value->DetectRule()->regex;
                    $tempdata['rule_type'] = ($value->DetectRule()->type === 1 ? $trans->t('packet plaintext match') : ($value->DetectRule()->type === 2 ? $trans->t('packet hex match') : '未知'));
                    $tempdata['datetime'] = date('Y-m-d H:i:s',$value->datetime);
                    $data[] = $tempdata;
                }
                $recordsTotal = $query['count'];
                $recordsFiltered = $query['count'];
                break;               
            case 'ban_rule':
                $querys = DetectRule::query()->orderBy($sort_field, $sort);
                $query = User::getTableDataFromAdmin($request, null, null, $querys);
                $data = [];

                foreach ($query['datas'] as $value) {
                    $tempdata['id'] = $value->id;
                    $tempdata['name'] = $value->name;
                    $tempdata['text'] = $value->text;
                    $tempdata['regex'] = $value->regex;
                    $tempdata['type'] = ($value->type === 1 ? $trans->t('packet plaintext match') : ($value->type === 2 ? $trans->t('packet plaintext match') : '未知'));
                    $data[] = $tempdata;
                }
                $recordsTotal = $query['count'];
                $recordsFiltered = $query['count'];
                break;
            case 'get_commission_log':
                $querys = Payback::where('ref_by', '=', $user->id)->orderBy($sort_field, $sort);
                $query = User::getTableDataFromAdmin($request, null, null, $querys);
                $data = [];
                
                foreach ($query['datas'] as $value) {
                    $tempdata['id'] = $value->id;
                    $tempdata['userid'] = $value->userid;
                    $tempdata['ref_get'] = $value->ref_get;
                    $tempdata['datetime'] = date('Y-m-d h:i:s', $value->datetime);
                    $data[] = $tempdata;
                }
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

    /**
     *
     * @param Request    $request
     * @param Response   $response
     * @param array      $args
     */
    public function ajaxDatatableDelete($request, $response, $args)
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