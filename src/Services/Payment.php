<?php

namespace App\Services;

use App\Services\Gateway\{
    ZeroPay
};
use Slim\Http\Response;
use Slim\Http\ServerRequest;

class Payment
{
    public static function notify(ServerRequest $request, Response $response, $args)
    {
        $instance = new ZeroPay();
        return  $instance->notify($request, $response, $args);
    }

    public static function return(ServerRequest $request, Response $response, $args)
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