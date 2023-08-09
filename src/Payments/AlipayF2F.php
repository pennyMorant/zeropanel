<?php

namespace App\Payments;

use App\Payments\Alipay\AlipayF2F as Alipay;
use Slim\Http\ServerRequest;
use Slim\Http\Response;

class AlipayF2F {
    protected $config = [];

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function pay($order)
    {
        
        $gateway = new Alipay();
        $gateway->setMethod('alipay.trade.precreate');
        $gateway->setAppId($this->config['alipayf2f_app_id']);
        $gateway->setPrivateKey($this->config['alipayf2f_private_key']); // 可以是路径，也可以是密钥内容
        $gateway->setAlipayPublicKey($this->config['alipayf2f_public_key']); // 可以是路径，也可以是密钥内容
        $gateway->setNotifyUrl($order['notify_url']);
        $gateway->setBizContent([
            'subject' => 'purchase',
            'out_trade_no' => $order['order_no'],
            'total_amount' => $order['total_amount']
        ]);
        $gateway->send();
        return [
            'type' => 0, // 0:qrcode 1:url
            'data' => $gateway->getQrCodeUrl()
        ];
        
    }

    public function notify(ServerRequest $request)
    {
        if ($request->getParam('trade_status') !== 'TRADE_SUCCESS') return false;
        $gateway = new Alipay();
        $gateway->setAppId($this->config['app_id']);
        $gateway->setPrivateKey($this->config['private_key']); // 可以是路径，也可以是密钥内容
        $gateway->setAlipayPublicKey($this->config['public_key']); // 可以是路径，也可以是密钥内容
        if ($gateway->verify($request)) {
            return [
                'trade_no' => $request->getParam('out_trade_no'),
            ];
        } else {
            return false;
        }
    }
}
