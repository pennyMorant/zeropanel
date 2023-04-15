<?php


namespace App\Payments;;

use App\Services\Payment;
use App\Models\Setting;
use App\Services\PaymentService;
use Slim\Http\ServerRequest;
use Slim\Http\Response;

class VmqPay
{
    private $config = [];

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function sign($payId, $type, $price)
    {
        $vmq_param = $this->config['vmq_key'];
        $vmq_secret = $this->config['vmq_secret'];

        return md5($payId . $vmq_param . $type . $price . $vmq_secret);
    }

    public function pay($order)
    {
        $url_header = $this->config['vmq_url'] . '/createOrder?';
        $sign       = $this->sign($order_no, $type, $final_amount);
        $data       = [
            'payId'     => $order_no,
            'type'      => $type,
            'price'     => $final_amount,
            'sign'      => $sign,
            'param'     => $configs['vmq_key'],
            'isHtml'    => '1',
            'notifyUrl' => Setting::obtain('website_url') . '/payment/notify/vmqpay',
            'returnUrl' => Setting::obtain('website_url') . '/payment/return?tradeno=' . $order_no,
        ];
        $url = $url_header . http_build_query($data);
        return ['ret' => 1, 'url' => $url, 'type' => 'url'];
    }

    public function notify(ServerRequest $request, Response $response, array $args)
    {
        ini_set("error_reporting", "E_ALL & ~E_NOTICE");
        $key         = Setting::obtain('vmq_key');         //通讯密钥
        $payId       = $request->getParam('payId');        //商户订单号
        $param       = $request->getParam('param');        //创建订单的时候传入的参数
        $type        = $request->getParam('type');         //支付方式 ：微信支付为1 支付宝支付为2
        $price       = $request->getParam('price');        //订单金额
        $reallyPrice = $request->getParam('reallyPrice');  //实际支付金额
        $sign        = $request->getParam('sign');         //校验签名，计算方式 = md5(payId + param + type + price + reallyPrice + 通讯密钥)
                                                           //开始校验签名
        $_sign = md5($payId . $param . $type . $price . $reallyPrice . $key);
        if ($_sign != $sign) {
            die('error_sign');  //sign校验不通过
        }
        PaymentService::executeAction($request->getParam('payId'));
    	die('success');
    }

}