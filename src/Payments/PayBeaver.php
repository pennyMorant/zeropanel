<?php

namespace App\Payments;;

use App\Models\Setting;
use App\Services\Payment;
use Slim\Http\ServerRequest;
use Slim\Http\Response;

class PayBeaver
{   
    protected $appSecret;
    protected $gatewayUri;

    public function __construct() {
        $configs = Setting::getClass('paybeaver');
        $this->appSecret = $configs['paybeaver_app_secret'];
        $this->gatewayUri = 'https://api.paybeaver.com/v1/gateway/fetch';
    }

    private function buildQuery($data)
    {
        ksort($data);
        return http_build_query($data);
    }

    private function sign($data)
    {
        return strtolower(md5($data . $this->appSecret));
    }

    private function verify($data, $signature)
    {
    	unset($data['sign']);
        $mySign = $this->sign($this->buildQuery($data));
        return $mySign === $signature;
    }

    public function post($data)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->gatewayUri);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        $data = curl_exec($curl);
        curl_close($curl);
        return $data;
    }


    public function ZeroPay($user_id, $method, $order_no, $amount)
    {
        $configs = Setting::getClass('paybeaver');
        $currency = Setting::getClass('currency');
        
        $data['app_id'] = $configs['paybeaver_app_id'];
        $data['out_trade_no'] = $order_no;
        
        if ($currency['enable_currency'] == true && !is_null($currency['currency_exchange_rate'])) {
            $data['total_amount'] = (int)($amount * 100 * $currency['currency_exchange_rate']);
        } else {
            $data['total_amount'] = (int)($amount * 100);
        }
        $data['notify_url'] = Setting::obtain('website_url') . '/payment/notify/paybeaver';
        $data['return_url'] = Setting::obtain('website_url') . '/payment/return?tradeno=' . $order_no;
        $params = $this->buildQuery($data);
        $data['sign'] = $this->sign($params);
    	$result = json_decode($this->post($data), true);
    	if (!isset($result['data']['pay_url'])) {
    		return [
                'ret' => 0, 
                'msg' => '支付网关处理失败'
            ];
    	}
        return [
            'url' => $result['data']['pay_url'], 
            'ret' => 1, 
            'type' => 'url'
        ];
    }


    public function notify(ServerRequest $request, Response $response, array $args)
    {
    	//file_put_contents(BASE_PATH . '/storage/paybeaver.log', json_encode($request->getParams())."\r\n", FILE_APPEND);
    	if (!$this->verify($request->getParams(), $request->getParam('sign'))) {
    		die('FAIL');
    	}
        Payment::executeAction($request->getParam('out_trade_no'));
    	die('SUCCESS');
    }

}
