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
use Slim\Http\Response;
use Slim\Http\ServerRequest;
use Exception;
use Ramsey\Uuid\Uuid;

class UserController extends AdminController
{
    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function index(ServerRequest $request, Response $response, $args)
    {
        $table_config['total_column'] = [
            
            'id'                    => 'ID',
            'email'                 => '邮箱',
            'money'                 => '金钱',
            'class'                 => '等级',
            'class_expire'          => '等级过期时间',
            'enable_traffic'        => '总流量',
            'enable'                => '启用',
            'is_admin'              => '管理员',
            'action'                    => '操作',
        ];

        $products = Product::where('status', 1)->orderBy('name')->get();
        $this->view()
            ->assign('products', $products)
            ->assign('table_config', $table_config)
            ->display('admin/user/user.tpl');
        return $response;
    }
    
    /**
     * 后台生成新用户
     * 
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function createNewUser(ServerRequest $request, Response $response, $args)
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
        $user->transfer_enable      = Tools::toGB($configs['signup_default_traffic']);
        $user->money                = ($money != -1 ? $money : 0);
        $user->class_expire         = date('Y-m-d H:i:s', time() + $configs['signup_default_class_time'] * 86400);
        $user->class                = $configs['signup_default_class'];
        $user->node_iplimit       = $configs['signup_default_ip_limit'];
        $user->node_speedlimit      = $configs['signup_default_speed_limit'];
        $user->signup_date             = date('Y-m-d H:i:s');
        $user->signup_ip               = $_SERVER['REMOTE_ADDR'];
        $user->theme                = $_ENV['theme'];
        $user->node_group           = 0;
        
        if ($user->save()) {
            $res['ret']         = 1;
            $res['msg']         = '新用户注册成功 用户名: ' . $email . ' 随机初始密码: ' . $pass;
            $res['email_error'] = 'success';
            $subject            = Setting::obtain('website_name') . '-新用户注册通知';
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
    public function updateUserIndex(ServerRequest $request, Response $response, $args)
    {
        $id = $args['id'];
        $user = User::find($id);
        $this->view()->assign('user', $user)->display('admin/user/update.tpl');
        return $response;
    }
    
    /**
     * 后台编辑用户
     * 
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function updateUser(ServerRequest $request, Response $response, $args): Response
    {
        $id = $request->getParam('id');
        $user = User::find($id);

        $email1 = $user->email;

        $user->email = $request->getParam('email');

        $email2 = $request->getParam('email');

        if ($request->getParam('password') != '') {
            $user->password = Hash::passwordHash($request->getParam('password'));
            $user->clean_link();
        }


        $user->transfer_enable  = Tools::toGB($request->getParam('transfer_enable'));
        $user->node_speedlimit  = $request->getParam('node_speedlimit');
        $user->node_iplimit   = $request->getParam('node_iplimit');
        $user->node_group       = $request->getParam('group');
        $user->remark           = $request->getParam('remark');
        $user->money            = $request->getParam('money');
        $user->class            = $request->getParam('class');
        $user->class_expire     = $request->getParam('class_expire');
        $user->commission       = $request->getParam('commission');

        // 手动封禁
        $ban_time = (int) $request->getParam('ban_time');
        if ($ban_time > 0) {
            $user->enable                       = 0;
            $end_time                           = date('Y-m-d H:i:s');
            $user->last_detect_ban_time         = $end_time;
            $DetectBanLog                       = new DetectBanLog();
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
    public function deleteUser(ServerRequest $request, Response $response, $args): Response
    {
        $id = $request->getParam('id');
        $user = User::find($id);
        $user->deleteUser();
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
    public function ajax(ServerRequest $request, Response $response, $args): Response
    {
        $configs = Setting::getClass('invite');
        $query = User::getTableDataFromAdmin(
            $request,
            static function (&$order_field) {
                if ($order_field == 'email') {
                    $order_field = 'id';
                } elseif ($order_field == 'action') {
                    $order_field = 'id';
                }
            },
        );
              
        $data = $query['datas']->map(function($rowData) {
            $type = "'user'";
            return [
                'id'    =>  $rowData->id,
                'email' =>  $rowData->email,
                'money' =>  $rowData->money,
                'class' =>  $rowData->class,
                'class_expire'  =>  $rowData->class_expire,
                'enable_traffic'    =>  Tools::flowToGB($rowData->transfer_enable).'GB',
                'is_admin'  =>  $rowData->is_admin(),
                'enable'    =>  $rowData->enable(),
                'action'    =>  '<div class="btn-group dropstart"><a class="btn btn-light-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false">操作</a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="/admin/user/update/'.$rowData->id.'">编辑</a></li>
                                        <li><a class="dropdown-item" type="button" onclick="zeroAdminDelete('.$type.', '.$rowData->id.')">删除</a></li>
                                    </ul>
                                </div>',
            ];
        })->toArray();
        
        return $response->withJson([
            'draw'            => $request->getParam('draw'),
            'recordsTotal'    => User::count(),
            'recordsFiltered' => $query['count'],
            'data'            => $data,
        ]);
    }

    public function updateUserStatus(ServerRequest $request, Response $response, $args): Response
    {
        $type = $args['type'];
        $id = $request->getParam('id');
        $enable = $request->getParam('enable');
        $is_admin = $request->getParam('is_admin');
        $user = User::find($id);
        switch ($type) {
            case 'enable': 
                $user->enable = $enable;
                break;
            case 'is_admin':
                $user->is_admin = $is_admin;
                break;  
        }
        $user->save();
        return $response->withJson([
            'ret'   => 1,
            'msg'   => 'success',
        ]);
    }
}
