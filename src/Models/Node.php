<?php

namespace App\Models;

use App\Utils\{Tools, URL};

class Node extends Model
{
    protected $connection = 'default';

    protected $table = 'node';

    protected $casts = [
        'node_speedlimit' => 'float',
        'traffic_rate'    => 'float',
        'node_type'       => 'int',
        'status'          => 'bool',
        'node_heartbeat'  => 'int',
    ];

    /**
     * 节点是否显示和隐藏
     */
    public function status()
    {
        switch ($this->status) {
            case 0:
                $status = '<div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" value="" id="node_status_'.$this->id.'" onclick="updateNodeStatus('.$this->id.')" />
                            </div>';
                break;
            case 1:
                $status = '<div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" value="" id="node_status_'.$this->id.'" checked="checked" onclick="updateNodeStatus('.$this->id.')" />
                            </div>';
                break;
        }
        return $status;
    }

    /**
     * 节点类型
     */
    public function nodeType(): string
    {
        switch ($this->node_type) {
            case 1:
                $type = 'Shadowsocks';
                break;
            case 2:
                $type = 'VMESS';
                break;
            case 5:
                $type = 'Shadowsocks - V2Ray-Plugin&Obfs';
                break;
            case 4:
                $type = 'TROJAN';
                break;
            case 3:
                $type = 'VLESS';
                break;
            default:
                $type = '系统保留';
        }
        return $type;
    }

