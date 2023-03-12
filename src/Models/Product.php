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
    
    protected $casts = [
        'content' => 'array',
    ];


    public function bandwidth()
    {
        return $this->traffic ?? 0;
    }

    

    public function reset()
    {
        return $this->reset_traffic_cycle ?? 0;
    }

    public function user_class()
    {
        return $this->class ?? 0;
    }

    public function class_expire()
    {
        return $this->time ?? 0;
    }

    public function speedlimit()
    {
        return $this->speed_limit ?? 0;
    }

    public function connector()
    {
        return $this->ip_limit ?? 0;
    }
    
    public function node_group()
    {
        return $this->user_group ?? -1;
    }

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
    

    public function purchase($user, $price)
    {
        $type = $this->type;
        if ($price == $this->month_price) {
            $time = 30;
        } else if($price == $this->quarter_price) {
            $time = 90;
        } else if ($price == $this->half_year_price) {
            $time = 180;
        } else if ($price == $this->year_price) {
            $time = 360;
        }
        switch ($type) {
        case 2:
            $user->transfer_enable += $this->traffic * 1024 * 1024 * 1024;
            $user->save();
        break;      
        case 1:         
            $user->transfer_enable = $this->traffic * 1024 * 1024 * 1024;
            $user->u = 0;
            $user->d = 0;
            $user->last_day_t = 0;
            
            if ($user->class == $this->class && $user->reset_traffic_value == $this->traffic && $user->userTrafficResetCycle() == $this->reset_traffic_cycle) {
                $user->class_expire = date('Y-m-d H:i:s', strtotime($user->class_expire) + $time * 86400);
            } else {
                $user->class_expire = date('Y-m-d H:i:s', time() + $time * 86400);
            }
            $user->class = $this->class;

            $user->node_speedlimit = $this->speed_limit;
            $user->node_connector = $this->ip_limit;
            if ($this->user_group != null) {
                $user->node_group = $this->user_group;
            }
            $user->product_id = $this->id;
            if ($this->reset_traffic_cycle === 1 && $time > 30) {
                $user->reset_traffic_date = date('d');
                $user->reset_traffic_value = $this->traffic;
            } else if ($this->reset_traffic_cycle === 2 && $time > 30) {
                $user->reset_traffic_date = 1;
                $user->reset_traffic_value = $this->traffic;
            }
            $user->save();
        break;
        }
    }

}