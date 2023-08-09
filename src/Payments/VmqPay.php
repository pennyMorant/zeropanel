<?php

namespace App\Payments;;

use Slim\Http\ServerRequest;

class VmqPay
{
    private $config = [];

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function sign($payId, $type, $price)
    {
        $vmq_param = $this->config['vmqpay_key'];
        $vmq_secret = $this->config['vmqpay_key'];

        return md5($payId . $vmq_param . $type . $price . $vmq_secret);
    }

    public function pay($order)
    {
        $url_header = $this->config['vmqpay_url'] . '/createOrder?';
        $sign       = $this->sign($order['order_no'], $this->config['vmqpay_type'], $order['total_amount']);
        $data       = [
            'payId'     => $order['order_no'],
            'type'      => $this->config['vmqpay_type'],
            'price'     => $order['total_amount'],
            'sign'      => $sign,
            'param'     => $this->config['vmqpay_key'],
            'isHtml'    => '1',
            'notifyUrl' => $order['notify_url'],
            'returnUrl' => $order['return_url'],
        ];
        $url = $url_header . http_build_query($data);
        return ['ret' => 1, 'url' => $url, 'type' => 'url'];
    }

    public function notify(ServerRequest $request)
    {
        ini_set("error_reporting", "E_ALL & ~E_NOTICE");
        $key         = $this->config['vmqpay_key'];         //通讯密钥
        $payId       = $request->getParam('payId');        //商户订单号
        $param       = $request->getParam('param');        //创建订单的时候传入的参数
        $type        = $request->getParam('type');         //支付方式 ：微信支付为1 支付宝支付为2
        $price       = $request->getParam('price');        //订单金额
        $reallyPrice = $request->getParam('reallyPrice');  //实际支付金额
        $sign        = $request->getParam('sign');         //校验签名，计算方式 = md5(payId + param + type + price + reallyPrice + 通讯密钥)
                                                           //开始校验签名
        $_sign = md5($payId . $param . $type . $price . $reallyPrice . $key);
        if ($_sign != $sign) {
            return false;
        }
        return [
            'order_no' => $request->getParam('payId'),
        ];
    }

}