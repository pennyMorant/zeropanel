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
use App\Services\Gateway\Epay\EpayNotify;
use App\Services\Gateway\Epay\EpaySubmit;
use App\Services\View;

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
        $data = array(
            "pid" => trim($this->epay['partner']),
            "type" => $method == 'alipay' ? 'Alipay' : 'Wechat',
            "out_trade_no" => $order_no,
            "notify_url" => Setting::obtain('website_general_url') . "/payment/notify/epay",
            "return_url" => Setting::obtain('website_general_url') . "/user/payment/return?tradeno=" . $order_no,
            "name" => Setting::obtain('website_general_url') . "充值" . $amount,
            "money" => $final_amount,
            "sitename" => Setting::obtain('website_general_url')
        );
        $alipaySubmit = new EpaySubmit($this->epay);
        $html_text = $alipaySubmit->buildRequestForm($data);
        $result = array('code'=>$html_text,'ret'=>1,'tradeno' => $order_no, 'type'=> 'url' );
        return $result;
    }

    public function notify($request, $response, $args)
    {
        $alipayNotify = new EpayNotify($this->epay);
        $verify_result = $alipayNotify->verifyNotify();
        if ($verify_result) {
            $out_trade_no = $_GET['out_trade_no'];
            $type = $_GET['type'];
            switch ($type) {
                case 'alipay':
                    $type = 'Alipay';
                    break;
                case 'qqpay':
                    $type = 'QQ';
                    break;
                case 'wxpay':
                    $type = 'Wechat';
                    // no break
                case 'epusdt':
                    $type = 'Epusdt';
                    break;
            }
            $trade_status = $_GET['trade_status'];
            if ($trade_status === 'TRADE_SUCCESS') {
                orderController::execute($request->getParam('payId'));
                return $response->withJson(['state' => 'success', 'msg' => '支付成功']);
            }
            return $response->withJson(['state' => 'fail', 'msg' => '支付失败']);
        }
        return $response->write('非法请求');
    }
}