<?php


namespace App\Payments;;

use App\Controllers\OrderController;
use Slim\Http\ServerRequest;
use Slim\Http\Response;

class TronapiPay
{
    protected $config;
    protected $gatewayUrl;

    public function __construct($config)
    {
        $this->config = $config;
        $this->gatewayUrl = 'https://pro.tronapi.com/api/transaction/create';
    }

    /**
     * @name    生成签名
     * @param sourceData
     */
    public function sign($data)
    {
        return strtolower(md5($data));
    }

    public function post($data)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->gatewayUrl);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        $data = curl_exec($curl);
        curl_close($curl);
        return $data;
    }

    public function pay($order)
    {

        $amount = $order['total_amount'];
        $currency = 'CNY';
        $coin_code = 'USDT';
        $order_id = $order['order_no'];
        $customer_id = $order['user_id'];
        $product_name = '';
        $notify_url = $order['notify_url'];
        $redirect_url = $order['return_url'];
        $signatureStr = $amount.$currency.$coin_code.$order_id.$product_name.$customer_id.$notify_url.$redirect_url.$this->config['tronapipay_public_key'].$this->config['tronapipay_private_key'];
        $signature = $this->sign($signatureStr);

        $data = [
            'amount' => $amount,
            'currency' => $currency,
            'coin_code' => $coin_code,
            'order_id' => $order_id,
            'customer_id' => $customer_id,
            'product_name' => $product_name,
            'notify_url' => $notify_url,
            'redirect_url' => $redirect_url,
            'public_key' => $this->config['tronapipay_public_key'],
            'signature' => $signature,
        ];

        $result = json_decode($this->post($data), true);
        if (!isset($result['data']['cashier_url'])) {        
            return [
                'ret' => 0, '
                msg' => '支付网关处理失败'
            ];
        }
        return [
            'ret' => 1, 
            'url' => $result['data']['cashier_url'], 
            'type' => 'url'
        ];
    }

    public function notify(ServerRequest $request)
    {
    	$transaction_token = $request->getParam('transaction_token');
        $order_id = $request->getParam('order_id');
        $amount = $request->getParam('amount');
        $currency = $request->getParam('currency');
        $coin_code = $request->getParam('coin_code');
        $coin_amount = $request->getParam('coin_amount');
        $hash = $request->getParam('hash');
        $private_key = $this->config['tronapipay_private_key'];
        $signature = $request->getParam('signature');
        $_signatureStr = $transaction_token.$order_id.$amount.$currency.$coin_code.$coin_amount.$hash.$private_key;
        $_signature = $this->sign($_signatureStr);
        if ($_signature != $signature) {
            return false;
        }
    	
        return [
            'order_no'  => $order_id,
            'custom_result' =>  [
                'code'  =>  200,
                'data'  =>  'ok'
            ],
        ]; 
    }
}