<?php

namespace App\Clients;

use App\Models\Node;
use App\Utils\Tools;
use App\Models\Setting;

class Shadowrocket
{
    public $flag = 'shadowrocket';
    private $user;
    private $servers;

    public function __construct($user, $servers)
    {
        $this->user = $user;
        $this->servers = $servers;
    }

    public function handle()
    {
        $user = $this->user;
        $servers = $this->servers;
        $uri = '';

        $upload = round($user->u / (1024*1024*1024), 2);
        $download = round($user->d / (1024*1024*1024), 2);
        $totalTraffic = round($user->transfer_enable / (1024*1024*1024), 2);
        $expiredDate = $user->class_expire;
        $webName = Setting::obtain('website_name');
        $uri .= "STATUS=🚀↑:{$upload}GB,↓:{$download}GB,TOT:{$totalTraffic}GB💡Expires:{$expiredDate}\r\nREMARKS={$webName}\r\n";

        foreach ($servers as $server) {
            $buildMethod = 'build' . ucfirst($server['type']);
            if (method_exists($this, $buildMethod)) {
                $uri .= $this->$buildMethod($server);
            }
        }

        return base64_encode($uri);
    }

    public static function buildShadowsocks($server)
    {
        $ip_type = Tools::isIP($server['address']);
        $address = ($ip_type === 'v6' ? '[%s]' : '%s');
        if (Node::getShadowsocksSupportMethod($server['method'])) {
            $url = sprintf(
                "ss://%s@{$address}:%d#%s\n",
                base64_encode($server['method'] . ':' . $server['passwd']),
                $server['address'],
                $server['port'],
                rawurlencode($server['remark'])
            );
        } else {
            $url = sprintf(
                "ss://%s@{$address}:%d#%s\n",
                base64_encode($server['method'] . ':' . $server['server_psk'] . ':' . $server['passwd']),
                $server['address'],
                $server['port'],
                rawurlencode($server['remark'])
            );
        }
        return $url;
    }

    public static function buildVmess($server)
    {
        $ip_type = Tools::isIP($server['address']);
        $address = ($ip_type === 'v6' ? '[%s]' : '%s');
        $url= sprintf(
            "vmess://%s@{$address}:%d?encryption=auto&host=%s&path=%s&security=%s&sni=%s&serviceName=%s&headerType=%s&type=%s#%s\n",
            $server['uuid'],
            $server['address'],
            $server['port'],
            rawurlencode($server['host']),
            rawurlencode($server['path']),
            $server['security'],
            $server['sni'],
            rawurlencode($server['servicename']),
            $server['headertype'],
            $server['net'],
            rawurlencode($server['remark'])
        );

        return $url;
    }

    public static function buildVless($server)
    {
        $ip_type = Tools::isIP($server['address']);
        $address = ($ip_type === 'v6' ? '[%s]' : '%s');
        $url= sprintf(
            "vless://%s@{$address}:%d?encryption=none&pbk=%s&sid=%s&fp=%s&host=%s&path=%s&flow=%s&security=%s&sni=%s&serviceName=%s&headerType=%s&type=%s#%s\n",
            $server['uuid'],
            $server['address'],
            $server['port'],
            $server['pbk'],
            $server['sid'],
            $server['fp'],
            rawurlencode($server['host']),
            rawurlencode($server['path']),
            $server['flow'],
            $server['security'],
            $server['sni'],
            rawurlencode($server['servicename']),
            $server['headertype'],
            $server['net'],
            rawurlencode($server['remark'])
        );

        return $url;
    }

    public static function buildTrojan($server)
    {
        $ip_type = Tools::isIP($server['address']);
        $address = ($ip_type === 'v6' ? '[%s]' : '%s');
        $url = sprintf(
            "trojan://%s@{$address}:%d?flow=%s&security=%s&sni=%s#%s\n",
            $server['uuid'],
            $server['address'],
            $server['port'],
            $server['flow'],
            $server['security'],
            $server['sni'],
            rawurlencode($server['remark'])
        );
        return $url;
    }

    public static function buildHysteria($server)
    {
        $ip_type = Tools::isIP($server['address']);
        $address = ($ip_type === 'v6' ? '[%s]' : '%s');
        $url = sprintf(
            "hysteria://{$address}:%d?protocol=%s&auth=%s&peer=%s&upmbps=%s&downmbps=%s&obfs=%s&obfsParam=%s#%s\n",
            $server['address'],
            $server['port'],
            $server['protocol'],
            $server['auth'],
            $server['peer'],
            $server['upmbps'],
            $server['downmbps'],
            $server['obfs'],
            $server['obfsParam'],
            rawurlencode($server['remark'])
        );
        return $url;
    }
}
