<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminController;
use App\Models\{
    User,
    Product,
    Setting,
    DetectBanLog
};
use App\Utils\{
    Hash,
    Tools,
};
use Slim\Http\Response;
use Slim\Http\ServerRequest;
use Ramsey\Uuid\Uuid;

class UserController extends AdminController
{
    public function index(ServerRequest $request, Response $response, array $args)
    {
        $table_config['total_column'] = [
            
            'id'           => 'ID',
            'email'        => '邮箱',
            'money'        => '金钱',
            'class'        => '等级',
            'class_expire' => '等级过期时间',
            'traffic'      => '流量',
            'enable'       => '启用',
            'is_admin'     => '管理员',
            'action'       => '操作',
        ];
        $table_config['ajax_url'] = 'user/ajax';
        $products = Product::where('status', 1)->orderBy('name')->get();
        $this->view()
            ->assign('products', $products)
            ->assign('table_config', $table_config)
            ->display('admin/user/user.tpl');
        return $response;
    }
    
    public function createNewUser(ServerRequest $request, Response $response, array $args)
    {
        $email          = strtolower(trim($request->getParsedBodyParam('email')));
        $passwd         = $request->getParsedBodyParam('passwd') ?: $email;

        $user = User::where('email', $email)->first();
        if (!is_null($user)) {
            return $response->withJson([
                'ret' => 0,
                'msg' => '邮箱已经被注册了'
            ]);
        }

        if (!Tools::isEmail($email)) {
            return $response->withJson([
                'ret' => 0,
                'msg' => '邮箱不规范'
            ]);
        }
        
        $configs = Setting::getClass('register');
          // do reg user
        $user                     = new User();
        $current_timestamp        = time();
        $user->password           = Hash::passwordHash($passwd);
        $user->email              = $email;
        $user->passwd             = $user->createShadowsocksPasswd();
        $user->uuid               = Uuid::uuid5(Uuid::NAMESPACE_DNS, $email . '|' . $current_timestamp);
        $user->t                  = 0;
        $user->u                  = 0;
        $user->d                  = 0;
        $user->transfer_enable    = Tools::toGB($configs['signup_default_traffic']);
        $user->money              = 0;
        $user->class_expire       = date('Y-m-d H:i:s', time() + $configs['signup_default_class_time'] * 86400);
        $user->class              = $configs['signup_default_class'];
        $user->node_iplimit       = $configs['signup_default_ip_limit'];
        $user->node_speedlimit    = $configs['signup_default_speed_limit'];
        $user->signup_date        = date('Y-m-d H:i:s');
        $user->signup_ip          = $_SERVER['REMOTE_ADDR'];
        $user->theme              = $_ENV['theme'];
        $user->subscription_token = $user->createSubToken();
        $user->node_group         = 0;
        
        $user->save();
        return $response->withJson([
            'ret' => 1,
            'msg' => '创建成功'
        ]);
    }
    
    public function updateUserIndex(ServerRequest $request, Response $response, array $args)
    {
        $id = $args['id'];
        $user = User::find($id);
        $this->view()
            ->assign('user', $user)
            ->display('admin/user/update.tpl');
        return $response;
    }
    
    public function updateUser(ServerRequest $request, Response $response, array $args): Response
    {
        $putData = $request->getParsedBody();
        $id = $putData['id'];
        $user = User::find($id);
        $user->email = $putData['email'];

        if ($putData['password'] != '') {
            $user->password = Hash::passwordHash($putData['password']);
        }

        $user->transfer_enable = Tools::toGB($putData['transfer_enable']);
        $user->node_speedlimit = $putData['node_speedlimit'] ?: 0;
        $user->node_iplimit    = $putData['node_iplimit'] ?: 0;
        $user->node_group      = $putData['group'] ?: 0;
        $user->remark          = $putData['remark'] ?: NULL;
        $user->money           = $putData['money'] ?: 0;
        $user->class           = $putData['class'] ?: 0;
        $user->class_expire    = $putData['class_expire'];
        $user->commission      = $putData['commission'] ?: 0;

          // 手动封禁
        $ban_time = (int) $putData['ban_time'];
        if ($ban_time > 0) {
            $user->enable                    = 0;
            $end_time                        = date('Y-m-d H:i:s');
            $user->last_detect_ban_time      = $end_time;
            $DetectBanLog                    = new DetectBanLog();
            $DetectBanLog->user_id           = $user->id;
            $DetectBanLog->email             = $user->email;
            $DetectBanLog->detect_number     = '0';
            $DetectBanLog->ban_time          = $ban_time;
            $DetectBanLog->start_time        = strtotime('1989-06-04 00:05:00');
            $DetectBanLog->end_time          = strtotime($end_time);
            $DetectBanLog->all_detect_number = $user->all_detect_number;
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

    public function deleteUser(ServerRequest $request, Response $response, array $args): Response
    {
        $id = $request->getParsedBodyParam('id');
        $user = User::find($id);
        $user->deleteUser();
        return $response->withJson([
            'ret' => 1,
            'msg' => '删除成功'
        ]);
    }

    public function ajax(ServerRequest $request, Response $response, array $args): Response
    {
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
                'id'           => $rowData->id,
                'email'        => $rowData->email,
                'money'        => $rowData->money,
                'class'        => $rowData->class,
                'class_expire' => $rowData->class_expire,
                'traffic'      => $rowData->usedTraffic() . '/' . Tools::flowToGB($rowData->transfer_enable).'GB',
                'is_admin'     => $rowData->is_admin(),
                'enable'       => $rowData->enable(),
                'action'       => '<div class="btn-group dropstart"><a class="btn btn-light-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false">操作</a>
                                    <ul    class="dropdown-menu">
                                    <li><a class="dropdown-item" href="user/update/'.$rowData->id.'">编辑</a></li>
                                    <li><a class="dropdown-item" type="button" onclick="zeroAdminDelete('.$type.', '.$rowData->id.')">删除</a></li>
                                    <li><a class="dropdown-item" type="button" onclick="zeroModalAdminCreateOrderForUser(' . $rowData->id . ')">分配订单</a></li>
                                    </ul>
                                </div>',
            ];
        })->toArray();
        
        return $response->withJson([
            'draw'            => $request->getParsedBodyParam('draw'),
            'recordsTotal'    => User::count(),
            'recordsFiltered' => $query['count'],
            'data'            => $data,
        ]);
    }

    public function updateUserStatus(ServerRequest $request, Response $response, array $args): Response
    {
        $type     = $args['type'];
        $id       = $request->getParsedBodyParam('id');
        $enable   = $request->getParsedBodyParam('enable');
        $is_admin = $request->getParsedBodyParam('is_admin');
        $user     = User::find($id);
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
