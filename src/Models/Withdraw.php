<?php

namespace App\Models;

class Withdraw extends Model
{
    protected $connection = 'default';
    protected $table = 'withdraw_log';

    public function status()
    {
        switch ($this->status) {
            case 1:
                $status = '<div class="badge font-weight-bold badge-light-success fs-6">已完成</div>';
                break;
            case 0:
                $status = '<div class="badge font-weight-bold badge-light-warning fs-6">处理中</div>';
                break;
            case -1: 
                $status = '<div class="badge font-weight-bold badge-light-danger fs-6">已退回</div>';
                break;
        }
        return $status;
    }
}
