<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Order;

class CouponService
{
    public $coupon;
    public $productId;
    public $userId;
    public $period;

    public function __construct($code)
    {
        $this->coupon = Coupon::where('code', $code)->first();
    }

    public function use(Order $order): bool
    {
        $this->setProductId($order->product_id);
        $this->setUserId($order->user_id);
        $this->setPeriod($order->product_period);
        if (!$this->check()){
            return false;
        }

        $order->discount_amount = round($order->order_total * ($this->coupon->discount / 100), 2);
        if ($order->discount_amount > $order->order_total) {
            $order->discount_amount = $order->order_total;
        }
        if (!is_null($this->coupon->total_use_count)) {
            if ($this->coupon->total_use_count <= 0) return false;
            $this->coupon->total_use_count = $this->coupon->total_use_count - 1;
            if (!$this->coupon->save()) {
                return false;
            }
        }
        
        return true;
    }

    public function getId()
    {
        return $this->coupon->id;
    }

    public function getCoupon()
    {
        return $this->coupon;
    }

    public function setProductId($productId)
    {
        $this->productId = $productId;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function setPeriod($period)
    {
        $this->period = $period;
    }

    public function checkLimitUseWithUser(): bool
    {
        $usedCount = Order::where('coupon_id', $this->coupon->id)
            ->where('user_id', $this->userId)
            ->whereNotIn('order_status', [0, 1])
            ->count();
        if ($usedCount >= $this->coupon->per_use_count) return false;
        return true;
    }

    public function check(): bool
    {
        if (!$this->coupon) {
            return false;
        }
        if ($this->coupon->total_use_count <= 0 && !is_null($this->coupon->total_use_count)) {
            return false;
        }
        if (time() > $this->coupon->expired_at) {
            return false;
        }
        if (!is_null($this->coupon->limited_product)) {
            if (!in_array($this->productId, json_decode($this->coupon->limited_product, true))) {
                return false;
            }
        }
        if (!is_null($this->coupon->limited_product_period)) {
            if (!in_array($this->period, json_decode($this->coupon->limited_product_period, true))) {
               return false;
            }
        }
        if (!is_null($this->coupon->per_use_count) && $this->userId) {
            if (!$this->checkLimitUseWithUser()) {
               return false;
            }
        }
        return true;
    }
}
