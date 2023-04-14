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
        $method = $args['method'];
        $uuid = $args['uuid'];
        $payment = new PaymentService($method, null, $uuid);
        $result = $payment->notify($request);
        $this->handle($result['order_no']);
        OrderController::execute($result['order_no']);
        die(isset($verify['custom_result']) ? $verify['custom_result'] : 'success');
    }

    public function return(ServerRequest $request, Response $response, array $args)
    {
        $order_no = $request->getParam('tradeno');
        $order = Order::where('order_no', $order_no)->first();
        return $response->withStatus(302)->withHeader('Location', '/user/order/'.$order->order_no);
    }

    public function handle($no)
    {
        $order = Order::where('order_no', $no)->first();
        if ($order->order_status == '2') {
            if (Setting::obtain('enable_push_top_up_message') == true) {
                $messageText = sprintf(
                    "💰成功收款%s元\n———————————————\n订单号：%s",
                    $order->order_total,
                    $order->order_no
                );
                Telegram::pushToAdmin($messageText);
            }
        }
    }
}