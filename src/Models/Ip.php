<?php

namespace App\Models;

/**
 * Ip Model
 */
class Ip extends Model
{
    protected $connection = 'default';
    protected $table = 'alive_ip';

    /**
     * 用户
     */
    public function user(): ?User
    {
        return User::find($this->userid);
    }

    /**
     * 节点
     */
    public function node(): ?Node
    {
        return Node::find($this->nodeid);
    }
    
    public function getUserAliveIpCount()
    {
        $total_ip = IP::selectRaw('userid, COUNT(DISTINCT ip) AS count')
            ->whereRaw('created_at >= UNIX_TIMESTAMP() - 180')
            ->groupBy('userid')
            ->get()
            ->pluck('count', 'userid')
            ->toArray();
        return $total_ip;
    }

    public function ip()
    {
        return str_replace('::ffff:', '', $this->attributes['ip']);
    }
}