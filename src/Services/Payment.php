<?php

namespace App\Services;

use App\Services\Gateway\{
    ZeroPay
};

class Payment
{
    public static function notify($request, $response, $args)
    {
        $instance = new ZeroPay();
        $instance->notify($request, $response, $args);
        return ['errcode' => 1, 'errmsg' => '回调处理完成'];
    }

    public static function return($request, $response, $args)
    {
        $instance = new ZeroPay();
        return $instance->getReturnHTML($request, $response, $args);
    }

    public static function toPay($user_id, $method, $order_no, $amount)
    {
       $instance = new ZeroPay();
       return $instance->purchase($user_id, $method, $order_no, $amount);
    }
}