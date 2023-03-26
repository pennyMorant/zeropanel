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
    public static function getNodes(User $user, $sort, array $rules = []): \Illuminate\Database\Eloquent\Collection
    {
        $query = Node::query();
        if (is_array($sort)) {
            $query->whereIn('sort', $sort);
        } else {
            $query->where('sort', $sort);
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
                $sort = [0];
                break;
            case 'vmess':
                $sort = [11];
                break;
            case 'trojan':
                $sort = [14];
                break;
            case 'vless':
                $sort = [15];
                break;
            default:
                $Rule['type'] = 'all';
                $sort = [0, 11, 14, 15];
                break;
        }

        // 获取节点
        $nodes = self::getNodes($user, $sort, $Rule);

        $return_array = [];
        foreach ($nodes as $node) {
            if (isset($Rule['content']['regex']) && $Rule['content']['regex'] != '') {
                // 节点名称筛选
                if (
                    ConfController::getMatchProxy(
                        [
                            'remark' => $node->name
                        ],
                        [
                            'content' => [
                                'regex' => $Rule['content']['regex']
                            ]
                        ]
                    ) === null
                ) {
                    continue;
                }
            }
            
            if (in_array($node->sort, [0, 11, 14, 15])) {
                $node_type = [
                    0  => 'getShadowsocksConfig',     // SS
                    11 => 'getVmessConfig',           // V2Ray
                    14 => 'getTrojanConfig',          // Trojan
                    15 => 'getVlessConfig'
                ];
                $custom_config = $node->custom_config;
                $type = $node_type[$node->sort];
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

    /**
     * 获取全部节点 Url
     *
     * ```
     *  $Rule = [
     *      'type'    => 'ss | ssr | vmess',
     *      'emoji'   => false,
     *      'is_mu'   => 1,
     *      'content' => [
     *          'noclass' => [0, 1, 2],
     *          'class'   => [0, 1, 2],
     *          'regex'   => '.*香港.*HKBN.*',
     *      ]
     *  ]
     * ```
     *
     * @param User  $user 用户
     * @param array $Rule 节点筛选规则
     */
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
            if ($out !== null) {
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
        $shadowsocks = base64_encode($node_config['method'] . ':' . $node_config['passwd']) . '@[' . $node_config['address'] . ']:' . $node_config['port'];
        return 'ss://'. $shadowsocks . '#' . rawurlencode($node_config['remark']);
    }


    /**
     * 获取 Vmess URL
     */
    public static function getVmessURL(User $user, Node $node, bool $emoji = false): string
    {
        $node_config = $node->getVmessConfig($user, $node->custom_config, $emoji);
        $vmess = $node_config['uuid'] . '@' . $node_config['address'] . ':' . $node_config['port'] . '?encryption=auto&host=' . $node_config['host'] . 
                '&path=' . $node_config['path'] . '&flow=' . $node_config['flow'] . '&security=' . $node_config['security'] . 
                '&sni=' . $node_config['sni'] . '&serviceName=' . $node_config['servicename'] . '&headerType=' . $node_config['headertype'] . 
                '&type=' . $node_config['net']  . '#' . rawurlencode($node_config['remark']);
        return 'vmess://' . $vmess;
    }

    /**
     * 获取 VLESS URL
     */
    public static function getVlessURL(User $user, Node $node, bool $emoji = false): string
    {
        $node_config = $node->getVlessConfig($user, $node->custom_config, $emoji);
        //$item['serviceName'] = $item['servicename'];
        $vless = $node_config['uuid'] . '@' . $node_config['address'] . ':' . $node_config['port'] . '?encryption=none&flow=' . 
                $node_config['flow'] . '&security=' . $node_config['security'] . '&sni=' . $node_config['sni'] . '&host=' . $node_config['host'] . 
                '&type=' . $node_config['net'] . '#' . rawurlencode($node_config['remark']);
        return 'vless://' . $vless;
    }
    
    /**
     * 获取 Trojan URL
     */
    public static function getTrojanURL(User $user, Node $node, bool $emoji = false): string
    {
        $node_config = $node->getTrojanConfig($user, $node->custom_config, $emoji);
        $trojan = $node_config['uuid'] . '@' . $node_config['address'] . ':' . $node_config['port'] . '?flow=' . 
                $node_config['flow'] . '&security=' . $node_config['security'] . '&sni=' . $node_config['sni'] . '#' . rawurlencode($node_config['remark']);
        return 'trojan://' . $trojan;
    }
}
