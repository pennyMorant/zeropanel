<?php

namespace App\Controllers;

use App\Services\{ 
    Config, 
    ZeroConfig
};
use App\Models\{
    Ip, 
    Code,
    Node,
    Product,
    User,
    Help,
    Link,
    Helpc,
    Bought,
    Ticket,
    Order,
    Payback,
    SigninIp,
    TrafficLog,
    UserSubscribeLog,
    Setting
};
use App\Utils\{
    URL, 
    Hash, 
    QQWry, 
    Check, 
    Tools, 
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
use Exception;

class ZeroController extends BaseController
{
    /**
     *
     * @param Request    $request
     * @param Response   $response
     * @param array      $args
     */
    public function NodeInfo($request, $response, $args)
    {
        $user = $this->user;
        $emoji = (bool)Setting::obtain('enable_subscribe_emoji');
        if (!$user->isLogin) {
            $res = ['ret' => -1, 'msg' => '登录状态已失效'];
            return $response->getBody()->write(json_encode($res));
        }

        $id   = $args['id'];
        $node = Node::find($id);
        if ($node == null) {
            $res = ['ret' => 0, 'msg' => '节点错误,请刷新页面重新获取'];
            return $response->getBody()->write(json_encode($res));
        }
        if ($user->class < $node->node_class) {
            $res = ['ret' => 0, 'msg' => '权限不足'];
            return $response->getBody()->write(json_encode($res));
        }

        switch ($node->sort) {
            case '0':
                $info = $node->getShadowsocksItem($user, $node->custom_config);
                $res = [
                    'ret' => 1,
                    'sort' => (int) $node->sort,
                    'info' => $info,
                    'url' => url::getShadowsocksURL($user, $node, $emoji)
                ];
                break;
            case '11':
                $info = $node->getVmessItem($user, $node->custom_config);
                $res = [
                    'ret' => 1,
                    'sort' => (int) $node->sort,
                    'info' => $info,
                    'url' => URL::getVmessURL($user, $node, $emoji),
                ];
                break;
            case '14':
                $info = $node->getTrojanItem($user, $node->custom_config);
                $res = [
                    'ret' => 1,
                    'sort' => 14,
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
            case "traffic_chart":
                $time_a = strtotime(date('Y-m-d',$_SERVER['REQUEST_TIME'])) + 86400;
                $time_b = $time_a + 86400;
                $datas = [];
                for ($i=0; $i < 8 ; $i++) {
                    $time_a -= 86400;
                    $time_b -= 86400;
                    $total1   = TrafficLog::where('user_id', $user->id)->where('datetime', '>', $time_a)->where('datetime', '<', $time_b)->sum('u');
                    $total2   = TrafficLog::where('user_id', $user->id)->where('datetime', '>', $time_a)->where('datetime', '<', $time_b)->sum('d');
                    $datas[] = [
                        'x'  => date('Y-m-d', $time_a),
                        'y' => substr(Tools::flowToGB($total1 + $total2), 0, 4),                      
                        'name' => I18n::get()->t('general.traffic'),
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

        switch ($name) {
            case 'ticket':
                $querys = Ticket::query()->where('userid', $user->id)->where('rootid', 0)->orderBy($sort_field, $sort);
                $query = User::getTableDataFromAdmin($request, null, null, $querys);
                $data = [];
                foreach ($query['datas'] as $value) {
                    $tempdata['id'] = $value->id;
                    $tempdata['title'] = $value->title;
                    $tempdata['datetime'] = date('Y-m-d H:i:s',$value->datetime);
                    $tempdata['status'] = $value->status;
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
                    $tempdata['order_status']      = $value->order_status;
                    $tempdata['order_type']        = $value->order_type == 'purchase_product_order' ? "产品购买" : "账户充值";
                    $tempdata['created_time']      = date('Y-m-d H:i:s', $value->created_time);
                    $tempdata['expired_time']      = $value->expired_time;
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

                $iplocation = new QQWry();
                foreach ($query['datas'] as $value) {
                    $logIp                  = $value->ip;
                    if (isset($data[$logIp])) {
                        continue;
                    }
                    $location                = $iplocation->getlocation($logIp);
                    $tempdata['id']          = $value->id;
                    $tempdata['ip']          = $logIp;
                    $tempdata['location']    = iconv("gbk", "utf-8//IGNORE", $location['country']) . iconv("gbk", "utf-8//IGNORE", $location['area']);;
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
                $iplocation = new QQWry();
                foreach ($query['datas'] as $value) {
                    $logIp                  = $value->ip;
                    if (isset($data[$logIp])) {
                        continue;
                    }
                    $location                = $iplocation->getlocation($logIp);
                    $tempdata['id']          = $value->id;
                    $tempdata['ip']          = $logIp;
                    $tempdata['location']    = iconv("gbk", "utf-8//IGNORE", $location['country']) . iconv("gbk", "utf-8//IGNORE", $location['area']);;
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
                $iplocation = new QQWry();
                foreach ($query['datas'] as $value) {
                    $location = $iplocation->getlocation($value->request_ip);
                    $tempdata['id'] = $value->id;
                    $tempdata['subscribe_type'] = $value->subscribe_type;
                    $tempdata['request_ip'] = $value->request_ip;
                    $tempdata['location'] = iconv("gbk", "utf-8//IGNORE", $location['country']) . iconv("gbk", "utf-8//IGNORE", $location['area']);
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