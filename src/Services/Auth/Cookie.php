<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Utils;
use App\Utils\Hash;
use function strval;
use function time;

class Cookie extends Base
{
    public function login($uid, $time)
    {
        $user = User::find($uid);
        $expire_in = time() + $time;
        $key = Hash::cookieHash($user->password, $expire_in);
        Utils\Cookie::set([
            'uid' => strval($uid),
            'email' => $user->email,
            'key' => $key,
            'ip' => Hash::ipHash($_SERVER['REMOTE_ADDR'], $uid, $expire_in),
            'expire_in' => strval($expire_in),
        ], $expire_in);
    }

    public function getUser(): User
    {
        $uid = Utils\Cookie::get('uid');
        $email = Utils\Cookie::get('email');
        $key = Utils\Cookie::get('key');
        $ipHash = Utils\Cookie::get('ip');
        $expire_in = Utils\Cookie::get('expire_in');

        $user = new User();
        $user->isLogin = false;

        if (is_null($uid)) {
            return $user;
        }

        if ($expire_in < time()) {
            return $user;
        }

        $user = User::find($uid);
        if (is_null($user)) {
            $user = new User();
            $user->isLogin = false;
            return $user;
        }

        if ($user->email !== $email) {
            $user = new User();
            $user->isLogin = false;
        }

        if (Hash::cookieHash($user->password, $expire_in) !== $key) {
            $user = new User();
            $user->isLogin = false;
            return $user;
        }

        $user->isLogin = true;
        return $user;
    }

    public function logout()
    {
        $time = time() - 1000;
        Utils\Cookie::set([
            'uid' => '',
            'email' => '',
            'key' => '',
        ], $time);
    }
}
