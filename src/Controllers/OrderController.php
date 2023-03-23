<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\{
    Coupon,
    Product,
    Setting,
    Order,
    User,
    Payback,
    Ann,
};
use Slim\Http\Response;
use Slim\Http\ServerRequest;
use App\Services\Payment;
use App\Services\Mail;
use Pkly\I18Next\I18n;

class OrderController extends BaseController
{
    public function order(ServerRequest $request, Response $response, $args)
    {
        $this->view()
            ->assign('anns', Ann::where('date', '>=', date('Y-m-d H:i:s', time() - 7 * 86400))->orderBy('date', 'desc')->get())
            ->display('user/order.tpl');
        return $response;
    }

    public function orderDetails(ServerRequest $request, Response $response, $args)
    {
        $order_no = $args['no'];
        $order = Order::where('user_id', $this->user->id)
            ->where('order_no', $order_no)
            ->first();
        $product = Product::find($order->product_id);

        switch ($order->order_payment) {
            case 'creditpay':
                $payment = 'credit';
                break;
            case 'alipay':
                $payment = 'alipay';
                break;
            case 'wechatpay':
                $payment = 'wechat';
                break;
            case 'cryptopay':
                $payment = 'crypto';
                break;
            default:
                $payment = '未知';
                break;
        }
        $payment = I18n::get()->t($payment);
        $payment_gateway = Setting::getClass('payment_gateway');
            $this->view()
                ->assign('anns', Ann::where('date', '>=', date('Y-m-d H:i:s', time() - 7 * 86400))->orderBy('date', 'desc')->get())
                ->assign('order', $order)
                ->assign('product', $product)
                ->assign('payment', $payment)
                ->assign('payment_gateway', $payment_gateway)
                ->display('user/order_detail.tpl');
        return $response;   
    }

    public function createOrder(ServerRequest $request, Response $response, $args)
    {
        $user = $this->user;
        $coupon_code = $request->getParam('coupon_code');
        $product_id = $request->getParam('product_id');
        $product_price = $request->getParam('product_price');
        $product = Product::find($product_id);
        $amount = $request->getParam('add_credit_amount');
        $type = $args['type'];

        switch ($type) {
            case 1:
                try {
                    if ($product == null) {
                        throw new \Exception(I18n::get()->t('error request'));
                    }
                    $all_price = [$product->month_price, $product->quarter_price, $product->half_year_price, $product->year_price];
                    if (!in_array($product_price, $all_price)) {
                        throw new \Exception(I18n::get()->t('error request'));
                    }
                    
                    if ($user->product_id == $product->id) {                      
                        throw new \Exception('已有该产品，不可新购');
                    }

                    $order = new Order();
                    $order->order_no = self::createOrderNo();
                    $order->user_id = $user->id;
                    $order->product_id = $product->id;
                    $order->order_type = $type;
                    $order->product_price = $product_price;
                    $order->order_coupon = (empty($coupon)) ? null : $coupon_code;
                    $order->order_total = (empty($coupon)) ? $product_price : round($product_price * ((100 - $coupon->discount) / 100), 2);
                    
                    $order->order_status = 1;
                    $order->created_time = time();
                    $order->updated_time = time();
                    $order->expired_time = time() + 600;
                    $order->paid_action = json_encode(['action' => 'buy_product', 'params' => $product->id]);
                    $order->execute_status = 0;
                    $order->save();
                } catch (\Exception $e) {
                    return $response->withJson([
                        'ret' => 0,
                        'msg' => $e->getMessage(),
                    ]);
                }
                break;
            case 2:
                try {
                    if ($amount == '') {
                        throw new \Exception(I18n::get()->t('please enter the amount'));
                    }
                    if ($amount <= 0) {
                        throw new \Exception(I18n::get()->t('amount should be greater than zero'));
                    }
                    $order = new Order();
                    $order->order_no = self::createOrderNo();
                    $order->user_id = $user->id;
                    $order->order_total = $amount;
                    $order->order_type = $type;
                    $order->order_status = 1;
                    $order->created_time = time();
                    $order->updated_time = time();
                    $order->expired_time = time() + 600;
                    $order->execute_status = 0;
                    $order->save();
                } catch (\Exception $e) {
                    return $response->withJson([
                        'ret' => 0,
                        'msg' => $e->getMessage(),
                    ]);
                }
                break;
            default:
                break;
        }
        return $response->withJson([
            'ret' => 1,
            'order_id' => $order->order_no,
        ]);
    }

    public function orderStatus(ServerRequest $request, Response $response, $args)
    {
        $order_no = $args['no'];
        $order = Order::where('order_no', $order_no)->first();

        return $response->withJson([
            'ret' => 1,
            'status' => $order->order_status,
        ]);
    }

    public function processOrder(ServerRequest $request, Response $response, $args)
    {
        $user = $this->user;
        $payment = $request->getParam('method');
        $order_no = $request->getParam('order_no');

        $order = Order::where('user_id', $user->id)->where('order_no', $order_no)->first();
        try {
            if (time() > $order->expired_time) {
                throw new \Exception(I18n::get()->t('order has expired'));
            }
            if ($order->order_status == 2) {
                throw new \Exception(I18n::get()->t('order has paid'));
            }
            
            if ($payment == 'creditpay') {
                if ($user->money < ($order->order_total)) {
                    throw new \Exception(I18n::get()->t('insufficient credit'));
                }
                
                $order->order_payment = $payment;
                $order->credit_paid = $order->order_total;
                $order->save();
                $user->money -= $order->order_total;
                $user->save();
                self::execute($order->order_no);
            } else {
                // 计算结账金额
                if ($order->credit_paid == 0) {
                    $checkout_amount = $order->order_total;
                } else {
                    $checkout_amount = $order->order_total - $order->credit_paid;
                }

                $order->order_payment = $payment;
                $order->save();
                // 提交订单
                $result =  Payment::toPay($user->id, $payment, $order->order_no, $checkout_amount);
                return $response->withJson($result);
            }
        } catch (\Exception $e) {
            return $response->withJson([
                'ret' => 0,
                    //'msg' => $e->getFile() . $e->getLine() . $e->getMessage(),
                    'msg' => $e->getMessage(),
                ]);
            }

        return $response->withJson([
            'ret' => 2, // 0时表示错误; 1是在线支付订单创建成功状态码; 2分配给账户余额支付
            'msg' => I18n::get()->t('success'),
        ]);
    }

