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
    public function pages($request, $response, $args)
    {
        $user       = $this->user;
        $backs      = Payback::where('ref_by', $user->id)->orderBy('datetime', 'desc');

        # 累计数据
        $back_sum   = $backs->sum('ref_get');       # 累计返利
        $user_num   = User::where('ref_by', $user->id)->count();

        # 最新3条返利
        $back_logs  = $backs->limit(3)->get();
        $back_news  = [];
        foreach ($back_logs as $back_log){
            $log_user = User::where('id', $back_log->userid)->first();
            if ($log_user === null) {
                $back_news[] = [
                    'name'      => '该用户已注销',
                    'email'     => '该用户已注销',
                    'avatar'    => '',
                    'time'      => date('Y-m-d H:i:s', $back_log->datetime),
                    'ref_get'   => $back_log->ref_get,
                ];
            } else {
                $back_news[] = [
                    'name'      => $log_user->name,
                    'email'     => $log_user->email,
                    'avatar'    => $log_user->getGravatarAttribute(),
                    'time'      => date('Y-m-d H:i:s', $back_log->datetime),
                    'ref_get'   => $back_log->ref_get,
                ];
            }

        }

        # 今日数据
        $unix_time  = strtotime(date('Y-m-d',time()));
        $today_back = $backs->where('datetime', '>', $unix_time)->sum('ref_get') ?? 0;
        $all_active_users = User::where('ref_by', $user->id)->where('class', '>=', 1 )->get();

        # 提现中金额
        if (!$take_total = Paytake::where('userid', $user->id)->where('type', 2)->where('status', 0)->sum('total')) {
            $take_total = 0;
        }

        $this->view()
            ->assign('back_sum', $back_sum)
            ->assign('user_num', $user_num)
            ->assign('back_news', $back_news)
            ->assign('today_back', $today_back)
            ->assign('all_active_users', $all_active_users)
            ->assign('take_total', $take_total)
            ->display('user/agent/index.tpl');
        return $response;
    }
    
    
    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function editUser($request, $response, $args)
    {
        $user = $this->user;
        if ($user == null || !$user->isLogin || $user->agent < 1) { return 0; }

        $id = $args['id'];
        $agent_referral_user = User::find($id);
        if ($agent_referral_user->ref_by !== $user->id) {
            return '您无权操作该用户';
        }

        

        $shops = Product::where('status', 1)->orderBy('name')->get();
        $email = explode('@', $agent_referral_user->email);
        $email = [ $email[0], '@'.$email[1] ];
        $this->view()
            ->assign('shops', $shops)
            ->assign('email', $email)
            ->assign('agent_referral_user', $agent_referral_user)
            ->assign('subInfo', \App\Controllers\LinkController::getSubinfo($agent_referral_user, 0))
            ->display('user/agent/user.tpl');
        return $response;
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function editUserSave($request, $response, $args)
    {
        $user = $this->user;
        if ($user == null || !$user->isLogin || $user->agent < 1) {
            $res['ret'] = -1;
            return $response->getBody()->write(json_encode($res));
        }

        $id       = $args['id'];
        $edituser = User::find($id);
        if ($edituser->ref_by !== $user->id) {
            $res['ret'] = 0;
            $res['msg'] = '您无权操作该用户';
            return $response->withJson($res);
        }

        $edituser_config = $edituser->config;
        if ($edituser_config['form_agent_create'] !== true) {
            $res['ret'] = 0;
            $res['msg'] = '您无权操作通过邀请链接或邀请码注册的用户';
            return $response->withJson($res);
        }

        $mode      = $request->getParam('mode');
        $name      = $request->getParam('name');
        $email     = strtolower(trim($request->getParam('email')));
        $password  = $request->getParam('password');
        $enable    = (int) $request->getParam('enable');
        $shopid    = (int) $request->getParam('shopid');

        switch ($mode) {
            case 'update_user_profile':
                # 昵称检验
                if (Setting::obtain('enable_change_username_user_general') != true) {
                    $res['ret'] = 0;
                    $res['msg'] = '此项不允许自行修改';
                    return $response->withJson($res);
                }
                if ($name == ''){
                    $res['ret'] = 0;
                    $res['msg'] = '昵称不允许留空';
                    return $response->withJson($res);
                }

                /*
                $regname = '/^[0-9a-zA-Z_\x{4e00}-\x{9fa5}]+$/u';
                if (!preg_match($regname,$name)){
                    $res['ret'] = 0;
                    $res['msg'] = '昵称仅支持中文、数字、字母和下划线的组合';
                    return $response->getBody()->write(json_encode($res));
                }*/
                if (strlen($name) > 24) {
                    $res['ret'] = 0;
                    $res['msg'] = '昵称太长了';
                    return $response->withJson($res);
                }

                # 检测邮箱
                if ($edituser->email != $email) {
                    if (Setting::obtain('enable_change_email_user_general') != true) {
                        $res['ret'] = 0;
                        $res['msg'] = '此项不允许自行修改，请联系管理员操作';
                        return $response->withJson($res);
                    }
                    if ($email == '') {
                        $res['ret'] = 0;
                        $res['msg'] = '未填写邮箱';
                        return $response->withJson($res);
                    }
                    $check_res = Check::isEmailLegal($email);
                    if ($check_res['ret'] == 0) {
                        return $response->withJson($check_res);
                    }
                    if (!Check::isEmailLegal($email)) {
                        $res['ret'] = 0;
                        $res['msg'] = '邮箱无效';
                        return $response->withJson($res);
                    }
                    $checkemail = User::where('email', '=', $email)->first();
                    if ($checkemail != null) {
                        $res['ret'] = 0;
                        $res['msg'] = '邮箱已存在';
                        return $response->withJson($res);
                    }
                }
                # 检测密码
                if ($password != '') {
                    if (strlen($password) < 8) {
                        $res['ret'] = 0;
                        $res['msg'] = '密码需8位以上';
                        return $response->withJson($res);
                    }
                    $edituser->password = Hash::passwordHash($password);
                    $edituser->save();
                    $edituser->clean_link();
                }

                $edituser->name    = $name;
                $edituser->email        = $email;
                $edituser->enable       = $enable;
                if (!$edituser->save()){
                    $res['ret'] = 0;
                    $res['msg'] = '保存失败';
                    return $response->withJson($res);
                }
                $res['ret'] = 1;
                $res['msg'] = '保存成功';
                return $response->withJson($res);
            case 'purchase_product':
                # 开通套餐
                if ($shopid > 0) {
                    $shop = Product::find($shopid);
                    if ($shop != null) {
                        if ($user->money < $shop->price) {
                            $res['ret'] = 0;
                            $res['msg'] = '套餐开通失败，原因是您的钱包余额不足!';
                            return $response->withJson($res);
                        }
                        $user->money = bcsub($user->money, $shop->price, 2);
                        $user->save();

                        Zero::bought_usedd($edituser, 1, 0);
                        $bought           = new Bought();
                        $bought->userid   = $edituser->id;
                        $bought->shopid   = $shop->id;
                        $bought->datetime = time();
                        $bought->renew    = 0;
                        $bought->price    = $shop->price;
                        $bought->usedd    = 1;
                        $bought->save();
                        $shop->buy($edituser);

                        Zero::getCommission($user, $edituser, $shop->price);
                        $res['ret'] = 1;
                        $res['msg'] = '套餐开通成功';
                        return $response->withJson($res);
                    } else {
                        $res['ret'] = 0;
                        $res['msg'] = '套餐开通失败，原因是套餐不存在!';
                        return $response->withJson($res);
                    }
                } else {
                    $res['ret'] = 1;
                    $res['msg'] = '不开通套餐无需保存';
                    return $response->withJson($res);
                }
                break;
            case 'reset_link':
                $edituser->clean_link();
                $res['ret'] = 1;
                $res['msg'] = '重置成功';
                return $response->withJson($res);
        }

        $res['ret'] = 0;
        $res['msg'] = '未知错误';
        return $response->withJson($res);
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */   
    public function addUser($request, $response, $args)
    {
        $user = $this->user;
        if ($user == null || !$user->isLogin || $user->agent < 1) { return 0; }

        $shop_plan = (array)ZeroConfig::get('shop_plan');
        foreach ($shop_plan as $shop_a) {
            foreach ($shop_a as $shop_b) {

            }
        }
        $shops = Product::where('status', 1)->orderBy('name')->get();

        $this->view()->assign('shops', $shops)->display('user/agent/add_user.tpl');
        return $response;
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function addUserSave($request, $response, $args)
    {
        $user = $this->user;
        if ($user == null || !$user->isLogin || $user->agent < 1) {
            $res['ret'] = -1;
            return $response->getBody()->write(json_encode($res));
        }
        $email = strtolower(trim($request->getParam('email')));
        $newuser = User::where('email', $email)->first();
        if ($newuser != null) {
            return $response->withJson([
                'ret' => 0,
                'msg' => '邮箱已经被注册了'
            ]);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $response->withJson([
                'ret' => 0,
                'msg' => '邮箱不规范'
            ]);
        }

        $configs = Setting::getClass('register');
        
        $current_timestamp             = time();
        $newuser                       = new User();
        $pass                          = Tools::genRandomChar(16);
        $newuser->name            = $email;
        $newuser->email                = $email;
        $newuser->password                 = Hash::passwordHash($pass);
        $newuser->passwd               = Tools::genRandomChar(16);
        $newuser->uuid                 = Uuid::uuid5(Uuid::NAMESPACE_DNS, $email . '|' . $current_timestamp);
        $newuser->t                    = 0;
        $newuser->u                    = 0;
        $newuser->d                    = 0;
        $newuser->method               = $configs['sign_up_for_method'];
        $newuser->protocol             = $configs['sign_up_for_protocol'];
        $newuser->protocol_param       = $configs['sign_up_for_protocol_param'];
        $newuser->obfs                 = $configs['sign_up_for_obfs'];
        $newuser->obfs_param           = $configs['sign_up_for_obfs_param'];
        $newuser->transfer_enable      = Tools::toGB($configs['sign_up_for_free_traffic']);
        $newuser->money                = 0;
        $newuser->class_expire         = date('Y-m-d H:i:s', time() + $configs['sign_up_for_class_time'] * 86400);
        $newuser->class                = $configs['sign_up_for_class'];
        $newuser->node_connector       = $configs['connection_device_limit'];
        $newuser->node_speedlimit      = $configs['connection_rate_limit'];

        $newuser->signup_date             = date('Y-m-d H:i:s');
        $newuser->signup_ip               = $_SERVER['REMOTE_ADDR'];
        $newuser->theme                = $_ENV['theme'];

        # 是代理商新建
        $newuser->ref_by                    = $user->id;
        $newuserconfig['form_agent_create'] = true;
        $newuser->config                    = $newuserconfig;

        # 注册分组
        $newuser->node_group = 0;

        if ($newuser->save()) {
            $res['ret']         = 1;
            $res['msg']         = '新用户注册成功' . PHP_EOL . '用户名： ' . $email . PHP_EOL . ' 随机初始密码： ' . $pass;
            $res['email_error'] = 'success';
            return $response->withJson($res);
        }
        $res['ret'] = 0;
        $res['msg'] = '未知错误';
       return $response->withJson($res);
    }

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
            '用户名：' . $user->name . '  #' . $user->id . PHP_EOL .
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
    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function delete($request, $response, $args)
    {
        $user = $this->user;

        if ($user == null || !$user->isLogin || $user->agent < 1) {
            $res['ret'] = 0;
            $res['msg'] = '非法操作';
            return $response->withJson($res);
        }

        $id         = $request->getParam('id');
        $delluser   = User::find($id);

        if ($delluser->ref_by !== $user->id) {
            $res['ret'] = 0;
            $res['msg'] = '您无权操作该用户';
            return $response->withJson($res);
        }

        $delluser_config = $delluser->config;
        if ($delluser_config['form_agent_create'] !== true) {
            $res['ret'] = 0;
            $res['msg'] = '您无权操作通过邀请链接或邀请码注册的用户';
            return $response->withJson($res);
        }

        if (!$delluser->kill_user()) {
            $res['ret'] = 0;
            $res['msg'] = '删除失败';
            return $response->withJson($res);
        }

        $res['ret'] = 1;
        $res['msg'] = '删除成功';
        return $response->withJson($res);
    }
    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function purchaseSalesAgent($request, $response, $args)
    {
        $user = $this->user;
        if ($user == null || !$user->isLogin) {
            $res['ret'] = 0;
            $res['msg'] = '非法操作';
            return $response->withJson($res);
        }
        $price = Setting::obtain('purchase_sales_agent_price');
        if ($user->money < $price) {
            $res['ret'] = 0;
            $res['msg'] = '账户余额不足';
            return $response->withJson($res);
        }
        $user->money = bcsub($user->money, $price, 2);
        $user->agent = 1;
        if (!$user->save()) {
            $res['ret'] = 0;
            $res['msg'] = '出现错误, 请联系客服';
            return $response->withJson($res); 
        }
        $res['ret'] = 1;
        $res['msg'] = '恭喜, 您现在是销售代理啦!';
        return $response->withJson($res);
    }
}
