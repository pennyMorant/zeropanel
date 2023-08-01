<?php

namespace App\Services;

use App\Models\User;
use App\Models\Node;
use App\Models\Setting;
use App\Models\UserSubscribeLog;
use App\Utils\Tools;
use voku\helper\AntiXSS;

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
        
        $configMethodLookup = [
            1 => 'getShadowsocksConfig',
            2 => 'getVmessConfig',
            3 => 'getVlessConfig',
            4 => 'getTrojanConfig',
            5 => 'getHysteriaConfig',
        ];
        
        $servers = [];
        foreach ($nodes as $node) {
            $configMethod = $configMethodLookup[$node->node_type] ?? null;
            if ($configMethod && method_exists($node, $configMethod)) {
                $servers[] = $node->$configMethod($user, $node->custom_config, $emoji);
            }
        }

        if (Setting::obtain('enable_subscribe_extend')) {
            $remaining_traffic = Tools::flowAutoShow($user->transfer_enable - ($user->u + $user->d));
            $expire_date = substr($user->class_expire, 0, 10);
            $extend_node_1 = [
                'remark' => "剩余流量:{$remaining_traffic}",
            ];
            $extend_node_2 = [
                'remark' => "到期时间:{$expire_date}",
            ];
            array_unshift($servers, array_merge($servers[0], $extend_node_1));
            array_unshift($servers, array_merge($servers[0], $extend_node_2));
        }
        return $servers;
    }

    public function recordLog($user)
    {
        $log                     = new UserSubscribeLog();
        $log->user_id            = $user->id;
        $log->email              = $user->email;
        $log->request_ip         = $_SERVER['REMOTE_ADDR'];
        $log->created_at         = time();
        $antiXss                 = new AntiXSS();
        $log->request_user_agent = $antiXss->xss_clean($_SERVER['HTTP_USER_AGENT']);
        $log->save();
    }
}