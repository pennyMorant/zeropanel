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
    

    public function purchase($user, $price, $order_type)
    {
        $product_type = $this->type;
        $price_to_time = [
            $this->month_price => 30,
            $this->quarter_price => 90,
            $this->half_year_price => 180,
            $this->year_price => 360
        ];
        if (isset($price_to_time[$price])) {
            $time = $price_to_time[$price];
        }
        
        switch ($product_type) {  // 产品类型
            case 1:
                switch ($order_type) { // 判定订单类型
                    case 1:
                        $user->transfer_enable = $this->traffic * 1024 * 1024 * 1024;
                        $user->u = 0;
                        $user->d = 0;
                        $user->last_day_t = 0;             
                        $user->class_expire = date('Y-m-d H:i:s', time() + $time * 86400);
                        $user->class = $this->class;
                        $user->node_speedlimit = $this->speed_limit;
                        $user->node_iplimit = $this->ip_limit;                       
                        $user->node_group = $this->user_group;
                        $user->product_id = $this->id;
                        if ($this->reset_traffic_cycle === 1 && $time > 30) {
                            $user->reset_traffic_date = date('d');
                            $user->reset_traffic_value = $this->traffic;
                        } else if ($this->reset_traffic_cycle === 2 && $time > 30) {
                            $user->reset_traffic_date = 1;
                            $user->reset_traffic_value = $this->traffic;
                        }
                        break;
                    case 3:
                        $user->class_expire = date('Y-m-d H:i:s', strtotime($user->class_expire) + $time * 86400);
                        if ($time = 30) {                            
                            $user->transfer_enable = $this->traffic * 1024 * 1024 * 1024;
                        }                          
                        break;
                    case 4:
                        break;
                }
                break;
            case 2:
                $user->transfer_enable += $this->traffic * 1024 * 1024 * 1024;
                break;    
            case 3:
                break;
        }
        $user->save();
    }

}