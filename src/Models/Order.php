<?php

namespace App\Models;

use Pkly\I18Next\I18n;
use App\Models\Payment;
use App\Services\PaymentService;

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

    public function payment() {
        $payment = Payment::find($this->payment_id);

        return $payment->name ?? '未选择';
    }

    public function orderType() {
        $order_type = [
            1   =>  '新购产品',
            2   =>  '充值余额',
            3   =>  '续费产品',
            4   =>  '升级产品',
        ];
        return $order_type[$this->order_type];
    }

    public function finshOrder($order_no) {
        PaymentService::executeAction($order_no);
    }
}

