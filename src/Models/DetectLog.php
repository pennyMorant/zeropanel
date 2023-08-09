<?php

namespace App\Models;

class DetectLog extends Model
{
    protected $connection = 'default';

    protected $table = 'detect_log';


    /**
     * 节点
     */
    public function node(): ?Node
    {
        return Node::find($this->node_id);
    }

    /**
     * 规则
     */
    public function rule(): ?DetectRule
    {
        return DetectRule::find($this->list_id);
    }

}