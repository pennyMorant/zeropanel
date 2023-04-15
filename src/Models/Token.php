<?php

namespace App\Models;

class Token extends Model
{
    protected $connection = 'default';
    protected $table = 'user_token';

    public static function createToken($user, $length, $type)
    {
        $token              = new Token;
        $token->token       = bin2hex(openssl_random_pseudo_bytes($length / 2));
        $token->user_id     = $user->id;
        $token->create_time = time();
        $token->expire_time = time() + 60 * 30;
        $token->type        = $type;
        $token->save();
        return $token->token;
    }
}
