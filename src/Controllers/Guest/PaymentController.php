<?php 
namespace App\Controllers\Guest;

use App\Controllers\OrderController;
use App\Services\PaymentService;
use App\Models\Order;
use App\Models\Setting;
use App\Utils\Telegram;
use Slim\Http\Response;
use Slim\Http\ServerRequest;

class PaymentController
{
    public function notify(ServerRequest $request, Response $response, array $args)
    {
        $method  = $args['method'];
        $uuid    = $args['uuid'];
        $payment = new PaymentService($method, null, $uuid);
        $verify  = $payment->notify($request);
        $this->handle($verify['order_no']);
        if ($method === 'PayPal') {
            return $response->withJson([
                'ret'   => 1,
                'msg'   => 'success',
            ]);
        }
        die(isset($verify['custom_result']) ? $verify['custom_result'] : 'success');
    }

    public function return(ServerRequest $request, Response $response, array $args)
    {
        $order_no = $args['order_no'];
        $order    = Order::where('order_no', $order_no)->first();
        if ($order) {
            return $response->withStatus(302)->withHeader('Location', '/user/order/'.$order->order_no);
        }
    }

    public function handle($no)
    {
        $order = Order::where('order_no', $no)->first();
        if (!$order->execute_status){
            OrderController::execute($no);
            if (Setting::obtain('enable_push_top_up_message')) {
                $messageText = sprintf(
                    "💰成功收款%s%s\n———————————————\n订单号：%s",
                    $order->order_total,
                    Setting::obtain('currency_unit'),
                    $order->order_no
                );
                Telegram::pushToAdmin($messageText);
            }
        }
        
    }
}