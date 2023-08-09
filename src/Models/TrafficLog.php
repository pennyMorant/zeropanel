<?php


namespace App\Models;

use App\Utils\Tools;

class TrafficLog extends Model
{
    protected $connection = 'default';
    protected $table = 'user_traffic_log';

    public function node()
    {
        $node = Node::where('id', $this->node_id)->first();
        if (is_null($node)) {
            self::where('id', '=', $this->id)->delete();
            return null;
        }

        return $node;
    }
}
