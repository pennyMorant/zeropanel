<?php

namespace App\Services;

use App\Models\User;
use App\Models\Node;
use App\Models\Setting;

class NodeService
{
    public function getAllNodes(User $user)
    {
        $query = Node::query();
        if (!$user->is_admin) {
            $group = ($user->node_group != 0 ? [0, $user->node_group] : [0]);
            $query->whereIn('node_group', $group)
                ->where('node_class', '<=', $user->class);
        }
        $nodes = $query->where('status', '1')->orderBy('node_sort', 'desc')->orderBy('name')->get();
        $emoji = Setting::obtain('enable_subscribe_emoji');
        $servers = [];
        foreach ($nodes as $node) {
            switch ($node->node_type) {
                case 1:
                    $servers[] = $node->getShadowsocksConfig($user, $node->custom_config, $emoji);
                    break;
                case 2:
                    $servers[] = $node->getVmessConfig($user, $node->custom_config, $emoji);
                    break;
                case 3:
                    $servers[] = $node->getVlessConfig($user, $node->custom_config, $emoji);
                    break;
                case 4:
                    $servers[] = $node->getTrojanConfig($user, $node->custom_config, $emoji);
                    break;
                case 5:
                    $servers[] = $node->getHysteriaConfig($user, $node->custom_config, $emoji);
                    break;
            }
        }
        return $servers;
    }
}