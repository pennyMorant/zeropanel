<?php

namespace App\Services;
use App\Models\User;

class Auth
{

    protected $user;

    private static function getDriver()
    {
        return Factory::createAuth();
    }

    public static function login($uid, $time)
    {
        self::getDriver()->login($uid, $time);
    }

    public static function getUser(): User
    {
        global $user;
        if (is_null($user)) {
            $user = self::getDriver()->getUser();
        }
        return $user;
    }

    public static function logout()
    {
        self::getDriver()->logout();
    }
}
