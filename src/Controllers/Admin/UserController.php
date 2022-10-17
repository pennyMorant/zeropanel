<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminController;
use App\Models\{
    User,
    Product,
    Setting,
    DetectBanLog
};
use App\Services\{
    Auth,
    Mail,
};
use App\Utils\{
    Hash,
    Tools,
    QQWry,
    Cookie
};
use Slim\Http\{
    Request,
    Response
};
use Exception;
use Ramsey\Uuid\Uuid;

class UserController extends AdminController
{
    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function index($request, $response, $args)
    {
        $table_config['total_column'] = array(
            'op'                    => '操作',
            'id'                    => 'ID',
            'name'             => '用户名',
            'remark'                => '备注',
            'email'                 => '邮箱',
            'money'                 => '金钱',
            'node_group'            => '群组',
            'expire_in'             => '账户过期时间',
            'class'                 => '等级',
            'class_expire'          => '等级过期时间',
            'passwd'                => '连接密码',
            'method'                => '加密方式',
            'protocol'              => '连接协议',
            'obfs'                  => '混淆方式',
            'obfs_param'            => '混淆参数',
            'online_ip_count'       => '在线IP数',
            'last_use_time'          => '上次使用时间',
            'used_traffic'          => '已用流量/GB',
            'enable_traffic'        => '总流量/GB',
            'last_checkin_time'     => '上次签到时间',
            'today_traffic'         => '今日流量',
            'enable'                => '是否启用',
            'reg_date'              => '注册时间',
            'reg_ip'                => '注册IP',
            'ref_by'                => '邀请人ID',
            'ref_by_name'      => '邀请人用户名',
            'top_up'                => '累计充值',
            'rebate'                => '销售代理返利百分比',
        );
        $table_config['default_show_column'] = array('op', 'id', 'name', 'remark', 'email');
        $table_config['ajax_url'] = 'user/ajax';
        $shops = Product::where('status', 1)->orderBy('name')->get();
        $this->view()
            ->assign('shops', $shops)
            ->assign('table_config', $table_config)
            ->display('admin/user/index.tpl');
        return $response;
    }
    
