<?php

namespace App\Services;

class ZeroConfig
{
    public static function get($key)
    {
        global $_ZC;
        return $_ZC[$key];
    }

    public static function getPublicSetting()
    {
        global $_ZC;
        return $_ZC;
    }
}
