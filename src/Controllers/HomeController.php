<?php

namespace App\Controllers;

use App\Models\Setting;
use App\Utils\Telegram\Process;
use Slim\Http\Response;
use Slim\Http\ServerRequest;

class HomeController extends BaseController
{
    public function index(ServerRequest $request, Response $response, array $args)
    {
        $this->view()
            ->display(Setting::obtain('website_landing_index') . '.tpl');
        return $response;
    }

    public function telegram(ServerRequest $request, Response $response, array $args)
    {
        $token = $request->getQueryParam('token');
        if ($token == Setting::obtain('telegram_bot_request_token')) {

            Process::commandBot();

            $result = '1';
        } else {
            $result = '0';
        }
        return $response->write($result);
    }

    public function page404(ServerRequest $request, Response $response, array $args)
    {
        return $response->write($this->view()->fetch('404.tpl'));
    }

    public function page405(ServerRequest $request, Response $response, array $args)
    {
        return $response->write($this->view()->fetch('405.tpl'));
    }

    public function page500(ServerRequest $request, Response $response, array $args)
    {
        return $response->write($this->view()->fetch('500.tpl'));
    }
}
