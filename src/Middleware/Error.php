<?php
declare(strict_types=1);
namespace App\Middleware;

use Slim\Interfaces\ErrorRendererInterface;
use Throwable;
use App\Services\View;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpBadRequestException;

class Error implements ErrorRendererInterface
{
    public function __invoke(Throwable $exception, bool $displayErrorDetails): string
    {
        $view = View::getSmarty();
        if ($exception instanceof HttpNotFoundException) {
            $template = '404.tpl';
        } elseif ($exception instanceof HttpForbiddenException) {
            $template = '405.tpl';
        } elseif ($exception instanceof HttpBadRequestException) {
            $template = '500.tpl';
        } else {
            $template = '404.tpl';
        }
        $view->assign('exception', $exception);
        $view->assign('displayErrorDetails', $displayErrorDetails);
        return $view->fetch($template);
    }
}