<?php

namespace App\Models;

use Pkly\I18Next\I18n;
use App\Controllers\OrderController;

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
        switch ($this->order_payment) {
            case 'creditpay':
                $payment = '<i class="bi bi-cash-coin fs-2hx text-success"></i>';
                break;
            case 'alipay':
                $payment = '<i class="bi bi-alipay fs-2hx text-primary"></i>';
                break;
            case 'wechatpay':
                $payment = '<i class="bi bi-wechat fs-2hx text-success"></i>';
                break;
            case 'cryptopay':
                $payment = '<i class="bi bi-currency-bitcoin fs-2hx text-warning"></i>';
                break;
            default:
                $payment = '<i class="bi bi-question-circle fs-2hx text-danger"></i>';
                break;
        }
        return $payment;
    }

    public function orderType() {
        $order_type = [
            1   =>  '新购产品',
            2   =>  '账户充值',
            3   =>  '续费产品',
            4   =>  '升级产品',
        ];
        return $order_type[$this->order_type];
    }

    public function finshOrder($order_no) {
        orderController::execute($order_no);
    }
}

