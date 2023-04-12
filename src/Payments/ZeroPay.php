<?php

namespace App\Payments;

use App\Models\{
    Order,
    Setting
};
use Omnipay\Omnipay;
use Slim\Http\Response;
use Slim\Http\ServerRequest;

class ZeroPay
{
    public function purchase($user_id, $method, $order_no, $amount)
    {        
        if ($method == 'alipay') {
            $payment = Setting::obtain('alipay_payment');
            switch ($payment) {
                case ('paytaro'):
                    $paytaro = new PayTaro();
                    $result = $paytaro->ZeroPay($user_id, $method, $order_no, $amount);
                    return $result;
                case ('paybeaver'):
                    $paybeaver = new PayBeaver();
                    $result = $paybeaver->ZeroPay($user_id, $method, $order_no, $amount);
                    return $result;
                case ('f2fpay'):
                    $f2fpay = new AopF2F();
                    $result = $f2fpay->ZeroPay($user_id, $method, $order_no, $amount);
                    return $result;
                case ('stripe'):
                    $stripe = new StripePay();
                    $result = $stripe->ZeroPay($user_id, $method, $order_no, $amount);
                    break;
                case ('theadpay'):
                    $theadpay = new THeadPay();
                    $result = $theadpay->ZeroPay($user_id, $method, $order_no, $amount);
                    return $result;
                case ('vmqpay'):
                    $vmq = new VmqPay();
                    $result = $vmq->ZeroPay($user_id, $method, $order_no, $amount);
                    return $result;
                case ('epay'):
                    $epay = new Epay();
                    $result = $epay->ZeroPay($user_id, $method, $order_no, $amount);
                    return $result;
                default:
                    return 0;
            }

        } else if ($method == 'wechatpay') {
            $payment = Setting::obtain('wechatpay_payment');
            switch ($payment) {
                case ('paytaro'):
                    $paytaro = new PayTaro();
                    $result = $paytaro->ZeroPay($user_id, $method, $order_no, $amount);                   
                    return $result;
                case ('paybeaver'):
                    $paybeaver = new PayBeaver();
                    $result = $paybeaver->ZeroPay($user_id, $method, $order_no, $amount);
                    return $result;
                case ('f2fpay'):
                    $f2fpay = new AopF2F();
                    $result = $f2fpay->ZeroPay($user_id, $method, $order_no, $amount);
                    return $result;
                case ('stripe'):
                    $stripe = new StripePay();
                    $result = $stripe->ZeroPay($user_id, $method, $order_no, $amount);
                    break;
                case ('mgate'):
                    $mgate = new MGate();
                    $result = $mgate->ZeroPay($user_id, $method, $order_no, $amount);
                    return $result;
                case ('theadpay'):
                    $theadpay = new THeadPay();
                    $result = $theadpay->ZeroPay($user_id, $method, $order_no, $amount);
                    return $result;
                case ('vmqpay'):
                    $vmq = new VmqPay();
                    $result = $vmq->ZeroPay($user_id, $method, $order_no, $amount);
                    return $result;
                case ('epay'):
                    $epay = new Epay();
                    $result = $epay->ZeroPay($user_id, $method, $order_no, $amount);
                    return $result;
                default:
                    return 0;
            }
        } else if ($method == 'cryptopay') {
            # 数字货币
            $payment = Setting::obtain('cryptopay_payment');
            switch ($payment) {
                case ('paytaro'):
                    $paytaro = new PayTaro();
                    $result = $paytaro->ZeroPay($user_id, $method, $order_no, $amount);                 
                    return $result;
                case ('tronapipay'):
                    $tronapipay = new TronapiPay();
                    $result = $tronapipay->ZeroPay($user_id, $method, $order_no, $amount);
                    return $result;
                default: 
                    return 0;
            }
        } else {
            return json_encode("错误的支付方式");
        }
    }

