<?php

namespace App\Services;

use App\Models\User;
use App\Models\Order;

class Analytics
{
    // admin
    public function getIncome($start_time, $end_time)
    {
        $sum = Order::selectRaw('SUM(order_total) as sum')->where('order_status', 2)->where('paid_at', '>=', $start_time)->where('paid_at', '<=', $end_time)->first()->sum;
        if (is_null($sum)) {
          $sum = 0;
        }
        return $sum;
    }
    // admin
    public function getNewUsers($start_time, $end_time)
    {
        $users = User::where('signup_date', '>=', $start_time)
        ->where('signup_date', '<', $end_time)
        ->count();
        if (is_null($users)) {
            $users = 0;
        }
        return $users;
    }

    public function increase_percentage($num_1, $num_2)
    {
        if ($num_1 > $num_2 && $num_2 != 0) {
            $percent = round((($num_1 - $num_2) / $num_2) * 100, 2);
        } else if($num_2 == 0) {
            $percent = $num_1 * 100;
        } else {
            $percent = 0;
        }
        return $percent;
    }
}
