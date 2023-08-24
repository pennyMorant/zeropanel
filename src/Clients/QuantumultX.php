<?php

namespace App\Clients;

use App\Models\Node;
use App\Utils\Tools;

class QuantumultX
{
    public $flag = 'quantumult%20x';
    private $servers;
    private $user;

    public function __construct($user, $servers)
    {
        $this->user = $user;
        $this->servers = $servers;
    }

    public function handle()
    {
        $servers = $this->servers;
        $user = $this->user;
        $uri = '';
        header("subscription-userinfo: upload={$user->u}; download={$user->d}; total={$user->transfer_enable}; expire=".strtotime($user->class_expire));

        foreach ($servers as $server) {
            $type = $server['type'];
            $buildMethod = 'build' . ucfirst($type);
            $isValidServer = true;
        
            if ($type === 'shadowsocks' && !Node::getShadowsocksSupportMethod($server['method'])) {
                $isValidServer = false;
            }
        
            if ($isValidServer && method_exists($this, $buildMethod)) {
                $uri .= $this->$buildMethod($server);
            }
        }
        return base64_encode($uri);
    }

    public static function buildShadowsocks($server)
    {
        $ip_type = Tools::isIP($server['address']);
        $address = ($ip_type === 'v6' ? '[%s]' : '%s');
        
        $uri = sprintf(
            "shadowsocks={$address}:%d, method=%s, password=%s, fast-open=false, udp-relay=true, tag=%s\r\n",
            $server['address'],
            $server['port'],
            $server['method'],
            $server['passwd'],
            $server['remark']
        );
        return $uri;
    }

    public static function buildVmess($server)
    {
        $ip_type = Tools::isIP($server['address']);
        $address = ($ip_type === 'v6' ? '[%s]' : '%s');
        if ($server['security'] == 'tls') {
            $obfs = 'over-tls';
        } else if ($server['security'] == 'tls' && $server['net'] == 'ws') {
            $obfs = 'wss';
        } else if ($server['net'] == 'ws') {
            $obfs = 'ws';
        } else {
            $obfs = '';
        }
        $uri = sprintf(
            "vmess={$address}:%d, method=chacha20-poly1305, password=%s, obfs=%s, obfs-host=%s, obfs-uri=%s, fast-open=false, udp-relay=false, tag=%s\r\n",
            $server['address'],
            $server['port'],
            $server['uuid'],
            $obfs,
            $server['host'],
            $server['path'],
            $server['remark']
        );

        return $uri;
    }

    public static function buildTrojan($server)
    {
        $ip_type = Tools::isIP($server['address']);
        $address = ($ip_type === 'v6' ? '[%s]' : '%s');
        $tlsVerify = $server['insecure'] ? 'false' : 'true';
        $uri = sprintf(
            "trojan={$address}:%d, password=%s, over-tls=true, tls-host=%s, tls-verification=%s, fast-open=false, udp-relay=false, tag=%s\r\n",
            $server['address'],
            $server['port'],
            $server['uuid'],
            $server['host'],
            $tlsVerify,
            $server['remark']
        );

        return $uri;
    }
}