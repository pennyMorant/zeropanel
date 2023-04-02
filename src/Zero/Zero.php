<?php

namespace App\Zero;

use App\Models\{
    Payback,
    Setting
};

class Zero
{
    /**
     * 返利
     * $form_user  邀请人
     * $to_user    被邀请人
     */
    public static function getCommission($form_user, $to_user, $price)
    {
        $commission_ratio = Setting::obtain('sales_agent_commission_ratio');
        if ($form_user->rebate > 0) {
            $ref_money = bcmul($price, ($form_user->rebate/100), 2);
        } else {
            $ref_money = bcmul($price, $commission_ratio, 2);
        }
        $form_user->commission += $ref_money;
        $form_user->save();

        $Payback = new Payback();
        $Payback->total = $price;
        $Payback->userid = $to_user->id;
        $Payback->ref_by = $form_user->id;
        $Payback->ref_get = $ref_money;
        $Payback->datetime = time();
        $Payback->save();  
    }
}
