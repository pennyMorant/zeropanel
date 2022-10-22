<?php

namespace App\Models;

/**
 * Node Model
 *
 * @property-read   int     $id         id
 * @property        string  $name       Display name
 * @property        int     $type       If node display @todo Correct column name and type
 * @property        string  $server     Domain
 * @property        string  $method     Crypt method @deprecated
 * @property        string  $info       Infomation
 * @property        string  $status     Status description
 * @property        int     $sort       Node type @todo Correct column name to `type`
 * @property        int     $custom_method  Customs node crypt @deprecated
 * @property        float   $traffic_rate   Node traffic rate
 * @todo More property
 * @property        bool    $online     If node is online
 * @property        bool    $gfw_block  If node is blocked by GFW
 */

use App\Services\Config;
use App\Utils\{Tools, URL};

class Node extends Model
{
    protected $connection = 'default';

    protected $table = 'node';

    protected $casts = [
        'node_speedlimit' => 'float',
        'traffic_rate'    => 'float',
        'sort'            => 'int',
        'type'            => 'bool',
        'node_heartbeat'  => 'int',
    ];

    /**
     * 节点是否显示和隐藏
     */
    public function type(): string
    {
        return $this->type ? '显示' : '隐藏';
    }

    /**
     * 节点类型
     */
    public function sort(): string
    {
        switch ($this->sort) {
            case 0:
                $sort = 'Shadowsocks';
                break;
            case 11:
                $sort = 'VMESS';
                break;
            case 13:
                $sort = 'Shadowsocks - V2Ray-Plugin&Obfs';
                break;
            case 14:
                $sort = 'TROJAN';
                break;
            case 15:
                $sort = 'VLESS';
                break;
            default:
                $sort = '系统保留';
        }
        return $sort;
    }
    
    /**
     * 节点最后活跃时间
     */
    public function node_heartbeat(): string
    {
        return date('Y-m-d H:i:s', $this->node_heartbeat);
    }
    
    public function getLastNodeInfoLog()
    {
        $log = NodeInfoLog::where('node_id', $this->id)->orderBy('id', 'desc')->first();
        if ($log == null) {
            return null;
        }
        return $log;
    }

    public function getNodeUptime()
    {
        $log = $this->getLastNodeInfoLog();
        if ($log == null) {
            return '暂无数据';
        }
        return Tools::secondsToTime((int) $log->uptime);
    }

    public function getNodeUpRate()
    {
        $log = NodeOnlineLog::where('node_id', $this->id)->where('log_time', '>=', time() - 86400)->count();
        return $log / 1440;
    }

    public function getNodeLoad()
    {
        $log = NodeInfoLog::where('node_id', $this->id)->orderBy('id', 'desc')->whereRaw('`log_time`%1800<60')->limit(48)->get();
        return $log;
    }

    public function getNodeAlive()
    {
        $log = NodeOnlineLog::where('node_id', $this->id)->orderBy('id', 'desc')->whereRaw('`log_time`%1800<60')->limit(48)->get();
        return $log;
    }

     /**
     * 获取节点 5 分钟内最新的在线人数
     */
    public function get_node_online_user_count(): int
    {
        if (in_array($this->sort, [9])) {
            return -1;
        }
        $log = NodeOnlineLog::where('node_id', $this->id)->where('log_time', '>', time() - 300)->orderBy('id', 'desc')->first();
        if ($log == null) {
            return 0;
        }
        return $log->online_user;
    }

    public function getTrafficFromLogs()
    {
        $id = $this->attributes['id'];

        $traffic = TrafficLog::where('node_id', $id)->sum('u') + TrafficLog::where('node_id', $id)->sum('d');

        if ($traffic == 0) {
            return '暂无数据';
        }

        return Tools::flowAutoShow($traffic);
    }

    /**
     * 获取节点在线状态
     *
     * @return int 0 = new node OR -1 = offline OR 1 = online
     */
    public function get_node_online_status(): int
    {
         // 类型 9 或者心跳为 0
        if ($this->node_heartbeat == 0 || in_array($this->sort, [9])) {
            return 0;
        }
        return $this->node_heartbeat + 300 > time() ? 1 : -1;
    }

    /**
     * 获取节点最新负载
     */
    public function get_node_latest_load(): int
    {
        $log = NodeInfoLog::where('node_id', $this->id)->where('log_time', '>', time() - 300)->orderBy('id', 'desc')->first();
        if ($log == null) {
            return -1;
        }
        return (explode(' ', $log->load))[0] * 100;
    }

    /**
     * 获取节点最新负载文本信息
     */
    public function get_node_latest_load_text(): string
    {
        $load = $this->get_node_latest_load();
        return $load == -1 ? 'N/A' : $load . '%';
    }

