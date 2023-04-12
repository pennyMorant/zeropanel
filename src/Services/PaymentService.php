<?php

namespace App\Services;

use App\Controllers\OrderController;
use App\Models\Order;
use App\Models\User;
use App\Models\Setting;
use App\Models\Payment;
use App\Utils\Telegram;
use Slim\Http\Response;
use Slim\Http\ServerRequest;

class PaymentService
{
    public $method;
    protected $class;
    protected $config;
    protected $payment;

    public function __construct($method, $id,)
    {
        $this->method = $method;
        $this->class = '\\App\\Payments\\' . $this->method;
        if ($id) $payment = Payment::find($id)->toArray();
        $this->config = [];
        if (isset($payment)) {
            $this->config = json_decode($payment['config'], true);
            $this->config['enable'] = $payment['enable'];
            $this->config['id'] = $payment['id'];
            $this->config['notify_domain'] = $payment['notify_domain'];
        };
        
        $this->payment = new $this->class($this->config);
    }

    public function notify(ServerRequest $request, Response $response, array $args)
    {
        return  $this->payment->notify($request, $response, $args);
    }

    public function return(ServerRequest $request, Response $response, array $args)
    {
        $order_no = $_GET['tradeno'];
        $order = Order::where('order_no', $order_no)->first();
        if ($order->order_status == 2) {
            return $response->withStatus(302)->withHeader('Location', '/user/order/'.$order->order_no);
        }
    }

    public function toPay($order)
    {
        $notify_url = Setting::obtain('website_url') . "/payment/notify/" . $this->method;
        if ($this->config['notify_domain']) {
            $notify_url = $this->config['notify_domain'] . "/payment/notify/" . $this->method;
        }

        return $this->payment->pay([
            'notify_url'    =>  $notify_url,
            'return_url'    =>  Setting::obtain('website_url') . "/payment/return?tradeno=" . $order['order_no'],
            'order_no'      =>  $order['order_no'],
            'total_amount'  =>  $order['total_amount'],
            'user_id'       =>  $order['user_id']
        ]);
        
    }

    public static function executeAction($order_no)
    {
        OrderController::execute($order_no);

        $order = Order::where('order_no', $order_no)->first();
        $user = User::find($order->user_id);
        if (Setting::obtain('enable_push_top_up_message') == true) {
            $messageText = '交易提醒' . PHP_EOL .
                            '------------------------------' . PHP_EOL .
                            '用户：' . $user->email . PHP_EOL .
                            '金额：' . $order->order_total . PHP_EOL .
                            '订单：' . $order->order_no;
            Telegram::pushToAdmin($messageText);
        }
    }
}