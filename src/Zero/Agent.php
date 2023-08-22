<?php

namespace App\Zero;

use App\Models\{
    User,
    Product,
    Payback,
    Withdraw,
    Setting
};
use App\Utils\{
    GA,
    Hash,
    Check,
    Tools,
    Telegram
};
use App\Zero\{
    Zero
};
use Slim\Http\Response;
use Slim\Http\ServerRequest;
use Ramsey\Uuid\Uuid;

class Agent extends \App\Controllers\BaseController
{
    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function ajaxDatatable(ServerRequest $request, Response $response, array $args)
    {
        $name = $args['name'];                        # 得到表名
        $user = $this->user;                          # 得到用户
        $sort = $request->getParam('order')[0]['dir'];             # 得到排序方法
        $field = $request->getParam('order')[0]['column'];            
        $sort_field = $request->getParam('columns')[$field]['data'];                                             # 得到排序字段

        if ($user == null || !$user->isLogin || $user->agent < 1) { return 0; }

        switch ($name) {
            case 'agent_user':
                $querys = User::query()->where('ref_by', '=', $user->id)->orderBy($sort_field, $sort);
                $query = User::getTableDataFromAdmin($request, null, null, $querys);
                $data = [];
                foreach ($query['datas'] as $value) {
                    $tempdata['id'] = $value->id;
                    $tempdata['name'] = $value->name;
                    $tempdata['email'] = $value->email;
                    $tempdata['money'] = $value->money;
                    $tempdata['unusedTraffic'] = $value->unusedTraffic();
                    $tempdata['class_expire'] = $value->class_expire;
                    $data[] = $tempdata;
                }
                $recordsTotal = $query['count'];
                $recordsFiltered = $query['count'];
                
                break;
            case 'amount_records':
                $time_a = strtotime(date('Y-m-d',$_SERVER['REQUEST_TIME'])) + 86400;
                $time_b = $time_a + 86400;
                $datas = [];
                for ($i=0; $i < 8 ; $i++) {
                    $time_a -= 86400;
                    $time_b -= 86400;
                    $total   = Payback::where('ref_by', $user->id)->where('datetime', '>', $time_a)->where('datetime', '<', $time_b)->sum('ref_get');
                    $datas[] = [
                        'x'  => date('Y-m-d', $time_a),
                        'y' => $total ?? 0,
                    ];
                }
                return $response->withJson(array_reverse($datas));
            case 'agent_withdraw_commission_log':
                $querys = Withdraw::where('userid', $user->id)->orderBy($sort_field, $sort);
                $query = User::getTableDataFromAdmin($request, null, null, $querys);
                $data = [];
                foreach ($query['datas'] as $value) {
                    $tempdata['id'] = $value->id;
                    $tempdata['type'] = ($value->type === 1 ? '划转到账户余额' : 'USDT提现');
                    $tempdata['total'] = $value->total;
                    $tempdata['status'] = $value->status;
                    $tempdata['datetime'] = date("Y-m-d H:i:s", $value->datetime);
                    $data = $tempdata;
                }
                $recordsTotal = $query['count'];
                $recordsFiltered = $query['count'];
                break;
            default:
                return 0;
        }

        return $response->withJson([
            'draw'            => $request->getParam('draw'),
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $data,
        ]);
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function ajaxChart(ServerRequest $request, Response $response, array $args)
    {
        $name = $args['name'];
        $user = $this->user;
        if ($user == null || !$user->isLogin || $user->agent < 1) { return 0; }
        switch ($name) {
            case 'commission_records':
                $time_a = strtotime(date('Y-m-d',$_SERVER['REQUEST_TIME'])) + 86400;
                $time_b = $time_a + 86400;
                $datas = [];
                for ($i=0; $i < 14 ; $i++) {
                    $time_a -= 86400;
                    $time_b -= 86400;
                    $total   = Payback::where('ref_by', $user->id)->where('datetime', '>', $time_a)->where('datetime', '<', $time_b)->sum('ref_get');
                    $datas[] = [
                        'x' => date('m-d', $time_a),
                        'y' => $total,
                    ];
                }
                return $response->withJson(array_reverse($datas));
            case 'user_records':
                $time_a = strtotime(date('Y-m-d',$_SERVER['REQUEST_TIME'])) + 86400;
                $time_b = $time_a + 86400;
                $datas = [];
                for ($i=0; $i < 14 ; $i++) {
                    $time_a -= 86400;
                    $time_b -= 86400;
                    $total   = User::where('ref_by', $user->id)->where('signup_date', '>', date('Y-m-d H:i:s', $time_a))->where('signup_date', '<', date('Y-m-d H:i:s', $time_b))->count();
                    $datas[] = [
                        'x' => date('m-d', $time_a),
                        'y' => $total,
                    ];
                }
                return $response->withJson(array_reverse($datas));
            default:
                return 0;
        return $response->withJson('success');
        }
    }
}
