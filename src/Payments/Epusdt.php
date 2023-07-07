<?php

namespace App\Payments;;

use Slim\Http\ServerRequest;
use Slim\Http\Response;

class Epusdt
{
    protected $config = [];

    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * @name    生成签名
     * @param sourceData
     */
    private function sign(array $parameter)
    {
        ksort($parameter);
        reset($parameter); //内部指针指向数组中的第一个元素
        $sign = '';
        $urls = '';
        foreach ($parameter as $key => $val) {
            if ($val == '') continue;
            if ($key != 'signature') {
                if ($sign != '') {
                    $sign .= "&";
                    $urls .= "&";
                }
                $sign .= "$key=$val"; //拼接为url参数形式
                $urls .= "$key=" . urlencode($val); //拼接为url参数形式
            }
        }
        $sign = md5($sign . $this->config['epusdt_private_key']);//密码追加进入开始MD5签名
        return $sign;
    }

    public function post($data)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->config['epusdt_url']. '/api/v1/order/create-transaction');
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $jsonData = json_encode($data);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
        $data = curl_exec($curl);
        curl_close($curl);
        return $data;
    }

    public function pay($order)
    {
        $data = [
            'amount'       => $order['total_amount'],
            'notify_url'   => $order['notify_url'],
            'order_id'     => $order['order_no'],
            'redirect_url' => $order['return_url'],
        ];
        $data['signature'] = $this->sign($data);
        
        $result = json_decode($this->post($data), true);
        if (!isset($result['data']['payment_url'])) {        
            return [
                'ret' => 0,
                'msg' => $result['message'] . $result['status_code']
            ];
        }
        return [
            'ret'  => 1,
            'url'  => $result['data']['payment_url'],
            'type' => 'url'
        ];
    }

    public function notify(ServerRequest $request)
    {
        $data = [
            'amount'       => $request->getParam('amount'),
            'order_id'     => $request->getParam('order_id'),
            //'notify_url'   => $notify_url,
            //'redirect_url' => $redirect_url,
        ];
        
        if ($request->getParam('status') === 2) {
            return [
                'order_no'      => $request->getParam('order_id'),
            ]; 
        }
    	
        return false;
    }
}
