<?php

declare(strict_types=1);

/**
 * Copyright (c) 2019.
 * Author:Alone88
 * Github:https://github.com/anhao
 */

namespace App\Services\Gateway;

use App\Controllers\OrderController;
use App\Models\Setting;
use Slim\Http\ServerRequest;
use Slim\Http\Response;
use App\Services\Gateway\Epay\EpayNotify;
use App\Services\Gateway\Epay\EpaySubmit;

class Epay
{
    protected $epay = [];

    public function __construct()
    {
        $this->epay['apiurl'] = Setting::obtain('epay_url');//易支付API地址
        $this->epay['partner'] = Setting::obtain('epay_pid');//易支付商户pid
        $this->epay['key'] = Setting::obtain('epay_key');//易支付商户Key
        $this->epay['sign_type'] = strtoupper('MD5'); //签名方式
        $this->epay['input_charset'] = strtolower('utf-8');//字符编码
        $this->epay['transport'] = 'https';//协议 http 或者https
    }

    public function ZeroPay($user_id, $method, $order_no, $amount)
    {
        $currency = Setting::getClass('currency');

        if ($currency['enable_currency'] == true && $currency['currency_exchange_rate'] != null) {
            $final_amount = $amount * $currency['currency_exchange_rate'];
        } else {
            $final_amount = $amount;
        }

        //请求参数
        $data = [
            "pid" => trim($this->epay['partner']),
            "type" => $method,
            "out_trade_no" => $order_no,
            "notify_url" => Setting::obtain('website_url') . "/payment/notify/epay",
            "return_url" => Setting::obtain('website_url') . "/payment/return?tradeno=" . $order_no,
            "name" => "充值",
            "money" => $final_amount,
            "sitename" => "test"
        ];
        $alipaySubmit = new EpaySubmit($this->epay);
        $html_text = $alipaySubmit->buildRequestForm($data);
        $result = ['url'=>$html_text, 'ret'=>1, 'tradeno' => $order_no, 'type'=> 'url'];
        return $result;
    }

    public function notify(ServerRequest $request, Response $response, $args)
    {
        $alipayNotify = new EpayNotify($this->epay);
        $verify_result = $alipayNotify->verifyNotify();
        if ($verify_result) {
            $out_trade_no = $_GET['out_trade_no'];
            $trade_status = $_GET['trade_status'];
            if ($trade_status === 'TRADE_SUCCESS') {
                orderController::execute($out_trade_no);
                die('success');
            }
        }
        die('error');
    }
}