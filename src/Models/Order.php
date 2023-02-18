<?php

namespace App\Models;

use Pkly\I18Next\I18n;

class Order extends Model
{
    protected $connection = 'default';
    protected $table = 'order';

    public function status() {
        $trans = I18n::get();
        switch ($this->order_status) {
            case 2:
                $status = '<div class="badge font-weight-bold badge-light-success fs-6">' . $trans->t('paid') . '</div>';
                break;
            case 1:
                $status = '<div class="badge font-weight-bold badge-light-warning fs-6">' . $trans->t('pending') . '</div>';
                break;
            case 0: 
                $status = '<div class="badge font-weight-bold badge-light-danger fs-6">' . $trans->t('invalid') . '</div>';
                break;
        }
        return $status;
    }
}

