<?php

namespace App\Clients;

use App\Models\Node;
use App\Utils\Tools;

class QuantumultX
{
    public $flag = 'quantumult20x';
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
            switch ($server['type']) {
                case 'shadowsocks':
                    if (Node::getShadowsocksSupportMethod($server['method'])) {
                        $uri .= self::buildShadowsocks($server);
                    }
                    break;
                case 'vmess':
                    $uri .= self::buildVmess($server);
                    break;
                case 'trojan':
                    $uri .= self::buildTrojan($server);
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
        $uri = sprintf(
            "vmess={$address}:%d, method=chacha20-poly1305, password=%s, obfs=%s, obfs-host=%s, obfs-uri=%s, fast-open=false, upd-relay=false, tag=%s\r\n",
            $server['address'],
            $server['port'],
            $server['uuid'],
            $server['net'],
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
        $uri = sprintf(
            "trojan={$address}:%d, password=%s, over-tls=true, tls-verification=true, fast-open=false, udp-relay=false, tag=%s\r\n",
            $server['address'],
            $server['port'],
            $server['uuid'],
            $server['remark']
        );

        return $uri;
    }
}