    /**
     * 后台生成新用户
     * 
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function createNewUser($request, $response, $args)
    {
        $email   = strtolower(trim($request->getParam('email')));
        $money   = (int) trim($request->getParam('balance'));
        $shop_id = (int) $request->getParam('product');

        $user = User::where('email', $email)->first();
        if ($user != null) {
            return $response->withJson([
                'ret' => 0,
                'msg' => '邮箱已经被注册了'
            ]);
        }

        if (! Tools::isEmail($email)) {
            return $response->withJson([
                'ret' => 0,
                'msg' => '邮箱不规范'
            ]);
        }
        
        $configs = Setting::getClass('register');
        // do reg user
        $pass                       = Tools::genRandomChar(16);
        $user                       = new User();
        $current_timestamp          = time();
        $user->password                 = Hash::passwordHash($pass);
        $user->name                 = $email;
        $user->email                = $email;
        $user->passwd               = Tools::genRandomChar(16);
        $user->uuid                 = Uuid::uuid5(Uuid::NAMESPACE_DNS, $email . '|' . $current_timestamp);
        $user->t                    = 0;
        $user->u                    = 0;
        $user->d                    = 0;
        $user->method               = $configs['sign_up_for_method'];
        $user->protocol             = $configs['sign_up_for_protocol'];
        $user->protocol_param       = $configs['sign_up_for_protocol_param'];
        $user->obfs                 = $configs['sign_up_for_obfs'];
        $user->obfs_param           = $configs['sign_up_for_obfs_param'];
        $user->transfer_enable      = Tools::toGB($configs['sign_up_for_free_traffic']);
        $user->money                = ($money != -1 ? $money : 0);
        $user->class_expire         = date('Y-m-d H:i:s', time() + $configs['sign_up_for_class_time'] * 86400);
        $user->class                = $configs['sign_up_for_class'];
        $user->node_connector       = $configs['connection_device_limit'];
        $user->node_speedlimit      = $configs['connection_rate_limit'];
        $user->expire_in            = date('Y-m-d H:i:s', time() + $configs['sign_up_for_free_time'] * 86400);
        $user->reg_date             = date('Y-m-d H:i:s');
        $user->reg_ip               = $_SERVER['REMOTE_ADDR'];
        $user->theme                = $_ENV['theme'];
        $user->node_group           = 0;
        
        if ($user->save()) {
            $res['ret']         = 1;
            $res['msg']         = '新用户注册成功 用户名: ' . $email . ' 随机初始密码: ' . $pass;
            $res['email_error'] = 'success';
            $user->addMoneyLog($user->money);
            $subject            = Setting::obtain('website_general_name') . '-新用户注册通知';
            $to                 = $user->email;
            $text               = '您好，管理员已经为您生成账户，用户名: ' . $email . '，登录密码为：' . $pass . '，感谢您的支持。 ';
            try {
                Mail::send($to, $subject, 'newuser.tpl', [
                    'user' => $user, 'text' => $text,
                ], []);
            } catch (Exception $e) {
                $res['email_error'] = $e->getMessage();
            }
            return $response->withJson($res);
        }
        return $response->withJson([
            'ret' => 0,
            'msg' => '未知错误'
        ]);
    }
    
    /**
     * 后台编辑用户按钮
     * 
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function edit($request, $response, $args)
    {
        $id = $args['id'];
        $user = User::find($id);
        $this->view()->assign('edit_user', $user)->display('admin/user/edit.tpl');
        return $response;
    }
    
    /**
     * 后台编辑用户
     * 
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function update($request, $response, $args)
    {
        $id = $args['id'];
        $user = User::find($id);

        $email1 = $user->email;

        $user->email = $request->getParam('email');

        $email2 = $request->getParam('email');

        $passwd = $request->getParam('passwd');

        if ($request->getParam('pass') != '') {
            $user->password = Hash::passwordHash($request->getParam('pass'));
            $user->clean_link();
        }


        $user->passwd           = $request->getParam('passwd');
        $user->protocol         = $request->getParam('protocol');
        $user->protocol_param   = $request->getParam('protocol_param');
        $user->obfs             = $request->getParam('obfs');
        $user->obfs_param       = $request->getParam('obfs_param');
        $user->transfer_enable  = Tools::toGB($request->getParam('transfer_enable'));
        $user->method           = $request->getParam('method');
        $user->node_speedlimit  = $request->getParam('node_speedlimit');
        $user->node_connector   = $request->getParam('node_connector');
        $user->enable           = $request->getParam('enable');
        $user->is_admin         = $request->getParam('is_admin');
        $user->node_group       = $request->getParam('group');
        $user->ref_by           = $request->getParam('ref_by');
        $user->remark           = $request->getParam('remark');
        $user->name             = $request->getParam('name');
        $user->money            = $request->getParam('money');
        $user->class            = $request->getParam('class');
        $user->class_expire     = $request->getParam('class_expire');
        $user->expire_in        = $request->getParam('expire_in');
        $user->rebate           = $request->getParam('rebate');
        $user->agent            = $request->getParam('agent');
        $user->commission       = $request->getParam('commission');

        // 手动封禁
        $ban_time = (int) $request->getParam('ban_time');
        if ($ban_time > 0) {
            $user->enable                       = 0;
            $end_time                           = date('Y-m-d H:i:s');
            $user->last_detect_ban_time         = $end_time;
            $DetectBanLog                       = new DetectBanLog();
            $DetectBanLog->name            = $user->name;
            $DetectBanLog->user_id              = $user->id;
            $DetectBanLog->email                = $user->email;
            $DetectBanLog->detect_number        = '0';
            $DetectBanLog->ban_time             = $ban_time;
            $DetectBanLog->start_time           = strtotime('1989-06-04 00:05:00');
            $DetectBanLog->end_time             = strtotime($end_time);
            $DetectBanLog->all_detect_number    = $user->all_detect_number;
            $DetectBanLog->save();
        }

        if (!$user->save()) {
            return $response->withJson([
                'ret' => 0,
                'msg' => '修改失败'
            ]);
        }
        return $response->withJson([
            'ret' => 1,
            'msg' => '修改成功'
        ]);
    }

    /**
     * 后台删除用户
     * 
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function delete($request, $response, $args)
    {
        $user = User::find((int) $request->getParam('id'));
        if (!$user->kill_user()) {
            return $response->withJson([
                'ret' => 0,
                'msg' => '删除失败'
            ]);
        }
        return $response->withJson([
            'ret' => 1,
            'msg' => '删除成功'
        ]);
    }
    
    /**
     * 后台切换用户
     * 
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function changetouser($request, $response, $args)
    {
        $userid     = $request->getParam('userid');
        $adminid    = $request->getParam('adminid');
        $user       = User::find($userid);
        $admin      = User::find($adminid);
        $expire_in  = time() + 60 * 60;

        if (!$admin->is_admin || !$user || !Auth::getUser()->isLogin) {
            return $response->withJson([
                'ret' => 0,
                'msg' => '非法请求'
            ]);
        }

        Cookie::set([
            'uid'           => $user->id,
            'email'         => $user->email,
            'key'           => Hash::cookieHash($user->password, $expire_in),
            'ip'            => md5($_SERVER['REMOTE_ADDR'] . Setting::obtain('website_security_token') . $user->id . $expire_in),
            'expire_in'     => $expire_in,
            'old_uid'       => Cookie::get('uid'),
            'old_email'     => Cookie::get('email'),
            'old_key'       => Cookie::get('key'),
            'old_ip'        => Cookie::get('ip'),
            'old_expire_in' => Cookie::get('expire_in'),
            'old_local'     => $request->getParam('local'),
        ], $expire_in);
        
        return $response->withJson([
            'ret' => 1,
            'msg' => '切换成功'
        ]);
    }
    
    /**
     * 后台用户AJAX
     * 
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function ajax($request, $response, $args)
    {
        $configs = Setting::getClass('invite');
        $query = User::getTableDataFromAdmin(
            $request,
            static function (&$order_field) {
                if ($order_field == 'used_traffic') {
                    $order_field = 'u + d';
                } elseif ($order_field == 'enable_traffic') {
                    $order_field = 'transfer_enable';
                } elseif ($order_field == 'today_traffic') {
                    $order_field = 'u + d - last_day_t';
                } elseif ($order_field == 'op') {
                    $order_field = 'id';
                }
            },
        );
        

        $data  = [];
        foreach ($query['datas'] as $value) {
            /** @var User $value */

