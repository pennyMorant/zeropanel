<?php

namespace App\Models;

/**
 * Ticket Model
 */
class Ticket extends Model
{
    protected $connection = 'default';

    protected $table = 'ticket';

    /**
     * 用户
     */
    public function user(): ?User
    {
        return User::find($this->userid);
    }

    /**
     * 工单状态
     */
    public function status()
    {
        switch ($this->status) {
            case '1': 
                $status = '<div class="badge font-weight-bold badge-light-success fs-6">活跃</div>';
                break;
            case '0':
                $status = '<div class="badge font-weight-bold badge-light fs-6">关闭</div>';
                break;
        }
        return $status;
    }
}