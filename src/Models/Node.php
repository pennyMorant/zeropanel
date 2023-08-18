<?php

namespace App\Models;

use App\Utils\{Tools, URL};
use Illuminate\Database\Eloquent\Casts\Json;

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
                $status = <<<EOT
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" value="" id="node_status_{$this->id}" onclick="updateNodeStatus({$this->id})">
                            </div>
                        EOT;
                break;
            case 1:
                $status = <<<EOT
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" value="" id="node_status_{$this->id}" checked="checked" onclick="updateNodeStatus({$this->id})" />
                            </div>
                        EOT;
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
                $type = 'Vmess';
                break;
            case 4:
                $type = 'Trojan';
                break;
            case 3:
                $type = 'Vless';
                break;
            case 5:
                $type = 'Hysteria';
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
     * 获取 SS 节点
     */
    public function getShadowsocksConfig(User $user, $custom_config, bool $emoji = false): array
    {
        $config = [];
        if ($this->node_type == 1) {
        $custom_configs       = json_decode($custom_config, true);
        $config['remark']     = $emoji ? $this->getNodeFlag($this->node_flag) . $this->name : $this->name;
        $config['type']       = 'shadowsocks';
        $config['passwd']     = $user->passwd;
        $config['server_psk'] = $custom_configs['server_psk'] ?? '';
        $config['method']     = $custom_configs['ss_encryption'];
        $config['address']    = $this->server;
        $config['port']       = $custom_configs['offset_port_user'];
        $config['class']      = $this->node_class;
        }
        return $config;
    }

    /**
     * 获取 VMESS 节点
     */
    public function getVmessConfig(User $user, $custom_config, bool $emoji = false): array
    {
        $config = [];
        if ($this->node_type == 2) {
        $custom_configs        = json_decode($custom_config, true);
        $config['v']           = '2';
        $config['type']        = 'vmess';
        $config['remark']      = $emoji ? $this->getNodeFlag($this->node_flag) . $this->name : $this->name;
        $config['uuid']        = $user->uuid;
        $config['class']       = $this->node_class;
        $config['address']     = $this->server;
        $config['port']        = $custom_configs['offset_port_user'];
        $config['aid']         = $custom_configs['alter_id'] ?? 0;
        $config['net']         = $custom_configs['network'];
        $config['security']    = $custom_configs['security'] ?? 'none';
        $config['flow']        = $custom_configs['flow'] ?? '';
        $config['path']        = $custom_configs['path'] ?? '';
        $config['host']        = $custom_configs['host'] ?? '';
        $config['sni']         = $custom_configs['host'] ?? '';
        $config['headertype']  = $custom_configs['header']['type'] ?? '';
        $config['servicename'] = $custom_configs['service_name'] ?? '';
        $config['insecure']    = $custom_configs['insecure'] ?? 'true';
        }
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
        $config['port']        = $custom_configs['offset_port_user'];
        $config['aid']         = 0;
        $config['net']         = $custom_configs['network'];
        $config['security']    = $custom_configs['security'] ?? '';
        $config['flow']        = $custom_configs['reality_config']['flow'] ?? '';
        $config['path']        = $custom_configs['path'] ?? '';
        $config['host']        = $custom_configs['host'] ?? $custom_configs['reality_config']['server_names'][array_rand($custom_configs['reality_config']['server_names'])] ?? '';
        $config['sni']         = $custom_configs['host'] ?? '';
        $config['pbk']         = $custom_configs['reality_config']['public_key'] ?? '';
        $config['sid']         = $custom_configs['reality_config']['short_ids'][array_rand($custom_configs['reality_config']['short_ids'])] ?? '';
        //$config['servername']  = $custom_configs['reality_config']['servernames'][array_rand($custom_configs['reality_config']['servernames'])] ?? '';
        $config['fp']          = $custom_configs['reality_config']['fingerprint'] ?? '';
        $config['headertype']  = $custom_configs['header']['type'] ?? '';
        $config['servicename'] = $custom_configs['service_name'] ?? '';
        $config['insecure']    = $custom_configs['insecure'] ?? 'true';
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
        $config['port']     = $custom_configs['offset_port_user'];
        $config['sni']      = $custom_configs['host'] ?? '';
        $config['security'] = $custom_configs['security'] ?? 'tls';
        $config['flow']     = $custom_configs['flow'] ?? '';
        $config['net']      = $custom_configs['network'] ?? '';
        $config['insecure']    = $custom_configs['insecure'] ?? 'true';

        return $config;
    }

    public function getHysteriaConfig(User $user, $custom_config, bool $emoji = false): array
    {
        $custom_configs      = json_decode($custom_config, true);
        $config['type']      = 'hysteria';
        $config['remark']    = $emoji ? $this->getNodeFlag($this->node_flag) . $this->name : $this->name;
        $config['obfsParam'] = $custom_configs['obfs_param'] ?? '';
        $config['address']   = $this->server;
        $config['port']      = $custom_configs['offset_port_user'];
        $config['protocol']  = $custom_configs['protocol'] ?? 'udp';
        $config['peer']      = $custom_configs['peer'] ?? '';
        $config['upmbps']    = $custom_configs['upmbps'] ?? '10';
        $config['downmbps']  = $custom_configs['downmbps'] ?? '10';
        $config['alpn']      = $custom_configs['alpn'] ?? '';
        $config['obfs']      = $custom_configs['obfs'] ?? '';
        $config['auth']      = $user->passwd;

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