    /**
     * 获取节点速率文本信息
     */
    public function get_node_speedlimit(): string
    {
        if ($this->node_speedlimit == 0.0) {
            return 0;
        } elseif ($this->node_speedlimit >= 1024.00) {
            return round($this->node_speedlimit / 1024.00, 1) . 'Gbps';
        } else {
            return $this->node_speedlimit . 'Mbps';
        }
    }
    
    /**
     * 节点是在线的
     */
    public function isNodeOnline(): ?bool
    {
        if ($this->node_heartbeat === 0) {
            return false;
        }
        return $this->node_heartbeat > time() - 300;
    }

    /**
     * 节点流量已耗尽
     */
    public function isNodeTrafficOut(): bool
    {
        return !($this->node_bandwidth_limit == 0 || $this->node_bandwidth < $this->node_bandwidth_limit);
    }

    /**
     * 节点是可用的，即流量未耗尽并且在线
     */
    public function isNodeAccessable(): bool
    {
        return $this->isNodeTrafficOut() == false && $this->isNodeOnline() == true;
    }

    /**
     * 更新节点 IP
     *
     * @param string $server_name
     */
    public function changeNodeIp(string $server_name): bool
    {
        $ip = gethostbyname($server_name);
        if ($ip == '') {
            return false;
        }
        $this->node_ip = $ip;
        return true;
    }

    /**
     * 获取节点 IP
     */
    public function getNodeIp(): string
    {
        $node_ip_str   = $this->node_ip;
        $node_ip_array = explode(',', $node_ip_str);
        return $node_ip_array[0];
    }

    /**
     * 获取出口地址 | 用于节点IP获取的地址
     */
    public function getOutAddress(): string
    {
        return $this->server;
    }


    /**
     * 获取 SS 节点
     */
    public function getShadowsocksItem(User $user, $custom_config, bool $emoji = false): array
    {
        $custom_configs = json_decode($custom_config, true);
        $item['remark']   = ($emoji ? Tools::addEmoji($this->name) : $this->name);
        $item['type']     = 'ss';
        $item['passwd']   = $user->passwd;
        $item['method']   = $custom_configs['mu_encryption'];
        $item['address']  = $this->server;
        $item['port']     = $custom_configs['offset_port_user'];
        $item['class']    = $this->node_class;

        return $item;
    }

    /**
     * 获取 V2Ray 节点
     */
    public function getVmessItem(User $user, $custom_config, bool $emoji = false): array
    {
        $custom_configs = json_decode($custom_config, true);
        $item['v']      = '2';      
        $item['type']   = 'vmess';
        $item['remark'] = ($emoji ? Tools::addEmoji($this->name) : $this->name);
        $item['id']     = $user->uuid;
        $item['class']  = $this->node_class;        
        $item['add'] = $this->server;
        $item['port'] = $custom_configs['offset_port_user'];
        $item['aid'] = $custom_configs['alter_id'];
        $item['net'] = $custom_configs['network'];
        $item['tls'] = $custom_configs['security'] ?? '';
        $item['path'] = $custom_configs['path'] ?? '';
        $item['host'] = $custom_configs['host'] ?? '';
        $item['sni'] = $custom_configs['host'] ?? '';
        $item['headertype'] = $custom_configs['header']['type'] ?? '';
        $item['servicename'] = $custom_configs['servicename'] ?? '';
        $item['verify_cert'] = $custom_configs['verify_cert'] ?? 'true';
        return $item;
    }

    /**
     * Trojan 节点
     */
    public function getTrojanItem(User $user, $custom_config,  bool $emoji = false): array
    {
        $custom_configs = json_decode($custom_config, true);
        $item['remark']   = ($emoji ? Tools::addEmoji($this->name) : $this->name);
        $item['type']     = 'trojan';
        $item['passwd']   = $user->uuid;
        $item['address'] = $this->server;
        $item['port'] = $custom_configs['offset_port_user'];
        $item['host'] = $custom_configs['host'] ?? '';
        if (array_key_exists('grpc', $custom_configs)) {
            $item['net'] = $custom_configs['grpc'] == 1 ? 'grpc' : '';
        } else {
            $item['net'] = '';
        }
        $item['servicename'] = $custom_configs['servicename'] ?? '';
        if (array_key_exists('enable_xtls', $custom_configs)) {
            $item['tls'] = $custom_configs['enable_xtls'] == 1 ? 'xtls' : 'tls';
        } else {
            $item['tls'] = 'tls';
        }
        $item['flow'] = $custom_configs['flow'] ?? '';
        
        return $item;
    }
}