    public static function execute($order_no)
    {
        $order = Order::where('order_no', $order_no)->first();
        
        if ($order->product_id == null) {
            return self::executeAddCredit($order);
        } else {
            return self::executeProduct($order);
        }
    }

    public static function executeAddCredit($order)
    {
        if ($order->execute_status !== 1) {
            $order->paid_time = time();
            $order->updated_time = time();
            $order->order_status = 2;
            $order->execute_status = 1;
            $order->save();

            $user = User::find($order->user_id);
            $user->money += $order->order_total;
            $user->save();

            if ($user->ref_by > 0 && Setting::obtain('invitation_mode') === 'after_topup') {
                Payback::rebate($user->id, $order->order_total);
            }
        }
    }

    public static function executeProduct($order)
    {
        $product = Product::find($order->product_id);
        $user = User::find($order->user_id);

        if ($order->execute_status != '1') {
            $order->order_status = 2;
            $order->updated_time = time();
            $order->paid_time = time();

            if (!empty($order->order_coupon)) {
                $coupon = Coupon::where('coupon', $order->order_coupon)->first();
                $coupon->use_count += 1;
                $coupon->discount_amount += $order->product_price - $order->order_total;
                $coupon->save();
            }

            $product->purchase($user, $order->product_price, $order_type);           

            // 返利
            if ($user->ref_by > 0 && Setting::obtain('invitation_mode') === 'after_purchase') {
                Payback::rebate($user->id, $order->order_total);
            }

            // 如果上面的代码执行成功，没有报错，再标记为已处理
            $order->execute_status = 1;
            $order->save();
            if ($product->stock !== -1) {
                $product->sales += 1; // 加销量
            }           
            $product->save();
            // 告罄补货通知
            /*
            if ($product->stock - $product->sales == 5 || $product->stock - $product->sales == 0) {
                $admin_users = User::where('is_admin', '1')->get();
                foreach ($admin_users as $admin) {
                    Mail::send($admin->email, $_ENV['appName'] . ' - 商品缺货通知', 'news/warn.tpl',
                        [
                            'user' => $admin,
                            'text' => '商品【' . $product->name . '】当前库存仅有 ' . ($product->stock - $product->sales) . ' 件，请注意及时补货',
                        ], []
                    );
                }
            }*/
        }
    }

    public function verifyCoupon(ServerRequest $request, Response $response, $args)
    {
        $coupon = $request->getParam('coupon_code');
        $coupon = trim($coupon);

        $user = $this->user;

        if (!$user->isLogin) {
            $res['ret'] = -1;
            return $response->withJson($res);
        }

        $product_id = $request->getParam('product_id');

        $product = Product::where('id', $product_id)->where('status', 1)->first();

        if ($product == null) {
            $res['ret'] = 0;
            $res['msg'] = I18n::get()->t('error request');
            return $response->withJson($res);
        }

        $coupons = Coupon::where('code', $coupon)->first();

        if ($coupons == null) {
            $res['ret'] = 0;
            $res['msg'] = I18n::get()->t('promo code invalid');
            return $response->withJson($res);
        }

        if ($coupons->expire < time()) {
            $res['ret'] = 0;
            $res['msg'] = I18n::get()->t('promo code has expired');
            return $response->withJson($res);
        }
        
        if ($coupons->order($product->id) == false) {
            $res['ret'] = 0;
            $res['msg'] = I18n::get()->t('the conditions of use are not met');
            return $response->withJson($res);
        }

        $per_use_limit = $coupons->per_use_count;
        if ($per_use_limit > 0) {
            $use_count = Order::where('user_id', $user->id)
                ->where('order_coupon', $coupons->code)
                ->where('order_status', 'paid')
                ->count();
            if ($use_count >= $per_use_limit) {
                $res['ret'] = 0;
                $res['msg'] = I18n::get()->t('promo code have been used up');
                return $response->withJson($res);
            }
        }

        $total_use_limit = $coupons->total_use_count;
        if ($total_use_limit > 0) {
            $total_use_count = Order::where('order_coupon', $coupons->code)
                ->where('order_status', 'paid')
                ->count();
            if ($total_use_count >= $total_use_limit) {
                $res['ret'] = 0;
                $res['msg'] = I18n::get()->t('promo code have been used up');
                return $response->withJson($res);
            }
        }

        $res['ret']     = 1;
        $res['total']   = round($product->price * ((100 - $coupons->discount) / 100), 2);
        return $response->withJson($res);
    }

    public static function createOrderNo(){
        $order_id_main = date('YmdHis') . rand(10000000,99999999);
        $order_id_len = strlen($order_id_main);
        $order_id_sum = 0;

        for($i=0; $i<$order_id_len; $i++){
            $order_id_sum += (int)(substr($order_id_main,$i,1));
        }

        $order_no = $order_id_main . str_pad((100 - $order_id_sum % 100) % 100,2,'0',STR_PAD_LEFT);
        return $order_no;
    }
}