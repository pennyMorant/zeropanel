<?php


namespace App\Services\Gateway;

use App\Services\Payment;
use App\Models\Setting;
use Slim\Http\ServerRequest;
use Slim\Http\Response;

class VmqPay
{
    /**
     * @name    生成签名
     * @param sourceData
     */
    public function sign($payId, $type, $price)
    {
        $configs = Setting::getClass('vmq');
        $vmq_param = $configs['vmq_key'];
        $vmq_secret = $configs['vmq_key'];

        return md5($payId . $vmq_param . $type . $price . $vmq_secret);
    }

    /**
     * @param $type
     * @param $price
     * @param $buyshop
     * @param int $order_id
     */
    public function ZeroPay($user_id, $method, $order_no, $amount)
    {
        $configs = Setting::getClass('vmq');
        $currency = Setting::getClass('currency');
        $type = $method === 'alipay' ? 2 : 1;
        $url_header = $configs['vmq_gateway'] . '/createOrder?';
        if ($currency['enable_currency'] == true && !is_null($currency['currency_exchange_rate'])) {
            $final_amount = $amount * $currency['currency_exchange_rate'];
        } else {
            $final_amount = $amount;
        }
        $sign = $this->sign($order_no, $type, $final_amount);
        $data = [
            'payId' => $order_no,
            'type' => $type,
            'price' => $final_amount,
            'sign' => $sign,
            'param' => $configs['vmq_key'],
            'isHtml' => '1',
            'notifyUrl' => Setting::obtain('website_url') . '/payment/notify/vmqpay',
            'returnUrl' => Setting::obtain('website_url') . '/payment/return?tradeno=' . $order_no,
        ];
        $url = $url_header . http_build_query($data);
        return ['ret' => 1, 'url' => $url, 'type' => 'url'];
    }

    public function notify(ServerRequest $request, Response $response, array $args)
    {
        ini_set("error_reporting", "E_ALL & ~E_NOTICE");
        $key = Setting::obtain('vmq_key');//通讯密钥
        $payId = $request->getParam('payId');//商户订单号
        $param = $request->getParam('param');//创建订单的时候传入的参数
        $type = $request->getParam('type');//支付方式 ：微信支付为1 支付宝支付为2
        $price = $request->getParam('price');//订单金额
        $reallyPrice = $request->getParam('reallyPrice');//实际支付金额
        $sign = $request->getParam('sign');//校验签名，计算方式 = md5(payId + param + type + price + reallyPrice + 通讯密钥)
        //开始校验签名
        $_sign = md5($payId . $param . $type . $price . $reallyPrice . $key);
        if ($_sign != $sign) {
            die('error_sign');//sign校验不通过
        }
        Payment::executeAction($request->getParam('payId'));
    	die('success');
    }

}