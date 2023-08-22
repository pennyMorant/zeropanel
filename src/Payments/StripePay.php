<?php

namespace App\Payments;;

use App\Services\Auth;
use App\Models\Order;
use App\Models\Setting;
use Stripe\{ Stripe, Charge, Source };
use Slim\Http\ServerRequest;
use Slim\Http\Response;

class StripePay
{
    public function __construct()
    {
        $configs = Setting::getClass('stripe');
        Stripe::setApiKey($configs['stripe_sk']);
    }

    public function ZeroPay($type, $price, $shopinfo, $order_id=0)
    {
        $configs  = Setting::getClass('stripe');
        
        if ($configs['stripe_min_recharge'] > $price) {
            return [ 'ret' => 0, 'msg' => '金额需大于 ' . $configs['stripe_min_recharge'] . ' 元' ];
        }

        if ( isset($shopinfo['telegram']) ) {
            $user = $shopinfo['telegram']['user'];
        } else {
            $user = Auth::getUser();
        }

        $ch = curl_init();
        $url = 'https://api.exchangerate-api.com/v4/latest/'.strtoupper($configs['stripe_currency']);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $currency = json_decode(curl_exec($ch));
        curl_close($ch);

        $price_exchanged = bcdiv((double)$price, $currency->rates->CNY, 10);
        try {
            $source = Source::create([
                'amount'    => floor($price_exchanged * 100),
                'currency'  => $configs['stripe_currency'],
                'type'      => $type,
                'redirect'  => [
                'return_url' => Setting::obtain('website_url') . '/user/payment/return',
                ],
            ]);
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            return [ 'ret' => 0, 'msg' => $this->amount_verification($e->getMessage(), $currency->rates->CNY) ];
        }

        if ($order_id == 0) {
            $pl             = new Order();
            $pl->userid     = $user->id;
            $pl->total      = $price;
            $pl->datetime   = time();
            $pl->tradeno    = $source['id'];
            if ($shopinfo) {
                if ( isset($shopinfo['telegram']) ) {
                    unset($shopinfo['telegram']['user']);
                }
                $pl->shop   = json_encode($shopinfo);
            }
            $pl->save();
        } else {
            $pl = Order::find($order_id);
            if ($pl->status === 1){
                return ['ret' => 0, 'msg' => "该订单已交易完成"];
            }
        }

        if ($type == 'alipay') {
            return [ 'ret' => 1, 'url' => $source['redirect']['url'], 'tradeno' => $pl->tradeno, 'type' => 'url' ];
        } else {
            return [ 'ret' => 1, 'url' => $source['wechat']['qr_code_url'], 'tradeno' => $pl->tradeno, 'type' => 'qrcode' ];
        }
    }

    public function amount_verification($total, $cny)
    {
        $error_message = explode('$', $total, 2);
        $Limit_amount  = explode(' ', $error_message[1], 2);
        if ($error_message[0] === 'Amount must be at least ') {
            return '金额需大于 ' . round(bcmul($Limit_amount[0], $cny, 3), 2) . ' 元 ($' . $Limit_amount[0] . ' ' . strtoupper($Limit_amount[1]) . ')';
        }
        return $total;
    }


    public function getStatus(ServerRequest $request, Response $response, array $args)
    {
        $p = Order::where('id', $_POST['pid'])->first();
        $return['ret'] = 1;
        $return['result'] = $p->status;
        return json_encode($return);
    }
}
