<?php
namespace App\Services\Gateway;

use App\Models\Setting;
use App\Services\Payment;
use Slim\Http\ServerRequest;
use Slim\Http\Response;

class PayTaro
{

    private $appSecret;
    private $gatewayUri;

    /**
     * 签名初始化
     * @param merKey    签名密钥
     */

    public function __construct()
    {
        $configs = Setting::getClass('paytaro');
        $this->appSecret = $configs['paytaro_app_secret'];
        $this->gatewayUri = 'https://api.paytaro.com/v1/gateway/fetch';
    }


    /**
     * @name    准备签名/验签字符串
     */
    public function prepareSign($data)
    {
        ksort($data);
        return http_build_query($data);
    }

    /**
     * @name    生成签名
     * @param sourceData
     * @return    签名数据
     */
    public function sign($data)
    {
        return strtolower(md5($data . $this->appSecret));
    }

    /*
     * @name    验证签名
     * @param   signData 签名数据
     * @param   sourceData 原数据
     * @return
     */
    public function verify($data, $signature)
    {
    	unset($data['sign']);
        $mySign = $this->sign($this->prepareSign($data));
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
        $configs = Setting::getClass('paytaro');
        $currency = Setting::getClass('currency');

        $data['app_id'] = $configs['paytaro_app_id'];
        $data['out_trade_no'] = $order_no;
        
        if ($currency['enable_currency'] == true && !is_null($currency['currency_exchange_rate'])) {
            $data['total_amount'] = (int)($amount * 100 * $currency['currency_exchange_rate']);
        } else {
            $data['total_amount'] = (int)($amount * 100);
        }
        $data['notify_url'] = Setting::obtain('website_url') . '/payment/notify/paytaro';
        $data['return_url'] = Setting::obtain('website_url') . '/payment/return?tradeno='.$order_no;
        $params = $this->prepareSign($data);
        $data['sign'] = $this->sign($params);
    	$result = json_decode($this->post($data), true);
    	if (!isset($result['data']['pay_url'])) {
    		return ['ret' => 0, 'msg' => '支付网关处理失败'];
    	}
        return ['url' => $result['data']['pay_url'], 'ret' => 0, 'type' => 'url'];
    }


    public function notify(ServerRequest $request, Response $response, $args)
    {
    	//file_put_contents(BASE_PATH . '/storage/paytaro.log', json_encode($request->getParams())."\r\n", FILE_APPEND);
    	if (!$this->verify($request->getParams(), $request->getParam('sign'))) {
    		die('FAIL');
    	}
    	Payment::excuteAction($request->getParam('out_trade_no'));
    	die('SUCCESS');
    }

}