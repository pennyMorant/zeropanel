<?php

namespace App\Utils;

use App\Models\{
    User,
    Node
};
use App\Services\{Config, ZeroConfig};
use App\Controllers\{
    LinkController,
    ConfController
};

class URL
{
    /**
     * 获取全部节点对象
     *
     * @param User  $user
     * @param mixed $sort  数值或数组
     * @param array $rules 节点筛选规则
     */
    public static function getNodes(User $user, $node_type, array $rules = []): \Illuminate\Database\Eloquent\Collection
    {
        $query = Node::query();
        if (is_array($node_type)) {
            $query->whereIn('node_type', $node_type);
        } else {
            $query->where('node_type', $node_type);
        }
        if (!$user->is_admin) {
            $group = ($user->node_group != 0 ? [0, $user->node_group] : [0]);
            $query->whereIn('node_group', $group)
                ->where('node_class', '<=', $user->class);
        }

        // 等级筛选
        if (isset($rules['content']['class']) && count($rules['content']['class']) > 0) {
            $query->whereIn('node_class', $rules['content']['class']);
        }
        if (isset($rules['content']['noclass']) && count($rules['content']['noclass']) > 0) {
            $query->whereNotIn('node_class', $rules['content']['noclass']);
        }
        // 等级筛选 end

        $nodes = $query->where('status', '1')->orderBy('node_sort', 'desc')->orderBy('name')->get();


        return $nodes;
    }

    
    public static function getNew_AllItems(User $user, array $Rule): array
    {
        $emoji = (isset($Rule['emoji']) ? $Rule['emoji'] : false);

        switch ($Rule['type']) {
            case 'shadowsocks':
                $node_type = [1];
                break;
            case 'vmess':
                $node_type = [2];
                break;
            case 'trojan':
                $node_type = [4];
                break;
            case 'vless':
                $node_type = [3];
                break;
            default:
                $Rule['type'] = 'all';
                $node_type = [1, 2, 3, 4, 5];
                break;
        }

        // 获取节点
        $nodes = self::getNodes($user, $node_type, $Rule);

        $return_array = [];
        foreach ($nodes as $node) {
            if (isset($Rule['content']['regex']) && $Rule['content']['regex'] != '') {
                // 节点名称筛选
                if (                  
                    is_null(ConfController::getMatchProxy(
                        [
                            'remark' => $node->name
                        ],
                        [
                            'content' => [
                                'regex' => $Rule['content']['regex']
                            ]
                        ]
                    ))
                ) {
                    continue;
                }
            }
            
            if (in_array($node->node_type, [1, 2, 3, 4, 5])) {
                $node_type = [
                    1  => 'getShadowsocksConfig',     // SS
                    2 => 'getVmessConfig',           // V2Ray
                    4 => 'getTrojanConfig',          // Trojan
                    3 => 'getVlessConfig'
                ];
                $custom_config = $node->custom_config;
                $type = $node_type[$node->node_type];
                $item = $node->$type($user, $custom_config, $emoji);
                if (!is_null($item)) {
                    $return_array[] = $item;
                }
                continue;
            }
            // 其他类型单端口节点 End
        }

        return $return_array;
    }

    
    public static function get_NewAllUrl(User $user, array $Rule): string
    {
        $return_url = '';

        $items = URL::getNew_AllItems($user, $Rule);
        foreach ($items as $item) {
            if ($item['type'] == 'vmess' || $item['type'] == 'vless') {
                $out = LinkController::getListItem($item, 'v2rayn');
            } else {
                $out = LinkController::getListItem($item, $Rule['type']);
            }
            if (!is_null($out)) {
                $return_url .= $out . PHP_EOL;
            }
        }
        return $return_url;
    }

    /**
     * 获取 SS URL
     */
    public static function getShadowsocksURL(User $user, Node $node, bool $emoji = false): string
    {
        $node_config = $node->getShadowsocksConfig($user, $node->custom_config, $emoji);
        $method = [
            '2022-blake3-aes-128-gcm',
            '2022-blake3-aes-256-gcm',
            '2022-blake3-chacha20-poly1305',
        ];
        if (!in_array($node_config['method'], $method)) {
            $url = sprintf(
                'ss://%s@[%s]:%s#%s',
                base64_encode($node_config['method'] . ':' . $node_config['passwd']),
                $node_config['address'],
                $node_config['port'],
                rawurlencode($node_config['remark'])
            );
        } else {
            $url = sprintf(
                'ss://%s:%s@[%s]:%s#%s',
                $node_config['method'],
                $node_config['server_key'] . ':' . $node_config['passwd'],
                $node_config['address'],
                $node_config['port'],
                rawurlencode($node_config['remark'])
            );
        }
        return $url;
    }


    /**
     * 获取 Vmess URL
     */
    public static function getVmessURL(User $user, Node $node, bool $emoji = false): string
    {
        $node_config = $node->getVmessConfig($user, $node->custom_config, $emoji);
        $url= sprintf(
            'vmess://%s@%s:%d?encryption=auto&host=%s&path=%s&flow=%s&security=%s&sni=%s&serviceName=%s&headerType=%s&type=%s#%s',
            $node_config['uuid'],
            $node_config['address'],
            $node_config['port'],
            $node_config['host'],
            $node_config['path'],
            $node_config['flow'],
            $node_config['security'],
            $node_config['sni'],
            rawurlencode($node_config['servicename']),
            $node_config['headertype'],
            $node_config['net'],
            rawurlencode($node_config['remark'])
        );
        return $url;
    }

    /**
     * 获取 VLESS URL
     */
    public static function getVlessURL(User $user, Node $node, bool $emoji = false): string
    {
        $node_config = $node->getVlessConfig($user, $node->custom_config, $emoji);

        $url= sprintf(
            'vmess://%s@%s:%d?encryption=none&host=%s&path=%s&flow=%s&security=%s&sni=%s&serviceName=%s&headerType=%s&type=%s#%s',
            $node_config['uuid'],
            $node_config['address'],
            $node_config['port'],
            $node_config['host'],
            $node_config['path'],
            $node_config['flow'],
            $node_config['security'],
            $node_config['sni'],
            rawurlencode($node_config['servicename']),
            $node_config['headertype'],
            $node_config['net'],
            rawurlencode($node_config['remark'])
        );
        return $url;
    }
    
    /**
     * 获取 Trojan URL
     */
    public static function getTrojanURL(User $user, Node $node, bool $emoji = false): string
    {
        $node_config = $node->getTrojanConfig($user, $node->custom_config, $emoji);
        $url= sprintf(
            'trojan://%s@%s:%s?flow=%s&security=%s&sni=%s#%s',
            $node_config['uuid'],
            $node_config['address'],
            $node_config['port'],
            $node_config['flow'],
            $node_config['security'],
            $node_config['sni'],
            rawurlencode($node_config['remark'])
        );
        return $url;
    }
}
