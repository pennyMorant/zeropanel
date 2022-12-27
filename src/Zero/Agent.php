<?php

namespace App\Zero;

use App\Models\{
    User,
    Code,
    Product,
    Bought,
    Payback,
    Paytake,
    Setting
};
use App\Services\{
    ZeroConfig
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
use Slim\Http\{
    Request,
    Response
};
use Ramsey\Uuid\Uuid;

class Agent extends \App\Controllers\BaseController
{
    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function withdraw($request, $response, $args)
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
            if (!$user->withdraw_account || !$user->withdraw_account_type) {
                return $response->withJson([
                    'ret' => 1,
                    'msg' => '还未设置提现账号'
                ]);
            }
            $withdraw_less_amount = Setting::obtain('withdraw_less_amount');
            if ($withdraw_less_amount !== 0 && $commission < $withdraw_less_amount) {
                return $response->withJson([
                    'ret' => 0,
                    'msg' => '提现金额需大于' . $withdraw_less_amount
                ]);
            }
        }

        # 创建提现记录
        $paytake           = new Paytake();
        $paytake->userid   = $user->id;
        $paytake->type     = $type;
        $paytake->total    = $commission;
        $paytake->status   = ($type === 1 ? 1 : 0);
        $paytake->datetime = time();
        if (!$paytake->save()) {
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
                $paytake->delete();
                return $response->withJson([
                    'ret' => 0,
                    'msg' => '提现金额需要大于0'
                ]);
            }
            # 转至余额 直接创建 code 记录 和 增加余额
            $code               = new Code();
            $code->code         = '#'.$paytake->id.' - '.'返利转余额';
            $code->type         = 3;
            $code->number       = $commission;
            $code->isused       = 1;
            $code->userid       = $user->id;
            $code->usedatetime  = date('Y-m-d H:i:s', time());
            if (!$code->save()) {
                return $response->withJson([
                    'ret' => 0,
                    'msg' => '创建记录失败,请联系客服'
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
        $sendAdmins = (array)json_decode(Setting::obtain('telegram_general_admin_id'));
        foreach ($sendAdmins as $sendAdmin) {
            $admin_telegram_id = User::where('id', $sendAdmin)->where('is_admin', '1')->value('telegram_id');
            if ($admin_telegram_id != null) {
                Telegram::PushToAdmin($text, $admin_telegram_id);
            }
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
        $type  = trim($request->getParam('type'));  # 类型

        if (!in_array($type, json_decode(Setting::obtain('withdraw_method'), true))) {
            $res['ret'] = 0;
            $res['msg'] = '不支持该账号类型提现';
            return $response->withJson($res);
        }
        if (!$account) {
            $res['ret'] = 0;
            $res['msg'] = '提现账号不能留空';
            return $response->withJson($res);
        }

        $user->withdraw_account_type = $type;
        $user->withdraw_account = $account;

        if (!$user->save()){
            $res['ret'] = 0;
            $res['msg'] = '出现错误, 请联系客服';
            return $response->withJson($res);
        }

        $res['ret'] = 1;
        $res['msg'] = '设置成功';
        return $response->withJson($res);
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function ajaxDatatable($request, $response, $args)
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
                $querys = Paytake::where('userid', $user->id)->orderBy($sort_field, $sort);
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
    public function ajaxChart($request, $response, $args)
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
