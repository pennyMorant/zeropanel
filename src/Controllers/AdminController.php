<?php

namespace App\Controllers;

use App\Models\{
    User,
    Order,
    TrafficLog
};
use App\Services\{
    Analytics
};
use Pkly\I18Next\I18n;
use Slim\Http\Response;
use Slim\Http\ServerRequest;
use Carbon\Carbon;

class AdminController extends UserController
{
    public function index(ServerRequest $request, Response $response, array $args)
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

    public function ajaxDataChart(ServerRequest $request, Response $response, array $args)
    {
        $name = $args['name'];
        switch ($name) {
            case 'newusers':
                // get current month 
                $start_date = Carbon::now()->subDays(6);
                $start_month_date = Carbon::parse($start_date)->startOfDay();
                // 获取当前日期
                $today = Carbon::today()->endOfDay();

                // 获取查询结果集合
                $users = User::whereBetween('signup_date', [$start_month_date, $today])->pluck('signup_date');

                // 对结果集合按照注册日期进行分组和统计
                $datas = $users->groupBy(function ($user) {
                    // 返回注册日期作为分组依据
                    return Carbon::parse($user)->toDateString();
                })->map(function ($group) {
                    // 返回每个分组的数量作为统计值
                    return $group->count();
                });

                // 填充缺失的日期和数量为 0 的数据并转换为 x y 数组格式
                $datas = collect(range(0, $today->diffInDays($start_month_date)))
                    ->mapWithKeys(function ($day) use ($start_month_date) {
                        // 返回从最早注册日期开始递增的每一天作为键，并获取对应的统计值或默认为 0 
                        return [$start_month_date->copy()->addDays($day)->toDateString() => 0];
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
                $start_date = Carbon::now()->subDays(6)->startOfDay();
                // 获取当前日期
                $today = Carbon::today()->endOfDay();

                // 获取查询结果集合
                $orders = Order::whereBetween('paid_at', [strtotime($start_date), strtotime($today)])
                    ->selectRaw('DATE(FROM_UNIXTIME(paid_at)) as date, SUM(order_total) as amount')
                    ->groupBy('date')->get();

                if (isset($orders)) {
                    // 对结果集合按照日期进行分组和统计
                    $datas = $orders->mapWithKeys(function ($item) {
                        // 返回每个分组的日期和金额作为键值对
                        return [$item->date => $item->amount];
                    });

                    // 填充缺失的日期和金额为 0 的数据并转换为 x y 数组格式
                    $datas = collect(range(0, $today->diffInDays($start_date)))
                        ->mapWithKeys(function ($day) use ($start_date) {
                            // 返回从最早付款日期开始递增的每一天作为键，并获取对应的金额或默认为 0 
                            return [$start_date->copy()->addDays($day)->format('Y-m-d') => 0];
                        })->merge($datas) // 合并两个集合，保留原有的金额
                        ->map(function ($value, $key) {
                            // 将每一天的数据转换为一个数组并返回
                            return [
                                'x' => strval($key),
                                'y' => $value,
                            ];
                        })->values(); // 返回一个索引数组
                }
                break;
            case 'traffic':
                // 获取7天内的起始和结束时间戳
                $start_date = Carbon::now()->subDays(6)->startOfDay();
                $today = Carbon::today()->endOfDay();
                // 查询7天内按日期分组的流量数据，并转换成GB              
                $traffic = TrafficLog::whereBetween('created_at', [strtotime($start_date), strtotime($today)])
                    ->selectRaw('DATE(FROM_UNIXTIME(created_at)) as x, ROUND(SUM((u) + (d)) / 1024 / 1024 / 1024, 2) as y')
                    ->groupBy('x')->get();
                if (isset($traffic)) {
                    // 对结果集合按照日期进行分组和统计
                    $datas = $traffic->mapWithKeys(function ($item) {
                        return [$item->x => $item->y];
                    });
                    $datas = collect(range(0, $today->diffInDays($start_date)))
                        ->mapWithKeys(function ($day) use ($start_date) {
                            return [$start_date->copy()->addDays($day)->format('Y-m-d') => 0];
                        })->merge($datas)
                        ->map(function ($value, $key) {
                            return [
                                'x' => strval($key),
                                'y' => $value,
                            ];
                        })->values(); // 返回一个索引数组
                }
                break;
            case 'user_traffic_ranking':
                $startOfDay = Carbon::today()->startOfDay()->timestamp;
                $endOfDay = Carbon::today()->endOfDay()->timestamp;

                $user = TrafficLog::selectRaw('user_id, COALESCE(SUM(u+d), 0) as total')
                    ->whereBetween('created_at', [$startOfDay, $endOfDay])
                    ->groupBy('user_id')
                    ->orderByDesc('total')
                    ->limit(10)
                    ->pluck('total', 'user_id');

                $datas = $user->map(function ($traffic, $user_id) {
                    return [
                        'y' => number_format($traffic / (1024 * 1024 * 1024), 2),
                        'x' => "ID: $user_id",
                    ];
                })->values();
                break;
        }
        return $response->withJson($datas);
    }
}
