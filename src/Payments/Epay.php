<?php

declare(strict_types=1);

namespace App\Payments;

use Slim\Http\ServerRequest;
use Slim\Http\Response;
use App\Payments\Epay\EpayNotify;
use App\Payments\Epay\EpaySubmit;

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
            "pid"          => trim($this->config['epay_pid']),
            "out_trade_no" => $order['order_no'],
            "notify_url"   => $order['notify_url'],
            "return_url"   => $order['return_url'],
            "name"         => "Purchase",
              //'type'  => $this->config['epay_type'],
              //"clientip"  =>  "192.168.1.100",
            "money" => $order['total_amount']
        ];
        $paySubmit = new EpaySubmit([
            'apiurl'        => $this->config['epay_url'],
            'key'           => $this->config['epay_key'],
            'partner'       => $this->config['epay_pid'],
            'sign_type'     => strtoupper('MD5'),
            'input_charset' => strtolower('utf-8'),
            'transport'     => 'https',
        ]);
        $payData = $paySubmit->buildRequestForm($data);
          /*
        if ($payData['code'] != 1) {
            return [
                'ret' => 0,
                'msg' => $payData['msg']
            ];
        }*/
        $result = [
            'url'     => $payData,
            'ret'     => 1,
            'type'    => 'url'
        ];
        return $result;
    }

    public function notify(ServerRequest $request)
    {
        $alipayNotify = new EpayNotify([
            'apiurl'        => $this->config['epay_url'],
            'key'           => $this->config['epay_key'],
            'partner'       => $this->config['epay_pid'],
            'sign_type'     => strtoupper('MD5'),
            'input_charset' => strtolower('utf-8'),
            'transport'     => 'https',
        ]);
        $verify_result = $alipayNotify->verifyNotify();
        if ($verify_result) {
            $out_trade_no = $request->getParam('out_trade_no');
            $trade_status = $request->getParam('trade_status');
            if ($trade_status === 'TRADE_SUCCESS') {
                return [
                    'order_no' => $out_trade_no,
                ];
            }
        }
        return false;
    }
}