<?php

namespace App\Models;

class Coupon extends Model
{
    protected $connection = 'default';
    protected $table = 'coupon';

    public function getLimitedProductPeriod()
    {
        $product_period = json_decode($this->limited_product_period, true);
        $period_string = [];
        foreach ($product_period as $value) {
            $period_string[] = $value;
        }
        return $period_string;
    }
}
