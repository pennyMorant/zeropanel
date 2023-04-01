<?php

namespace App\Services\Auth;
use App\Models\User;

abstract class Base
{
    abstract public function login($uid, $time);

    abstract public function logout();

    abstract public function getUser(): User;
}
