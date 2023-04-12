<?php

namespace App\Models;

final class Payment extends Model
{
    protected $connection = 'default';
    protected $table = 'payment';

    public function enable() 
    {
        switch ($this->enable) {
            case 0:
                $enable = '<div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" value="" onclick="zeroAdminEnablePayment(' . 1 . ', '.$this->id.')" />
                            </div>';
                break;
            case 1:
                $enable = '<div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" value="" checked="checked" onclick="zeroAdminEnablePayment('. 0 . ', '.$this->id.')" />
                            </div>';
                break;
        }
        return $enable;
    }
}