<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\{
    Coupon,
    Product,
    Setting,
    Order,
    User,
    Commission,
    Payment
};
use Slim\Http\Response;
use Slim\Http\ServerRequest;
use App\Services\PaymentService;
use App\Services\CouponService;
use Pkly\I18Next\I18n;

class OrderController extends BaseController
{
    public function order(ServerRequest $request, Response $response, array $args)
    {
        $this->view()
            ->display('user/order.tpl');
        return $response;
    }

    public function orderDetails(ServerRequest $request, Response $response, array $args)
    {
        $order_no = $args['no'];
        $order = Order::where('user_id', $this->user->id)
            ->where('order_no', $order_no)
            ->first();
        if (!is_null($order->product_id) && $order->order_type != '2') {
            $product = Product::find($order->product_id);
            $product_name = $product->name;
            $order_type = [
                1   =>  I18n::get()->t('purchase product') .  ': ' . $product_name . '-' . $product->productPeriod($order->product_price),
                3   =>  I18n::get()->t('renewal product') .': ' . $product_name . '-' . $product->productPeriod($order->product_price),
                4   =>  I18n::get()->t('upgrade product') .': ' . $product_name . '-' . $product->productPeriod($order->product_price),
            ];
        } else {
            $product_name = '';
            $product = [];
            $order_type = [
                2   =>  I18n::get()->t('add credit') .': ' . $order->order_total,
            ];
        }
        $paypal_currency_unit = Setting::obtain('currency_unit') ?: 'USD';
        $payments = Payment::where('enable', 1)->get();
            $this->view()
                ->assign('order', $order)
                ->assign('product', $product)
                ->assign('order_type', $order_type)
                ->assign('payments', $payments)
                ->assign('paypal_currency_unit', $paypal_currency_unit)
                ->display('user/order_detail.tpl');
        return $response;   
    }

    public function createOrder(ServerRequest $request, Response $response, array $args)
    {
        $user          = $this->user;
        $postData      = $request->getParsedBody();
        $type          = $args['type'];

        switch ($type) {
            case 1: // 新购产品
                $coupon_code   = $postData['coupon_code'];
                $product_id    = $postData['product_id'];
                $product_price = $postData['product_price'];
                $product       = Product::find($product_id);
                try {
                    if (Setting::obtain('verify_email') === 'open' && $user->verified === 0) {
                        throw new \Exception(I18n::get()->t('please verify email first'));
                    }
                    if (is_null($product)) {
                        throw new \Exception(I18n::get()->t('error request'));
                    }
                    
                    if (!$product->productPeriod($product_price)) {
                        throw new \Exception(I18n::get()->t('error request'));
                    }
                    if (($product->stock > 0) && $product->stock - $product->realTimeSales() <= 0) {
                        throw new \Exception(I18n::get()->t('sold'));
                    }
                    if ($user->product_id == $product->id) {                      
                        throw new \Exception('已有该产品，请在主页点击续费');
                    }

                    $order                 = new Order();
                    $order->order_no       = self::createOrderNo();
                    $order->user_id        = $user->id;
                    $order->product_id     = $product->id;
                    $order->order_type     = $type;
                    $order->product_price  = $product_price;
                    $order->product_period = $product->productPeriod($product_price) ?? NULL;
                    $order->order_total    = $product_price;
                    if (!empty($coupon_code)) {
                        $couponService = new CouponService($coupon_code);
                        if (!$couponService->use($order)){
                            throw new \Exception(I18n::get()->t('coupon failed'));
                        }
                        $order->coupon_id = $couponService->getID();
                        $order->order_total = round($order->order_total - $order->discount_amount, 2);
                    }
                    if ($user->money > 0 && $order->order_total > 0) {
                        $remaining_total = $user->money - $order->order_total;
                        if ($remaining_total > 0) {
                            $order->credit_paid = $order->order_total;
                            $order->order_total = 0;
                        } else {
                            $order->credit_paid = $user->money;
                            $order->order_total = $order->order_total - $user->money;
                        }
                    }
                    
                    $order->order_status   = 1;
                    $order->created_at     = time();
                    $order->updated_at     = time();
                    $order->expired_at     = time() + 600;
                    $order->execute_status = 0;
                    $order->save();
                } catch (\Exception $e) {
                    return $response->withJson([
                        'ret' => 0,
                        'msg' => $e->getMessage(),
                    ]);
                }
                break;
            case 2: // 账户充值
                $amount        = $postData['add_credit_amount'];
                try {
                    if ($amount == '') {
                        throw new \Exception(I18n::get()->t('please enter the amount'));
                    }
                    if ($amount <= 0) {
                        throw new \Exception(I18n::get()->t('amount should be greater than zero'));
                    }
                    $order                 = new Order();
                    $order->order_no       = self::createOrderNo();
                    $order->user_id        = $user->id;
                    $order->order_total    = $amount;
                    $order->order_type     = $type;
                    $order->order_status   = 1;
                    $order->created_at     = time();
                    $order->updated_at     = time();
                    $order->expired_at     = time() + 600;
                    $order->execute_status = 0;
                    $order->save();
                } catch (\Exception $e) {
                    return $response->withJson([
                        'ret' => 0,
                        'msg' => $e->getMessage(),
                    ]);
                }
                break;
            case 3: //续费产品
                try {
                    $latest_order = Order::where('user_id', $user->id)->where('order_status', 2)
                        ->where('order_type', 1)->where('product_id', $user->product_id)->latest('paid_at')->first();
                    if (is_null($latest_order)){
                        throw new \Exception('订单不存在');
                    }
                    if (is_null($latest_order->product_period)) {
                        throw new \Exception('订单错误，无法续费');
                    }
                    $product = Product::find($user->product_id);
                    if (is_null($product)) {
                        throw new \Exception('产品已经被删除, 续费失败');
                    }
                    if ($product->renew === 0 && $product->status === 0) {
                        throw new \Exception('该产品不允许续费');
                    }
                    
                    $order                 = new Order;
                    $order->order_no       = OrderController::createOrderNo();
                    $order->order_type     = 3;
                    $order->user_id        = $user->id;
                    $order->product_id     = $latest_order->product_id;
                    $order->product_price  = $latest_order->product_price;
                    $order->product_period = $product->productPeriod($latest_order->product_price);
                    $order->order_total    = $latest_order->order_total + $latest_order->credit_paid;
                    if ($user->money > 0 && $order->order_total > 0) {
                        $remaining_total = $user->money - $order->order_total;
                        if ($remaining_total > 0) {
                            $order->credit_paid = $order->order_total;
                            $order->order_total = 0;
                        } else {
                            $order->credit_paid = $user->money;
                            $order->order_total = $order->order_total - $user->money;
                        }
                    }
                    $order->order_status   = 1;
                    $order->created_at     = time();
                    $order->updated_at     = time();
                    $order->expired_at     = time() + 600;
                    $order->execute_status = 0;
                    $order->save();
                } catch (\Exception $e){
                    return $response->withJson([
                        'ret' => 0,
                        'msg' => $e->getMessage(),
                    ]);
                }
            default:
                break;
        }
        return $response->withJson([
            'ret'      => 1,
            'order_no' => $order->order_no,
        ]);
    }

