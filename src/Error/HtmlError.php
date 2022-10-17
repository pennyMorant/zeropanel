<?php
declare(strict_types=1);
namespace App\Error;

use Slim\Interfaces\ErrorRendererInterface;
use Throwable;
use App\Services\View;

class HtmlError implements ErrorRendererInterface
{
    public function __invoke(Throwable $exception, bool $displayErrorDetails): string
    {
        $view = View::getSmarty();
        if (http_response_code(500)) {
            return ($view->fetch('500.tpl'));
        } else if (http_response_code(404)) {
            return ($view->fetch('404.tpl'));
        } else if (http_response_code(405)) {
            return ($view->fetch('405.tpl'));
        } else {
            return ($view->fetch('500.tpl'));
        }
    }
}