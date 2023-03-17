<?php

namespace App\Controllers;

use App\Models\{
    User,
    Coupon,
    Order,
    Node,
    Payback,
    TrafficLog
};
use App\Utils\{
    Tools,
    DatatablesHelper
};
use App\Services\{
    Analytics
};
use Pkly\I18Next\I18n;
use Countable;
use Ozdemir\Datatables\Datatables;
use Slim\Http\{
    Request,
    Response
};
use Carbon\Carbon;
use DB;


/**
 *  Admin Controller
 */
class AdminController extends UserController
{
    /**
     * 后台首页
     *
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function index($request, $response, $args)
    {
        $sts = new Analytics();
        $start_time_this_month = mktime(0,0,0,date('m'),1,date('Y'));
        $end_time_this_month = mktime(23,59,59,date('m'),date('t'),date('Y'));
        $start_time_last_month = mktime(0, 0, 0, date("m") - 1, 1, date("Y"));
        $end_time_last_month = mktime(23, 59, 59, date("m"), 0, date("Y"));
        $income_this_month = $sts->getIncome($start_time_this_month, $end_time_this_month);
        $new_users_this_month = $sts->getNewUsers(date("Y-m-d H:i:s", $start_time_this_month), date("Y-m-d H:i:s", $end_time_this_month));
        $increase_percentage_income = $sts->increase_percentage($income_this_month, $sts->getIncome($start_time_last_month, $end_time_last_month));
        $increase_percentage_new_users = $sts->increase_percentage($new_users_this_month, $sts->getNewUsers(date('Y-m-d H:i:s', $start_time_last_month), date('Y-m-d H:i:s', $end_time_last_month)));
        $this->view()
            ->assign('income_this_month', $income_this_month)
            ->assign('new_users_this_month', $new_users_this_month)
            ->assign('increase_percentage_income', $increase_percentage_income)
            ->assign('increase_percentage_new_users', $increase_percentage_new_users)
            ->display('admin/index.tpl');
        return $response;
    }

    /**
     * 节点列表
     *
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function node($request, $response, $args)
    {
        $this->view()->assign('nodes', Node::all())->display('admin/node.tpl');
        return $response;
    }
 
    /**
     * 统计信息
     *
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function sys($request, $response, $args)
    {
        $this->view()->display('admin/index.tpl');
        return $response;
    }

    /**
     * 后台邀请返利页面
     *
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function invite($request, $response, $args)
    {
        $table_config['total_column'] = array(
            'id'              => 'ID',
            'total'           => '原始金额',
            'userid'   => '发起用户ID',
            'ref_by'     => '获利用户ID',
            'ref_get'         => '获利金额',
            'datetime'        => '时间'
        );
        $table_config['default_show_column'] = array();
        $table_config['default_show_column'] = array_keys($table_config['total_column']);
        $table_config['ajax_url'] = 'payback/ajax';
        $this->view()->assign('table_config', $table_config)->display('admin/invite.tpl');
        return $response;
    }

    /**
     * 后台邀请返利页面 AJAX
     *
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function ajaxPayBack($request, $response, $args)
    {
        $query = Payback::getTableDataFromAdmin(
            $request
        );
        $data = [];
        foreach ($query['datas'] as $value) {
            $tempdata                   = [];
            $tempdata['id']             = $value->id;
            $tempdata['total']          = $value->total;
            $tempdata['userid']         = $value->userid;
            $tempdata['ref_by']         = $value->ref_by;
            $tempdata['ref_get']        = $value->ref_get;
            $tempdata['datetime']       = date('Y-m-d H:i:s', $value->datetime);
            $data[] = $tempdata;
        }
        return $response->WithJson([
            'draw'              => $request->getParam('draw'),
            'recordsTotal'      => Payback::count(),
            'recordsFiltered'   => $query['count'],
            'data'              => $data
        ]);
        return $response;
    }

    /**
     * 后台商品优惠码页面
     *
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function coupon($request, $response, $args)
    {
        $table_config['total_column'] = array(
            'id'        => 'ID',
            'code'      => '优惠码',
            'expire'    => '过期时间',
            'limited_product'      => '限定商品ID',
            'discount'    => '额度',
            'per_use_count'   => '每个用户次数',
            'total_use_count' => '优惠码总使用次数'
        );
        $table_config['default_show_column'] = array();
        foreach ($table_config['total_column'] as $column => $value) {
            $table_config['default_show_column'][] = $column;
        }
        $table_config['ajax_url'] = 'coupon/ajax';
        $this->view()->assign('table_config', $table_config)->display('admin/coupon.tpl');
        return $response;
    }
    
    /**
     * 后台商品优惠码页面 AJAX
     *
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function ajaxCoupon($request, $response, $args)
    {
        $query = Coupon::getTableDataFromAdmin(
            $request
        );
        $data = [];
        foreach ($query['datas'] as $value) {
            $tempdata['id'] = $value->id;
            $tempdata['code'] = $value->code;
            $tempdata['expire'] = date('Y-m-d H:i:s', $value->expire);
            $tempdata['limited_product'] = $value->limited_product == '' ? '无限制' : $value->limited_product;
            $tempdata['discount'] = $value->discount;
            $tempdata['per_use_count'] = $value->per_use_count == '-1' ? '无限次使用' : $value->per_use_count;
            $tempdata['total_use_count'] = $value->total_use_count == '-1' ? '无限次使用' : $value->total_use_count;

            $data[] = $tempdata;
        }
        return $response->WithJson([
            'draw'              => $request->getParam('draw'),
            'recordsTotal'      => Coupon::count(),
            'recordsFiltered'   => $query['count'],
            'data'              => $data
        ]);
        return $response;
    }

       /**
     * 添加优惠码
     *
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function addCoupon($request, $response, $args)
    {
        $generate_type = (int) $request->getParam('generate_type');
        $final_code    = $request->getParam('code');
        if (empty($final_code) && in_array($generate_type, [1, 3])) {
            return $response->withJson([
                'ret' => 0,
                'msg' => '优惠码不能为空'
            ]);
        }

        if ($generate_type == 1) {
            if (Coupon::where('code', $final_code)->count() != 0) {
                 return $response->withJson([
                    'ret' => 0,
                    'msg' => '优惠码已存在'
                ]);
            }
        } else {
            while (true) {
                $code_str = Tools::genRandomChar(8);
                if ($generate_type == 3) {
                    $final_code .= $code_str;
                } else {
                    $final_code  = $code_str;
                }

                if (Coupon::where('code', $final_code)->count() == 0) {
                    break;
                }
            }
        }
        $code          = new Coupon();
        $code->per_use_count = $request->getParam('per_use_count');
        $code->total_use_count = $request->getParam('total_use_count');
        $code->code    = $final_code;
        $code->expire  = time() + $request->getParam('expire') * 3600;
        $code->limited_product    = $request->getParam('limited_product');
        $code->discount  = $request->getParam('discount');

        $code->save();

         return $response->withJson([
            'ret' => 1,
            'msg' => '优惠码添加成功'
        ]);
    }
    

    public function ajaxDataChart($request, $response, $args)
    {
        $name = $args['name'];
        switch ($name) {
            case 'newusers':
                // 获取最早注册用户的日期
                $earliest_signup_date = User::orderBy('signup_date')->first()->signup_date ?? Carbon::now()->subDays(7);
                $earliest_signup_date = Carbon::parse($earliest_signup_date);
                // 获取当前日期
                $today = Carbon::today();

                // 获取查询结果集合
                $users = User::whereBetween('signup_date', [$earliest_signup_date, $today])->pluck('signup_date');

                // 对结果集合按照注册日期进行分组和统计
                $datas = $users->groupBy(function ($user) {
                    // 返回注册日期作为分组依据
                    return $user->toDateString();
                })->map(function ($group) {
                    // 返回每个分组的数量作为统计值
                    return $group->count();
                });

                // 填充缺失的日期和数量为 0 的数据并转换为 x y 数组格式
                $datas = collect(range(0, $today->diffInDays($earliest_signup_date)))
                    ->mapWithKeys(function ($day) use ($earliest_signup_date) {
                        // 返回从最早注册日期开始递增的每一天作为键，并获取对应的统计值或默认为 0 
                        return [$earliest_signup_date->copy()->addDays($day)->toDateString() => 0];
                    })->merge($datas) // 合并两个集合，保留原有的统计值
                    ->map(function ($value, $key) {
                        // 将每一天的数据转换为一个数组并返回
                        return [
                            'x' => strval($key),
                            'y' => intval($value)
                        ];
                    })->values(); // 返回一个索引数组
                break;
            case 'income':
                // 获取最早付款日期
                $earliest_paid_date = Order::where('order_status', 2)
                    ->where('order_payment', '!=', 'creditpay')->orderBy('paid_time')->first()->paid_time ?: Carbon::now()->subDays(7);
                $earliest_paid_date = Carbon::createFromTimestamp($earliest_paid_date)->startOfDay();

                // 获取当前日期
                $today = Carbon::today();

                // 获取查询结果集合
                $orders = Order::where('order_payment', '!=', 'creditpay')
                    ->whereBetween('paid_time', [strtotime($earliest_paid_date), strtotime($today)])
                    ->selectRaw('DATE_FORMAT(FROM_UNIXTIME(paid_time), "%Y-%m-%d") as date, sum(order_total) as amount')
                    ->groupBy('date')->get();

                if (isset($orders)) {
                    // 对结果集合按照日期进行分组和统计
                    $datas = $orders->mapWithKeys(function ($item) {
                        // 返回每个分组的日期和金额作为键值对
                        return [$item->date => $item->amount];
                    });

                    // 填充缺失的日期和金额为 0 的数据并转换为 x y 数组格式
                    $datas = collect(range(0, $today->diffInDays($earliest_paid_date)))
                        ->mapWithKeys(function ($day) use ($earliest_paid_date) {
                            // 返回从最早付款日期开始递增的每一天作为键，并获取对应的金额或默认为 0 
                            return [$earliest_paid_date->copy()->addDays($day)->format('Y-m-d') => 0];
                        })->merge($datas) // 合并两个集合，保留原有的金额
                        ->map(function ($value, $key) {
                            // 将每一天的数据转换为一个数组并返回
                            return [
                                'x' => strval($key),
                                'y' => intval($value)
                            ];
                        })->values(); // 返回一个索引数组
                }
                break;
            case 'traffic':
                // 获取7天内的起始和结束时间戳
                $time_a = strtotime(date('Y-m-d', $_SERVER['REQUEST_TIME']) . " -6 days");
                $time_b = $time_a + 86400 * 7;
                // 查询7天内按日期分组的流量数据，并转换成GB
                $traffic = TrafficLog::whereBetween('datetime', [$time_a, $time_b])
                    ->groupByRaw('DATE(FROM_UNIXTIME(datetime))')
                    ->selectRaw('DATE(FROM_UNIXTIME(datetime)) as x, ROUND(SUM(u + d) / 1024 / 1024 / 1024, 2) as y')
                    ->pluck('y', 'x');
                // 把日期和流量数据添加到数组中，并补充没有流量数据的日期
                $datas = [];
                for ($i = 6; $i >= 0; $i--) {
                    // 获取当天的日期
                    $date = date('Y-m-d', $_SERVER['REQUEST_TIME'] - $i * 86400);
                    // 如果有流量数据，就取出来，否则设为0
                    $flow = isset($traffic[$date]) ? (string)$traffic[$date] : '0';
                    // 把日期和流量数据添加到数组中
                    $datas[] = [
                        'x' => $date,
                        'y' => $flow,
                        'name' => I18n::get()->t('traffic'),
                    ];
                }
                break;
            case 'user_traffic_ranking':
                $time_a = Carbon::today()->startOfDay()->timestamp;
                $time_b = Carbon::today()->endOfDay()->timestamp;
                $user = TrafficLog::selectRaw('user_id, SUM(u+d) as total')
                    ->whereBetween('datetime', [$time_a, $time_b])->groupBy('user_id')
                    ->limit(10)->orderByDesc('total')->pluck('total', 'user_id');
                $datas = [];
                foreach ($user as $user_id => $traffic) {
                    $traffic = $traffic < 107374 ? 0 : $traffic;
                    $datas[] = [
                        'y' => number_format($traffic / (1024 * 1024 * 1024), 2),
                        'x' => "用户ID:" . $user_id,
                    ];
                }
                break;
        }
        return $response->withJson($datas);
    }
}
