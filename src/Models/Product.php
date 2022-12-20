<?php

namespace App\Models;

/**
 * @property-read   int     $id
 * @property        string  $name
 * @property        float   $price
 * @property        array   $content
 * @property        int     $auto_renew
 * @property        int     $auto_reset_bandwidth
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

    public function expire()
    {
        return $this->account_validity_period ?? 0;
    }

    public function reset()
    {
        return $this->traffic_reset_period ?? 0;
    }

    public function reset_value()
    {
        return $this->traffic_reset_value ?? 0;
    }

    public function reset_exp()
    {
        return $this->traffic_reset_validity_period ?? 0;
    }

    public function traffic_package()
    {
        return isset($this->content['traffic_package']);
    }

    public function user_class()
    {
        return $this->class ?? 0;
    }

    public function class_expire()
    {
        return $this->class_validity_period ?? 0;
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
    

    public function purchase($user)
    {
        $type = $this->type;
        switch ($type) {
        case 'traffic':
            $user->transfer_enable += $this->traffic * 1024 * 1024 * 1024;
            $user->save();
        break;
        
        case 'cycle':
            if (Setting::obtain('enable_reset_traffic_when_purchase_user_general') == true) {
                $user->transfer_enable = $this->traffic * 1024 * 1024 * 1024;
                $user->u = 0;
                $user->d = 0;
                $user->last_day_t = 0;
            } else {
                $user->transfer_enable += $this->traffic * 1024 * 1024 * 1024;
            }

            if (Setting::obtain('enable_add_times_when_purchase_user_general') == true) {
                if ($user->class == $this->class) {
                    $user->class_expire = date('Y-m-d H:i:s', strtotime($user->class_expire) + $this->class_validity_period * 86400);
                } else {
                    $user->class_expire = date('Y-m-d H:i:s', time() + $this->class_validity_period * 86400);
                }
                $user->class = $this->class;
            } else {
                $user->class = $this->class;
                $user->class_expire = date('Y-m-d H:i:s', time() + $this->class_validity_period * 86400);
            }

            $user->node_speedlimit = $this->speed_limit;

            $user->node_connector = $this->ip_limit;

            $user->node_group = $this->user_group;
            $user->save();
        break;
        }
    }

}