     /**
     * 获取节点 5 分钟内最新的在线人数
     */
    public function getNodeOnlineUserCount(): int
    {
        $log = NodeOnlineLog::where('node_id', $this->id)->where('created_at', '>', time() - 300)->orderBy('id', 'desc')->first();
        if (is_null($log)) {
            return 0;
        }
        return $log->online_user;
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
     * 更新节点 IP
     */
    public function changeNodeIp(string $server_name): bool
    {
        $result = dns_get_record($server_name, DNS_A + DNS_AAAA);
        $dns = [];
        if (count($result) > 0) {
            $dns = $result[0];
        }
        if (array_key_exists('ip', $dns)) {
            $ip = $dns['ip'];
        } elseif (array_key_exists('ipv6', $dns)) {
            $ip = $dns['ipv6'];
        } else {
            $ip = $server_name;
        }
        $this->node_ip = $ip;
        return true;
    }

    /**
     * 获取 SS 节点
     */
    public function getShadowsocksConfig(User $user, $custom_config, bool $emoji = false): array
    {
        $custom_configs       = json_decode($custom_config, true);
        $config['remark']     = $emoji ? $this->getNodeFlag($this->node_flag) . $this->name : $this->name;
        $config['type']       = 'shadowsocks';
        $config['passwd']     = $user->passwd;
        $config['server_psk'] = $custom_configs['server_psk'] ?? '';
        $config['method']     = $custom_configs['mu_encryption'];
        $config['address']    = $this->server;
        $config['port']       = $custom_configs['offset_port_user'] ?? $custom_configs['mu_port'];
        $config['class']      = $this->node_class;

        return $config;
    }

    /**
     * 获取 VMESS 节点
     */
    public function getVmessConfig(User $user, $custom_config, bool $emoji = false): array
    {
        $custom_configs        = json_decode($custom_config, true);
        $config['v']           = '2';
        $config['type']        = 'vmess';
        $config['remark']      = $emoji ? $this->getNodeFlag($this->node_flag) . $this->name : $this->name;
        $config['uuid']        = $user->uuid;
        $config['class']       = $this->node_class;
        $config['address']     = $this->server;
        $config['port']        = $custom_configs['offset_port_user'] ?? $custom_configs['v2_port'];
        $config['aid']         = $custom_configs['alter_id'];
        $config['net']         = $custom_configs['network'];
        $config['security']    = $custom_configs['security'] ?? '';
        $config['flow']        = $custom_configs['flow'] ?? '';
        $config['path']        = $custom_configs['path'] ?? '';
        $config['host']        = $custom_configs['host'] ?? '';
        $config['sni']         = $custom_configs['host'] ?? '';
        $config['headertype']  = $custom_configs['header']['type'] ?? '';
        $config['servicename'] = $custom_configs['servicename'] ?? '';
        $config['verify_cert'] = $custom_configs['verify_cert'] ?? 'true';
        return $config;
    }

    /**
     * 获取 VLESS 节点
     */
    public function getVlessConfig(User $user, $custom_config, bool $emoji = false): array
    {
        $custom_configs        = json_decode($custom_config, true);
        $config['type']        = 'vless';
        $config['remark']      = $emoji ? $this->getNodeFlag($this->node_flag) . $this->name : $this->name;
        $config['uuid']        = $user->uuid;
        $config['class']       = $this->node_class;
        $config['address']     = $this->server;
        $config['port']        = $custom_configs['offset_port_user'] ?? $custom_configs['v2_port'];
        $config['aid']         = $custom_configs['alter_id'];
        $config['net']         = $custom_configs['network'];
        $config['security']    = $custom_configs['security'] ?? '';
        $config['flow']        = $custom_configs['flow'] ?? '';
        $config['path']        = $custom_configs['path'] ?? '';
        $config['host']        = $custom_configs['host'] ?? '';
        $config['sni']         = $custom_configs['host'] ?? '';
        $config['headertype']  = $custom_configs['header']['type'] ?? '';
        $config['servicename'] = $custom_configs['servicename'] ?? '';
        $config['verify_cert'] = $custom_configs['verify_cert'] ?? 'true';
        return $config;
    }

    /**
     * Trojan 节点
     */
    public function getTrojanConfig(User $user, $custom_config,  bool $emoji = false): array
    {
        $custom_configs     = json_decode($custom_config, true);
        $config['remark']   = $emoji ? $this->getNodeFlag($this->node_flag) . $this->name : $this->name;
        $config['type']     = 'trojan';
        $config['uuid']     = $user->uuid;
        $config['address']  = $this->server;
        $config['port']     = $custom_configs['offset_port_user'] ?? $custom_configs['trojan_port'];
        $config['sni']      = $custom_configs['host'] ?? '';
        $config['security'] = $custom_configs['security'] ?? 'tls';
        $config['flow']     = $custom_configs['flow'] ?? '';
        if (isset($config['grpc']) == 1) {
            $config['net']         = 'grpc';
            $config['servicename'] = $custom_configs['servicename'] ?? '';
        } else {
            $config['net'] = 'tcp';
        }
        
        return $config;
    }

    public function getNodeFlag($country)
    {
        $country_emoji = [
            'united-states'  => '🇺🇸',
            'canada'         => '🇨🇦',
            'mexico'         => '🇲🇽',
            'argentina'      => '🇦🇷',
            'brazil'         => '🇧🇷',
            'united-kingdom' => '🇬🇧',
            'france'         => '🇫🇷',
            'germany'        => '🇩🇪',
            'ireland'        => '🇮🇪',
            'turkey'         => '🇹🇷',
            'russia'         => '🇷🇺',
            'hong-kong'      => '🇭🇰',
            'japan'          => '🇯🇵',
            'singapore'      => '🇸🇬',
            'taiwan'         => '🇹🇼',
            'south-korea'    => '🇰🇷',
            'australia'      => '🇦🇺',
            'thailand'       => '🇹🇭',
            'philippines'    => '🇵🇭',
            'malaysia'       => '🇲🇾',
        ];
        return $country_emoji[$country];
    }

    public static function getShadowsocksSupportMethod($method)
    {
        $method_2022 = [
            '2022-blake3-aes-128-gcm',
            '2022-blake3-aes-256-gcm',
            '2022-blake3-chacha20-poly1305',
        ];
        $method_old = [
            'aes-128-gcm',
            'aes-192-gcm',
            'aes-256-gcm',
            'chacha20-poly1305',
            'chacha20-ietf-poly1305',
            'xchacha20-ietf-poly1305',
        ];

        $support = in_array($method, $method_2022) ? false : (in_array($method, $method_old) ?? true);
        return $support;
    }
}
