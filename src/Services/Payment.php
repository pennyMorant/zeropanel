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
        return  $instance->notify($request, $response, $args);
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