<?php
/**
 * Created by Bob.
 * User: Bobs9
 */

namespace App\Services\Gateway;

use App\Models\Order;
use App\Models\Setting;
use App\Services\Auth;
use App\Services\Config;
use App\Services\View;
use Exception;

class THeadPay extends AbstractPayment
{
    protected $config;

    public function __construct()
    {
        $configs = Setting::getClass('theadpay');
        $this->config = [
            'theadpay_url'      => $configs['theadpay_url'],
            'theadpay_mchid'    => $configs['theadpay_mchid'],
            'theadpay_key'      => $configs['theadpay_key'],
        ];
    }

    public function pay($order)
    {
        $params = [
            'mchid' => $this->config['theadpay_mchid'],
            'out_trade_no' => $order['trade_no'],
            'total_fee' => (string)$order['total_fee'],
            'notify_url' => $order['notify_url'],
        ];
        $params['sign'] = $this->sign($params);
        $data = json_encode($params);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->config['theadpay_url']);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        $data = curl_exec($curl);
        curl_close($curl);

        $result = json_decode($data, true);
        if (!is_array($result) || !isset($result["status"])) {
            throw new \Exception('未知错误');
        }
        if ($result["status"] !== "success") {
            throw new \Exception($result["message"]);
        }

        return [
            'type' => 0, // QRCode
            'data' => $result["code_url"],
        ];
    }

    public function verify($params)
    {
        return $params['sign'] === $this->sign($params);
    }

    protected function sign($params)
    {
        unset($params['sign']);
        ksort($params);
        reset($params);
        $data = http_build_query($params) . "&key=" . $this->config['theadpay_key'];
        return strtoupper(md5($data));
    }

    public function ZeroPay($type, $price, $buyshop, $order_id = 0)
    {
        if ($order_id == 0 && $price <= 0) {
            return ['errcode' => -1, 'errmsg' => '非法的金额'];
        }
        $user = Auth::getUser();
        if ($order_id == 0) {
            $pl = new Order();
            $pl->userid = $user->id;
            $pl->total = $price;
            if ($buyshop['id'] != 0) $pl->shop = json_encode($buyshop);
            $pl->datetime = time();
            $pl->tradeno = self::generateGuid();
            $pl->save();
        } else {
            $pl = Order::find($order_id);
            if ($pl->status === 1){
                return ['errcode' => -1, 'errmsg' => "该订单已交易完成"];
            }
        }
        $params = $this->pay([
            'trade_no' => $pl->tradeno,
            'total_fee' => $pl->total * 100,
            'notify_url' => rtrim(Setting::obtain('website_url'), '/') . '/payment/notify/theadpay',
        ]);;
        $result['pid'] = $pl->tradeno;

        return ['errcode' => 0, 'url' => $params['data'], 'pid' => $pl->tradeno, 'type' => 'qrcode'];
    }

    public function notify(ServerRequest $request, Response $response, $args)
    {
        $inputString = file_get_contents('php://input', 'r');
        $inputStripped = str_replace(array("\r", "\n", "\t", "\v"), '', $inputString);
        $params = json_decode($inputStripped, true); //convert JSON into array

        if ($this->verify($params)) {
            $pid = $params['out_trade_no'];
            $this->postPayment($pid, 'THeadPay 平头哥支付 ' . $pid);
            die('success'); //The response should be 'success' only
        }

        die('fail');
    }


    public function getStatus(ServerRequest $request, Response $response, $args)
    {
        $p = Order::where('tradeno', $_POST['pid'])->first();
        return $response->withJson([
            'ret' => 1,
            'result' => $p->status,
        ]);
    }
}