    public function notify(ServerRequest $request, Response $response, array $args)
    {
        $path = $request->getUri()->getPath();
        file_put_contents(BASE_PATH . '/storage/pay.log', json_encode(file_get_contents("php://input")) . "\r\n", FILE_APPEND);
        $path_exploded = explode('/', $path);
        $payment = $path_exploded[3];

        switch ($payment) {
            case ('vmqpay'):
                $vmqpay = new VmqPay();
                $vmqpay->notify($request, $response, $args);
                return;
            case ('paytaro'):
                $paytaro = new PayTaro();
                $paytaro->notify($request, $response, $args);
                return;
            case ('paybeaver'):
                $paybeaver = new PayBeaver();
                $paybeaver->notify($request, $response, $args);
                return;
            case ('f2fpay'):
                $gateway = Omnipay::create('Alipay_AopF2F');
                $gateway->setSignType('RSA2'); //RSA/RSA2
                $gateway->setAppId($_ENV['f2fpay_app_id']);
                $gateway->setPrivateKey($_ENV['merchant_private_key']); // 可以是路径，也可以是密钥内容
                $gateway->setAlipayPublicKey($_ENV['alipay_public_key']); // 可以是路径，也可以是密钥内容
                $notifyUrl = $_ENV['f2fNotifyUrl'] ?? (Setting::obtain('website_url') . '/payment/notify/f2fpay');
                $gateway->setNotifyUrl($notifyUrl);

                $aliRequest = $gateway->completePurchase();
                $aliRequest->setParams($_POST);

                try {
                    /** @var \Omnipay\Alipay\Responses\AopCompletePurchaseResponse $response */
                    $aliResponse = $aliRequest->send();
                    $pid = $aliResponse->data('out_trade_no');
                    if ($aliResponse->isPaid()) {
                        $this->postPayment($pid, '支付宝' . $pid);
                        die('success'); //The response should be 'success' only
                    }
                } catch (Exception $e) {
                    die('fail');
                }
                return;
            case ('payjs'):
                $payjs = new PAYJS($_ENV['payjs_key']);
                $payjs->notify($request, $response, $args);
                return;
            case ('stripe'):
                \Stripe\Stripe::setApiKey($_ENV['stripe_key']);
                $endpoint_secret = $_ENV['stripe_webhook'];
                $payload = @file_get_contents('php://input');
                $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
                $event = null;
                try {
                    $event = \Stripe\Webhook::constructEvent(
                        $payload,
                        $sig_header,
                        $endpoint_secret
                    );
                } catch (\UnexpectedValueException $e) {
                    http_response_code(400);
                    exit();
                } catch (\Stripe\Error\SignatureVerification $e) {
                    http_response_code(400);
                    exit();
                }
                switch ($event->type) {
                    case 'source.chargeable':
                        $source = $event->data->object;
                        $charge = \Stripe\Charge::create([
                            'amount' => $source['amount'],
                            'currency' => $source['currency'],
                            'source' => $source['id'],
                        ]);
                        if ($charge['status'] == 'succeeded') {
                            $type = null;
                            if ($source['type'] == 'alipay') {
                                $type = '支付宝';
                            } else if ($source['type'] == 'wechat') {
                                $type = '微信支付';
                            }
                            $order = Order::where('tradeno', '=', $source['id'])->first();
                            if ($order->status !== 1) {
                                $this->postPayment($source['id'], 'Stripe ' . $type);
                                echo 'SUCCESS';
                            } else {
                                echo 'ERROR';
                            }
                        }
                        break;
                    default:
                        http_response_code(400);
                        exit();
                }
                return http_response_code(200);
            case ('theadpay'):
                $theadpay = new THeadPay();
                $theadpay->notify($request, $response, $args);
                return;
            case ('epay'):
                $epay = new Epay();
                $epay->notify($request, $response, $args);
                return;
            case ('tronapipay'):
                $tronapipay = new TronapiPay();
                $tronapipay->notify($request, $response, $args);
                return;
            default:
                return 'failed';
        }
    }

    public function getReturnHTML (ServerRequest $request, Response $response, array $args)
    {
        $order_no = $_GET['tradeno'];
        $order = Order::where('order_no', $order_no)->first();
        if ($order->order_status == 2) {
            return $response->withStatus(302)->withHeader('Location', '/user/order/'.$order->order_no);
        }
    }

}
