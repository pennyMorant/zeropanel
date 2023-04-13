<?php

namespace App\Services;

use App\Controllers\OrderController;
use App\Models\Order;
use App\Models\User;
use App\Models\Setting;
use App\Models\Payment;
use App\Utils\Telegram;
use Slim\Http\Response;
use Slim\Http\ServerRequest;

class OrderService
{
    public function notifyOrder (ServerRequest $request, Response $response, array $args)
    {
        
    }
}