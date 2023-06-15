<?php

namespace App\Models;

class Commission extends Model
{
    protected $connection = 'default';
    protected $table      = 'commission';
    
    public static function rebate($user_id, $order_amount, $order_no)
    {
        $configs      = Setting::getClass('invite');
        $user         = User::where('id', $user_id)->first();
        $gift_user_id = $user->ref_by;

        // 判断
        $invite_rebate_mode = $configs['invite_rebate_mode'];
        $rebate_ratio       = $configs['rebate_ratio'] / 100;
        if ($invite_rebate_mode == 'continued') {
            // 不设限制
            self::executeRebate($user_id, $gift_user_id, $order_amount, $order_no);
        } elseif ($invite_rebate_mode == 'limit_frequency') {
            // 限制返利次数
            $rebate_frequency = self::where('userid', $user_id)->count();
            if ($rebate_frequency < $configs['rebate_frequency_limit']) {
                self::executeRebate($user_id, $gift_user_id, $order_amount, $order_no);
            }
        } elseif ($invite_rebate_mode == 'limit_amount') {
            // 限制返利金额
            $total_rebate_amount = self::where('userid', $user_id)->sum('ref_get');
            // 预计返利 (expected_rebate) 是指：订单金额 * 返点比例
            $expected_rebate = $order_amount * $rebate_ratio;
            // 调整返利 (adjust_rebate) 是指：若历史返利总额在加上此次预计返利金额超过总返利限制，总返利限制与历史返利总额的差值
            if ($total_rebate_amount + $expected_rebate > $configs['rebate_amount_limit']
                && $total_rebate_amount <= $configs['rebate_amount_limit']
                ) {
                $adjust_rebate = $configs['rebate_amount_limit'] - $total_rebate_amount;
                if ($adjust_rebate > 0) {
                    self::executeRebate($user_id, $gift_user_id, $order_amount, $order_no, $adjust_rebate);
                }
            } else {
                self::executeRebate($user_id, $gift_user_id, $order_amount, $order_no);
            }
        } elseif ($invite_rebate_mode == 'limit_time_range') {
            if (strtotime($user->signup_date) + $configs['rebate_time_range_limit'] * 86400 > time()) {
                self::executeRebate($user_id, $gift_user_id, $order_amount, $order_no);
            }
        }
    }

    public static function executeRebate($user_id, $gift_user_id, $order_amount, $order_no, $adjust_rebate = null)
    {
        $gift_user     = User::where('id', $gift_user_id)->first();
        $rebate_amount = $order_amount * (Setting::obtain('rebate_ratio') / 100);
            // 返利
        $gift_user->commission += $adjust_rebate ?? $rebate_amount;
        $gift_user->save();
            // 记录
        $commission                = new Commission();
        $commission->order_amount  = $order_amount;
        $commission->userid        = $user_id;
        $commission->invite_userid = $gift_user_id;
        $commission->order_no      = $order_no;
        $commission->get_amount    = $adjust_rebate ?? $rebate_amount;
        $commission->created_at    = time();
        $commission->save(); 
    }
}
