<?php

namespace App\Payments;

use Slim\Http\ServerRequest;
use Slim\Http\Response;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PayPal
{
    protected $config = [];
    protected $paypal_config = [];

    public function __construct($config)
    {
        $this->config = $config;

        $this->paypal_config = [
            'mode' => 'live',
            'sandbox' => [
                'client_id' => $this->config['paypal_client_id'],
                'client_secret' => $this->config['paypal_secret'],
                'app_id' => '',
            ],
            'live' => [
                'client_id' => $this->config['paypal_client_id'],
                'client_secret' => $this->config['paypal_secret'],
                'app_id' => '',
            ],
            'payment_action' => 'Sale',
            'currency' => 'USD',
            'notify_url' => '',
            'locale' => 'en_US',
            'validate_ssl' => true,
        ];
    }

    public function pay ($order)
    {
        $order_data = [
            "intent" => "CAPTURE",
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => 'USD',
                        "value" => $order['total_amount'],
                    ],
                    "reference_id" => $order['order_no'],
                ],
            ],
        ];

        $paypal = new PayPalClient($this->paypal_config);
        $paypal->getAccessToken();
        $trade = $paypal->createOrder($order_data);
        return $trade;
        
    }

    public function notify (ServerRequest $request)
    {
        $order_id = $request->getParsedBodyParam('orderID');

        $pp = new PayPalClient($this->paypal_config);
        $pp->getAccessToken();

        $result = $pp->capturePaymentOrder($order_id);
        $transaction = $result['purchase_units'][0]['payments']['captures'][0];
        if ($transaction['status'] === 'COMPLETED') {
            return [
                'order_no' => $result['purchase_units'][0]['reference_id'],
            ];
        }
        return false;
    }
}