    public function orderStatus(ServerRequest $request, Response $response, array $args)
    {
        $order_no = $args['no'];
        $order = Order::where('order_no', $order_no)->first();

        return $response->withJson([
            'ret' => 1,
            'status' => $order->order_status,
        ]);
    }

    public function processOrder(ServerRequest $request, Response $response, array $args)
    {
        $user       = $this->user;
        $payment_id = $request->getParsedBodyParam('payment_id');
        $order_no   = $request->getParsedBodyParam('order_no');

        $order = Order::where('user_id', $user->id)->where('order_no', $order_no)->first();
        try {
            if (time() > $order->expired_at) {
                throw new \Exception(I18n::get()->t('order has expired'));
            }
            if ($order->order_status == 2) {
                throw new \Exception(I18n::get()->t('order has paid'));
            }
            
            if ($order->order_total <= 0) {
                if ($user->money < $order->order_total) {
                    throw new \Exception(I18n::get()->t('insufficient credit'));
                }
                self::execute($order->order_no);
            } else {
                // 计算结账金额              
                $payment         = Payment::find($payment_id);
                $payment_service = new PaymentService($payment->gateway, $payment->id);
                if ($payment->fixed_fee || $payment->percent_fee) {
                    $order->handling_fee = round(($order->order_total * ($payment->percent_fee / 100)) + $payment->fixed_fee, 2);
                }

                if ($payment->recharge_bonus && $order->order_type === 2) {
                    $order->bonus_amount = $order->order_total * ($payment->recharge_bonus / 100);
                }
                
                $currency          = Setting::getClass('currency');
                $exchange_rate     = $currency['currency_exchange_rate'] ?: 1;
                if ($payment->gateway === 'PayPal') {
                    $exchange_rate = 1;
                }
                $order->payment_id = $payment_id;
                $order->save();

                $result = $payment_service->toPay([
                    'order_no'     => $order->order_no,
                    'total_amount' => isset($order->handling_fee) ? round((($order->order_total + $order->handling_fee) * $exchange_rate), 2) : round($order->order_total * $exchange_rate, 2),
                    'user_id'      => $user->id
                ]);
                
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
        if (!$order->execute_status) {
            if (is_null($order->product_id) && $order->order_type === 2) {
                return self::executeAddCredit($order);
            } else {
                return self::executeProduct($order);
            }
        }
    }

    public static function executeAddCredit($order)
    {    
        $order->paid_at        = time();
        $order->updated_at     = time();
        $order->order_status   = 2;
        $order->execute_status = 1;
        $order->save();

        $user         = User::find($order->user_id);
        $user->money += $order->order_total + $order->bonus_amount;
        $user->save();

        if ($user->ref_by > 0 && Setting::obtain('invitation_mode') === 'after_topup') {
            Commission::rebate($user->id, $order->order_total, $order->order_no);
        }
    }

    public static function executeProduct($order)
    {
        $product = Product::find($order->product_id);
        $user    = User::find($order->user_id);
      
        $product->purchase($user, $order);        
        // 如果上面的代码执行成功，没有报错，再标记为已处理
        $order->order_status   = 2;
        $order->updated_at     = time();
        $order->paid_at        = time();
        $order->execute_status = 1;
        $order->save();
        $product->sales += 1;  // 加累积销量     
        $product->save();
        $user->money -= $order->credit_paid;  // 支付成功，从用户账户扣除余额抵扣金额
        $user->save();
        // 优惠码被使用次数
        if (!is_null($order->coupon_id)) {
            $coupon = Coupon::find($order->coupon_id);
            $coupon->total_used_count += 1;
            $coupon->save();
        }
        // 返利
        if ($user->ref_by > 0 && Setting::obtain('invitation_mode') === 'after_purchase') {
            Commission::rebate($user->id, $order->order_total, $order->order_no);
        }
    }

    public function verifyCoupon(ServerRequest $request, Response $response, array $args)
    {
        $coupon_code    = $request->getParsedBodyParam('coupon_code');
        $product_id     = $request->getParsedBodyParam('product_id');
        $product_price  = $request->getParsedBodyParam('product_price');
        $user           = $this->user;
        $product        = Product::where('id', $product_id)->where('status', 1)->first();
        $coupons        = Coupon::where('code', $coupon_code)->first();
        //$per_use_limit  = $coupons->per_use_count ?? NULL;
        $product_period = $product->productPeriod($product_price);
        try{
            if (is_null($product)) {
                throw new \Exception(I18n::get()->t('error request'));
            }           
            if (is_null($coupons)) {
                throw new \Exception(I18n::get()->t('promo code invalid'));
            }

            if ($coupons->expired_at < time()) {
                throw new \Exception(I18n::get()->t('promo code has expired'));
            }
            
            if (!is_null($coupons->limited_product)) {
                if (!in_array($product_id, json_decode($coupons->limited_product, true))) {
                    throw new \Exception(I18n::get()->t('此优惠码不适用于此产品'));
                }
            }
            if (!is_null($coupons->limited_product_period)) {
                if (!in_array($product_period, json_decode($coupons->limited_product_period, true))) {
                    throw new \Exception(I18n::get()->t('此优惠码不适用于此产品周期'));
                }
            }        
            if (!is_null($coupons->per_use_limit)) {
                $use_count = Order::where('user_id', $user->id)
                    ->where('coupon_id', $coupons->id)
                    ->whereNotIn('order_status', [0, 1])
                    ->count();
                if ($use_count >= $coupons->per_use_limit) {
                    throw new \Exception(I18n::get()->t('promo code have been used up'));
                }
            }
           
            if ($coupons->total_use_count <= 0 && !is_null($coupons->total_use_count)) {
                throw new \Exception(I18n::get()->t('promo code have been used up'));
            }
        } catch (\Exception $e) {
            return $response->withJson([
                'ret' => 0,
                'msg' => $e->getMessage(),
            ]);
        }
        
        return $response->withJson([
            'ret'     => 1,
            'total'   => number_format($product_price * ((100 - $coupons->discount) / 100), 2)
        ]);
    }

    public static function createOrderNo(){
        $order_id_main = date('YmdHis') . rand(10000000,99999999);
        $order_id_len  = strlen($order_id_main);
        $order_id_sum  = 0;

        for($i=0; $i<$order_id_len; $i++){
            $order_id_sum += (int)(substr($order_id_main,$i,1));
        }

        $order_no = $order_id_main . str_pad((100 - $order_id_sum % 100) % 100,2,'0',STR_PAD_LEFT);
        return $order_no;
    }
}
