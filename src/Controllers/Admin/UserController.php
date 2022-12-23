<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminController;
use App\Models\{
    User,
    Product,
    Setting
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
            'remark'                => '备注',
            'email'                 => '邮箱',
            'money'                 => '金钱',
            'node_group'            => '群组',
            'class'                 => '等级',
            'class_expire'          => '等级过期时间',
            'passwd'                => '连接密码',
            'method'                => '加密方式',
            'online_ip_count'       => '在线IP数',
            'last_use_time'          => '上次使用时间',
            'used_traffic'          => '已用流量/GB',
            'enable_traffic'        => '总流量/GB',
            'today_traffic'         => '今日流量',
            'enable'                => '是否启用',
            'signup_date'              => '注册时间',
            'signup_ip'                => '注册IP',
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
        $user->password             = Hash::passwordHash($pass);
        $user->email                = $email;
        $user->passwd               = Tools::genRandomChar(16);
        $user->uuid                 = Uuid::uuid5(Uuid::NAMESPACE_DNS, $email . '|' . $current_timestamp);
        $user->t                    = 0;
        $user->u                    = 0;
        $user->d                    = 0;
        $user->method               = $configs['sign_up_for_method'];
        $user->transfer_enable      = Tools::toGB($configs['sign_up_for_free_traffic']);
        $user->money                = ($money != -1 ? $money : 0);
        $user->class_expire         = date('Y-m-d H:i:s', time() + $configs['sign_up_for_class_time'] * 86400);
        $user->class                = $configs['sign_up_for_class'];
        $user->node_connector       = $configs['connection_device_limit'];
        $user->node_speedlimit      = $configs['connection_rate_limit'];
        $user->signup_date             = date('Y-m-d H:i:s');
        $user->signup_ip               = $_SERVER['REMOTE_ADDR'];
        $user->theme                = $_ENV['theme'];
        $user->node_group           = 0;
        
        if ($user->save()) {
            $res['ret']         = 1;
            $res['msg']         = '新用户注册成功 用户名: ' . $email . ' 随机初始密码: ' . $pass;
            $res['email_error'] = 'success';
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
        $user->transfer_enable  = Tools::toGB($request->getParam('transfer_enable'));
        $user->method           = $request->getParam('method');
        $user->node_speedlimit  = $request->getParam('node_speedlimit');
        $user->node_connector   = $request->getParam('node_connector');
        $user->enable           = $request->getParam('enable');
        $user->is_admin         = $request->getParam('is_admin');
        $user->node_group       = $request->getParam('group');
        $user->ref_by           = $request->getParam('ref_by');
        $user->remark           = $request->getParam('remark');
        $user->money            = $request->getParam('money');
        $user->class            = $request->getParam('class');
        $user->class_expire     = $request->getParam('class_expire');
        $user->rebate           = $request->getParam('rebate');
        $user->agent            = $request->getParam('agent');
        $user->commission       = $request->getParam('commission');

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
            $tempdata['remark']                 = $value->remark;
            $tempdata['email']                  = $value->email;
            $tempdata['money']                  = $value->money;
            $tempdata['node_group']             = $value->node_group;
            $tempdata['class']                  = $value->class;
            $tempdata['class_expire']           = $value->class_expire;
            $tempdata['passwd']                 = $value->passwd;
            $tempdata['method']                 = $value->method;
            $tempdata['online_ip_count']        = $value->online_ip_count();
            $tempdata['last_use_time']          = $value->lastUseTime();
            $tempdata['used_traffic']           = Tools::flowToGB($value->u + $value->d);
            $tempdata['enable_traffic']         = Tools::flowToGB($value->transfer_enable);
            $tempdata['today_traffic']          = $value->TodayusedTraffic();
            $tempdata['enable']                 = $value->enable == 1 ? '可用' : '禁用';
            $tempdata['signup_date']               = $value->signup_date;
            $tempdata['signup_ip']                 = $value->signup_ip;
            $tempdata['ref_by']                 = $value->ref_by;
            $tempdata['ref_by_name']            = $value->ref_by_name();
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
