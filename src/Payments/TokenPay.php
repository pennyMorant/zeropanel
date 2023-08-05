<?php

namespace App\Payments;;

use Slim\Http\ServerRequest;
use Slim\Http\Response;
use Curl\Curl;

class TokenPay
{
    protected $config = [];

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function pay($order)
    {
        $params = [
			"ActualAmount" => $order['total_amount'],
			"OutOrderId" => $order['order_no'], 
			"OrderUserKey" => strval($order['user_id']), 
			"Currency" => $this->config['tokenpay_currency'],
			'RedirectUrl' => $order['return_url'],
			'NotifyUrl' => $order['notify_url'],
        ];
        ksort($params);
        reset($params);
        $str = stripslashes(urldecode(http_build_query($params))) . $this->config['tokenpay_private_key'];
        $params['Signature'] = md5($str);

        $curl = new Curl();
        $curl->setUserAgent('TokenPay');
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, 0);
        $curl->setOpt(CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        $curl->post($this->config['tokenpay_url'] . '/CreateOrder', json_encode($params));
        $result = $curl->response;
        $curl->close();

        $paymentURL = $result->data;
        if (!$result->success) {        
            return [
                'ret' => 0,
                'msg' => $result->message
            ];
        }
        return [
            'ret'   => 1,
            'type' => 'url',
            'url' => $paymentURL
        ];
    }

    public function notify(ServerRequest $request)
    {
        $params = $request->getParsedBody();
        $sign = $params['Signature'];
        unset($params['Signature']);
        ksort($params);
        reset($params);
        $str = stripslashes(urldecode(http_build_query($params))) . $this->config['tokenpay_private_key'];
        if ($sign !== md5($str)) {
            die('cannot pass verification');
        }
        $status = $params['Status'];
        // 0: Pending 1: Paid 2: Expired
        if ($status != 1) {
            die('failed');
        }
        return [
            'order_no' => $params['OutOrderId'],
            'custom_result' => 'ok'
        ];
    }
}