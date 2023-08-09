<?php
namespace App\Payments;;

use Slim\Http\ServerRequest;

class Mgate
{
    private $config = [];

    /**
     * 签名初始化
     * @param merKey    签名密钥
     */

    public function __construct($config)
    {
        $this->config = $config;
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
        return strtolower(md5($data . $this->config['mgate_secret']));
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
        curl_setopt($curl, CURLOPT_URL, $this->config['mgate_url']  . '/v1/gateway/fetch');
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_POST, false);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        $data = curl_exec($curl);
        curl_close($curl);
        return $data;
    }

    public function pay($order)
    {
        $data = [
            'app_id'       => $this->config['mgate_id'],
            'out_trade_no' => $order['order_no'],
            'notify_url'   => $order['notify_url'],
            'return_url'   => $order['return_url'],
            'total_amount' => $order['total_amount']
        ];
        $params       = $this->prepareSign($data);
        $data['sign'] = $this->sign($params);
        $result       = json_decode($this->post($data), true);
    	if (!isset($result['data']['pay_url'])) {
    		return [
                'ret' => 0,
                'msg' => '支付网关处理失败'
            ];
    	}
        return [
            'url'  => $result['data']['pay_url'],
            'ret'  => 1,
            'type' => 'url'
        ];
    }


    public function notify(ServerRequest $request)
    {
    	if (!$this->verify($request->getParams(), $request->getParam('sign'))) {
    		return false;
    	}
    	return [
            'order_no' => $request->getParam('out_trade_no'),
        ];
    }

}