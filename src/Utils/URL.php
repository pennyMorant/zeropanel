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

        $nodes = $query->where('type', '1')->orderBy('node_sort', 'desc')->orderBy('name')->get();


        return $nodes;
    }

    /**
     * 获取全部节点
     *
     * ```
     * $Rule = [
     *      'type'    => 'all | ss | ssr | vmess | trojan',
     *      'emoji'   => false,
     *      'is_mu'   => 1,
     *      'content' => [
     *          'noclass' => [0, 1, 2],
     *          'class'   => [0, 1, 2],
     *          'regex'   => '.*香港.*HKBN.*',
     *      ]
     * ]
     * ```
     *
     * @param User  $user 用户
     * @param array $Rule 节点筛选规则
     */
    public static function getNew_AllItems(User $user, array $Rule): array
    {
        $emoji = (isset($Rule['emoji']) ? $Rule['emoji'] : false);

        switch ($Rule['type']) {
            case 'ss':
                $sort = [0];
                break;
            case 'vmess':
                $sort = [11];
                break;
            case 'trojan':
                $sort = [14];
                break;
            default:
                $Rule['type'] = 'all';
                $sort = [0, 11, 14];
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
            // 筛选 End

            // 其他类型单端口节点
            if (in_array($node->sort, [0, 11, 14])) {
                $node_class = [
                    0  => 'getShadowsocksItem',     // SS
                    11 => 'getVmessItem',           // V2Ray
                    14 => 'getTrojanItem',          // Trojan
                ];
                $custom_config = $node->custom_config;
                $class = $node_class[$node->sort];
                $item = $node->$class($user, $custom_config, $emoji);
                if ($item != null) {
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
        $item = $node->getShadowsocksItem($user, $node->custom_config, $emoji);
        $return = 'ss://' . $item['method'] . ':' . $item['passwd'] . '@' . $item['address'] . ':' . $item['port'];
        return $return . '#' . rawurlencode($item['remark']);
    }


    /**
     * 获取 Vmess URL
     */
    public static function getVmessURL(User $user, Node $node, bool $emoji = false): string
    {
        $item = $node->getVmessItem($user, $node->custom_config, $emoji);
        $item['ps'] = $item['remark'];
        $item['serviceName'] = $item['servicename'];
        return 'vmess://' . base64_encode(
            json_encode($item, 320)
        );
    }
    
    /**
     * 获取 Trojan URL
     */
    public static function getTrojanURL(User $user, Node $node, bool $emoji = false): string
    {
        $server = $node->getTrojanItem($user, $node->custom_config, $emoji);
        $return = 'trojan://' . $server['passwd'] . '@' . $server['address'] . ':' . $server['port'];
        if ($server['host'] != $server['address']) {
            $return .= '?peer=' . $server['host'] . '&sni=' . $server['host'];
        }
        if($server['tls'] == "xtls"){
            $return.=("&security=".$server['tls']."&flow=".$server['flow']);
        }
        return $return . '#' . rawurlencode($server['remark']);
    }

    public static function getJsonObfs(array $item): string
    {
        $ss_obfs_list = Config::getSupportParam('ss_obfs');
        $plugin = '';
        if (in_array($item['obfs'], $ss_obfs_list)) {
            if (strpos($item['obfs'], 'http') !== false) {
                $plugin .= 'obfs-local --obfs http';
            } else {
                $plugin .= 'obfs-local --obfs tls';
            }
            if ($item['obfs_param'] != '') {
                $plugin .= '--obfs-host ' . $item['obfs_param'];
            }
        }
        return $plugin;
    }

    public static function getSurgeObfs(array $item): string
    {
        $ss_obfs_list = Config::getSupportParam('ss_obfs');
        $plugin = '';
        if (in_array($item['obfs'], $ss_obfs_list)) {
            if (strpos($item['obfs'], 'http') !== false) {
                $plugin .= ', obfs=http';
            } else {
                $plugin .= ', obfs=tls';
            }
            if ($item['obfs_param'] != '') {
                $plugin .= ', obfs-host=' . $item['obfs_param'];
            } else {
                $plugin .= ', obfs-host=wns.windows.com';
            }
        }
        return $plugin;
    }

    public static function cloneUser(User $user): User
    {
        return clone $user;
    }
}