            $tempdata['op']                     = '' .
                '<a class="btn btn-brand" href="/admin/user/' . $value->id . '/edit">编辑</a>' .
                '<a class="btn btn-brand-accent" id="delete" href="javascript:void(0);" onClick="delete_modal_show(\'' . $value->id . '\')">删除</a>';


            $tempdata['id']                     = $value->id;
            $tempdata['name']              = $value->name;
            $tempdata['remark']                 = $value->remark;
            $tempdata['email']                  = $value->email;
            $tempdata['money']                  = $value->money;
            $tempdata['node_group']             = $value->node_group;
            $tempdata['expire_in']              = $value->expire_in;
            $tempdata['class']                  = $value->class;
            $tempdata['class_expire']           = $value->class_expire;
            $tempdata['passwd']                 = $value->passwd;
            $tempdata['method']                 = $value->method;
            $tempdata['protocol']               = $value->protocol;
            $tempdata['obfs']                   = $value->obfs;
            $tempdata['obfs_param']             = $value->obfs_param;
            $tempdata['online_ip_count']        = $value->online_ip_count();
            $tempdata['last_use_time']          = $value->lastUseTime();
            $tempdata['used_traffic']           = Tools::flowToGB($value->u + $value->d);
            $tempdata['enable_traffic']         = Tools::flowToGB($value->transfer_enable);
            $tempdata['last_checkin_time']      = $value->lastCheckInTime();
            $tempdata['today_traffic']          = $value->TodayusedTraffic();
            $tempdata['enable']                 = $value->enable == 1 ? '可用' : '禁用';
            $tempdata['reg_date']               = $value->reg_date;
            $tempdata['reg_ip']                 = $value->reg_ip;
            $tempdata['ref_by']                 = $value->ref_by;
            $tempdata['ref_by_name']       = $value->ref_by_name();
            $tempdata['top_up']                 = $value->get_top_up();
            $tempdata['rebate']                 = $value->rebate > 0 ? $value->rebate . '%' : ($configs['rebate_ratio'] * 100) . '%';

            $data[] = $tempdata;
        }
        
        return $response->withJson([
            'draw'            => $request->getParam('draw'),
            'recordsTotal'    => User::count(),
            'recordsFiltered' => $query['count'],
            'data'            => $data,
        ]);
    }
}
