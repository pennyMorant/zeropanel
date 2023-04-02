<?php

namespace App\Utils;

final class Cookie
{
    public static function set($arg, $time): void
    {
        foreach ($arg as $key => $value) {
            setcookie((string) $key, (string) $value, (int) $time, '/', '', false, true);
        }
    }

    public static function setwithdomain($arg, $time, $domain): void
    {
        foreach ($arg as $key => $value) {
            setcookie((string) $key, (string) $value, (int) $time, '/', (string) $domain, false, true);
        }
    }

    public static function get($key)
    {
        return $_COOKIE[$key] ?? '';
    }
}
