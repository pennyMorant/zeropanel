<?php

namespace App\Controllers;

use App\Models\{
    InviteCode,
    Setting,
};
use App\Utils\{
    Tools,
    TelegramProcess,
    Telegram\Process
};
use Slim\Http\{
    Request,
    Response
};

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
    public function index($request, $response, $args)
    {
        $this->view()
            ->display(Setting::obtain('website_general_landing_index') . '.tpl');
        return $response;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     */
    public function telegram($request, $response, $args)
    {
        $token = $request->getQueryParam('token');
        if ($token == Setting::obtain('telegram_bot_request_token')) {
            if (Setting::obtain('enable_new_telegram_bot')) {
                Process::index();
            } else {
                TelegramProcess::process();
            }
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
    public function page404($request, $response, $args)
    {
        return $response->write($this->view()->fetch('404.tpl'));
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     */
    public function page405($request, $response, $args)
    {
        return $response->write($this->view()->fetch('405.tpl'));
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     */
    public function page500($request, $response, $args)
    {
        return $response->write($this->view()->fetch('500.tpl'));
    }
}
