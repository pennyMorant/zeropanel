<?php

namespace App\Models;

class Coupon extends Model
{
    protected $connection = 'default';
    protected $table = 'coupon';

    public function order($product_id)
    {
        if ($this->limited_product == '') {
            return true;
        }

        $product_array = explode(',', $this->limited_product);

        foreach ($product_array as $product) {
            if ($product == $product_id) {
                return true;
            }
        }

        return false;
    }
}
