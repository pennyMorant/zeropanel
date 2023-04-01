<?php

namespace App\Services;

use App\Services\Auth\Cookie;

class Factory
{
    public static function createAuth()
    {
        $method = $_ENV['authDriver'];
        switch ($method) {
            case 'cookie':
                return new Cookie();
        }
    }
}
