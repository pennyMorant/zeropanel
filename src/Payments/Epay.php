<?php

declare(strict_types=1);

/**
 * Copyright (c) 2019.
 * Author:Alone88
 * Github:https://github.com/anhao
 */

namespace App\Payments;;

use Slim\Http\ServerRequest;
use Slim\Http\Response;
use App\Payments\Epay\EpayNotify;
use App\Payments\Epay\EpaySubmit;
use App\Services\PaymentService;

class Epay
{
    protected $config = [];

    public function __construct($config)
    {
        $this->config = $config;

    }

    public function pay($order)
    {

        //请求参数
        $data = [
            "pid" => trim($this->config['epay_pid']),
            "out_trade_no" => $order['order_no'],
            "notify_url" => $order['notify_url'],
            "return_url" => $order['return_url'],
            "name" => "Purchase",
            'type'  => $this->config['epay_type'],
            "clientip"  =>  "192.168.1.100",
            "money" => $order['total_amount']
        ];
        $paySubmit = new EpaySubmit([
            'apiurl'  =>  $this->config['epay_url'],
            'key'  =>  $this->config['epay_key'],
            'partner'   =>  $this->config['epay_pid'],
            'sign_type' =>  strtoupper('MD5'),
            'input_charset' => strtolower('utf-8'),
            'transport' => 'https',
        ]);
        $payData = $paySubmit->buildRequestPost($data);
        if ($payData['code'] != 1) {
            return [
                'ret'   =>  0,
                'msg'   =>  $payData['msg']
            ];
        }
        $result = [
            'url'       =>  $payData['payurl'], 
            'ret'       =>  1, 
            'tradeno'   =>  $order['order_no'], 
            'type'      =>  'url'
        ];
        return $result;
    }

    public function notify(ServerRequest $request, Response $response, array $args)
    {
        $alipayNotify = new EpayNotify([
            'apiurl'  =>  $this->config['epay_url'],
            'key'  =>  $this->config['epay_key'],
            'partner'   =>  $this->config['epay_pid'],
            'sign_type' =>  strtoupper('MD5'),
            'input_charset' => strtolower('utf-8'),
            'transport' => 'https',
        ]);
        $verify_result = $alipayNotify->verifyNotify();
        if ($verify_result) {
            $out_trade_no = $_GET['out_trade_no'];
            $trade_status = $_GET['trade_status'];
            if ($trade_status === 'TRADE_SUCCESS') {
                PaymentService::executeAction($out_trade_no);
                die('success');
            }
        }
        die('error');
    }
}