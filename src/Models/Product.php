<?php

namespace App\Models;

/**
 * @property-read   int     $id
 * @property        string  $name
 * @property        float   $price
 * @property        int     $status
 */
class Product extends Model
{
    protected $connection = 'default';

    protected $table = 'product';

    public function status()
    {
        switch ($this->status) {
            case 0:
                $status = '<div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" value="" id="product_status_'.$this->id.'" onclick="updateProductStatus('.$this->id.')" />
                            </div>';
                break;
            case 1:
                $status = '<div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" value="" id="product_status_'.$this->id.'" checked="checked" onclick="updateProductStatus('.$this->id.')" />
                            </div>';
                break;
        }
        return $status;
    }

    public function type()
    {
        switch ($this->type) {
            case 1:
                $type = '周期产品';
                break;
            case 2:
                $type = '流量产品';
                break;
            case 3:
                $type = '其他产品';
                break;
            default:
                $type = 'best product';
            
        }
        return $type;
    }
    

    public function purchase($user, $order)
    {
        $product_type = $this->type; 
        switch ($product_type) {  // 产品类型
            case 1:
                switch ($order->order_type) { // 判定订单类型
                    case 1: // purchase new product
                        $user->transfer_enable = $this->traffic * 1024 * 1024 * 1024;
                        $user->u               = 0;
                        $user->d               = 0;
                        $user->last_day_t      = 0;
                        $user->class_expire    = date('Y-m-d H:i:s', time() + $this->productPeriod($order->product_price) * 86400);
                        $user->class           = $this->class;
                        $user->node_speedlimit = $this->speed_limit;
                        $user->node_iplimit    = $this->ip_limit;
                        $user->node_group      = $this->user_group;
                        $user->product_id      = $this->id;
                        if ($this->reset_traffic_cycle === 1) { //订单日充值
                            $user->reset_traffic_date  = date('d');
                            $user->reset_traffic_value = $this->traffic;
                        } else if ($this->reset_traffic_cycle === 2) {  //每月1日重置
                            $user->reset_traffic_date  = 1;
                            $user->reset_traffic_value = $this->traffic;
                        }
                        break;
                    case 3: // renew product
                        $user->class_expire = date('Y-m-d H:i:s', strtotime($user->class_expire) + $order->product_period* 86400);
                        break;
                    case 4:  // update product
                        break;
                }
                break;
            case 2: // purchase traffic
                $user->transfer_enable += $this->traffic * 1024 * 1024 * 1024;
                break;    
            case 3: // purchase other product
                break;
        }
        $user->save();
    }

    public function getAverageMonthlyPrice()
    {
       $price = Product::where('id', $this->id)
       ->selectRaw("GREATEST(COALESCE(month_price, 0), COALESCE(quarter_price, 0), COALESCE(half_year_price, 0), COALESCE(year_price, 0), COALESCE(two_year_price, 0)) AS max_price,
       CASE 
           WHEN GREATEST(COALESCE(month_price, 0), COALESCE(quarter_price, 0), COALESCE(half_year_price, 0), COALESCE(year_price, 0), COALESCE(two_year_price, 0)) = month_price THEN 'month_price'
           WHEN GREATEST(COALESCE(month_price, 0), COALESCE(quarter_price, 0), COALESCE(half_year_price, 0), COALESCE(year_price, 0), COALESCE(two_year_price, 0)) = quarter_price THEN 'quarter_price'
           WHEN GREATEST(COALESCE(month_price, 0), COALESCE(quarter_price, 0), COALESCE(half_year_price, 0), COALESCE(year_price, 0), COALESCE(two_year_price, 0)) = half_year_price THEN 'half_year_price'
           WHEN GREATEST(COALESCE(month_price, 0), COALESCE(quarter_price, 0), COALESCE(half_year_price, 0), COALESCE(year_price, 0), COALESCE(two_year_price, 0)) = year_price THEN 'year_price'
           WHEN GREATEST(COALESCE(month_price, 0), COALESCE(quarter_price, 0), COALESCE(half_year_price, 0), COALESCE(year_price, 0), COALESCE(two_year_price, 0)) = two_year_price THEN 'two_year_price'
       END AS column_with_max_price")
       ->first();
        $date = [
            'month_price'       =>  1,
            'quarter_price'     =>  3,
            'half_year_price'   =>  6,
            'year_price'        =>  12,
            'two_year_price'    =>  24,
        ];

        $average_price =  $price->max_price / $date[$price->column_with_max_price];
        return $average_price;
    }

    public function productPeriod($price)
    {
        $product_period = [
            $this->month_price     => 30,
            $this->quarter_price   => 90,
            $this->half_year_price => 180,
            $this->year_price      => 360,
            $this->two_year_price  => 720
        ];
        if (isset($product_period[$price])) {
            $period = $product_period[$price];
        }

        return $period;
    }

}