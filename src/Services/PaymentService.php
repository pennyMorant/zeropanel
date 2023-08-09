<?php

namespace App\Services;


use App\Models\Setting;
use App\Models\Payment;
use Slim\Http\Response;
use Slim\Http\ServerRequest;

class PaymentService
{
    public $method;
    protected $class;
    protected $config;
    protected $payment;

    public function __construct($method, $id = null, $uuid = null)
    {
        $this->method = $method;
        $this->class  = '\\App\\Payments\\' . $this->method;
        if ($id) $payment   = Payment::find($id)->toArray();
        if ($uuid) $payment = Payment::where('uuid', $uuid)->first()->toArray();
           $this->config    = [];
        if (isset($payment)) {
            $this->config                   = json_decode($payment['config'], true);
            $this->config['enable']         = $payment['enable'];
            $this->config['id']             = $payment['id'];
            $this->config['uuid']           = $payment['uuid'];
            $this->config['notify_domain']  = $payment['notify_domain'];
            $this->config['recharge_bonus'] = $payment['recharge_bonus'];
        };
        
        $this->payment = new $this->class($this->config);
    }

    public function notify($param)
    {
        return  $this->payment->notify($param);
    }

    public function toPay($order)
    {
        $notify_url = Setting::obtain('website_url') . "/payment/notify/" . $this->method  . '/' . $this->config['uuid'];
        if ($this->config['notify_domain']) {
            $notify_url = $this->config['notify_domain'] . "/payment/notify/" . $this->method . '/' . $this->config['uuid'];
        }

        return $this->payment->pay([
            'notify_url'   => $notify_url,
            'return_url'   => Setting::obtain('website_url') . "/payment/return/" . $order['order_no'],
            'order_no'     => $order['order_no'],
            'total_amount' => $order['total_amount'],
            'user_id'      => $order['user_id']
        ]);
        
    }
}