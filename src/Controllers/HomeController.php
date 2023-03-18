<?php

namespace App\Controllers;

use App\Models\{
    InviteCode,
    Setting,
};
use App\Utils\{
    Tools,
    Telegram\Process
};
use Slim\Http\Response;
use Slim\Http\ServerRequest;

/**
 *  HomeController
 */
class HomeController extends BaseController
{
    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     */
    public function index(ServerRequest $request, Response $response, $args)
    {
        $this->view()
            ->display(Setting::obtain('website_landing_index') . '.tpl');
        return $response;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     */
    public function telegram(ServerRequest $request, Response $response, $args)
    {
        $token = $request->getQueryParam('token');
        if ($token == Setting::obtain('telegram_bot_request_token')) {
           
            Process::index();
            
            $result = '1';
        } else {
            $result = '0';
        }
        return $response->write($result);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     */
    public function page404(ServerRequest $request, Response $response, $args)
    {
        return $response->write($this->view()->fetch('404.tpl'));
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     */
    public function page405(ServerRequest $request, Response $response, $args)
    {
        return $response->write($this->view()->fetch('405.tpl'));
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     */
    public function page500(ServerRequest $request, Response $response, $args)
    {
        return $response->write($this->view()->fetch('500.tpl'));
    }
}
