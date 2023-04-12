<?php
namespace App\Payments;;

use App\Models\Setting;
use App\Services\PaymentService;
use Slim\Http\ServerRequest;
use Slim\Http\Response;

class PayTaro
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

    public function pay($order)
    {
        $data = [
            'app_id'    =>  $this->config['mgate_id'],
            'out_trade_no'  =>  $this->config['order_no'],
            'notify_url'    =>  $this->config['notify_url'],
            'return_url'    =>  $this->config['return_url'],
            'total_amount'   =>  $this->config['total_amount']
        ];
        $params = $this->prepareSign($data);
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
            'ret' => 0, 
            'type' => 'url'
        ];
    }


    public function notify(ServerRequest $request, Response $response, array $args)
    {
    	//file_put_contents(BASE_PATH . '/storage/paytaro.log', json_encode($request->getParams())."\r\n", FILE_APPEND);
    	if (!$this->verify($request->getParams(), $request->getParam('sign'))) {
    		die('FAIL');
    	}
    	PaymentService::executeAction($request->getParam('out_trade_no'));
    	die('SUCCESS');
    }

}