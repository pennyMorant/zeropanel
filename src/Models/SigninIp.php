<?php

namespace App\Models;

/**
 * Ip Model
 */
class SigninIp extends Model
{
    protected $connection = 'default';

    protected $table = 'signin_ip';

    protected $casts = [
        'type' => 'int',
    ];

    /**
     * 登录用户
     */
    public function user(): ?User
    {
        return User::find($this->userid);
    }

    /**
     * 登录时间
     */
    public function datetime(): string
    {
        return date('Y-m-d H:i:s', $this->datetime);
    }


    /**
     * 登录成功与否
     */
    public function type(): string
    {
        return $this->type == 0 ? '成功' : '失败';
    }
}