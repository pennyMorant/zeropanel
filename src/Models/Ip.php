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

    /**
     * 节点名
     */
    public function node_name(): string
    {
        if (is_null($this->node())) {
            return '节点已不存在';
        }
        return $this->node()->name;
    }

    /**
     * 时间
     */
    public function datetime(): string
    {
        return date('Y-m-d H:i:s', $this->datetime);
    }
    
    public function getUserAliveIpCount()
    {
        $total_ip = IP::selectRaw('userid, COUNT(DISTINCT ip) AS count')
            ->whereRaw('datetime', '>=', 'UNIX_TIMESTAMP(NOW()) - 180')
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