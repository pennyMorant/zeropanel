<?php

namespace App\Clients;

use App\Models\Setting;
use App\Models\Node;
use App\Utils\Tools;

class Universal
{
    public $flag = 'universal';
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
        $appName = Setting::obtain('website_name');
        header("subscription-userinfo: upload={$user->u}; download={$user->d}; total={$user->transfer_enable}; expire={$user->class_expire}");
        header('profile-update-interval: 24');
        header("content-disposition:attachment;filename*=UTF-8''".rawurlencode($appName));
        header("profile-web-page-url:" . Setting::obtain('website_url'));

        $uri = '';
        
        foreach ($servers as $server) {
            switch ($server['type']) {
                case 'shadowsocks':
                    $uri .= self::buildShadowsocks($server);
                    break;
                case 'vmess':
                    $uri .= self::buildVmess($server);
                    break;
                case 'vless': 
                    $uri .= self::buildVless($server);
                    break;
                case 'trojan':
                    $uri .= self::buildTrojan($server);
                    break;
                case 'hysteria':
                    $uri .= self::buildHysteria($server);
                    break;
            }
        }
        return base64_encode($uri);
    }

    public function buildShadowsocks($server)
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

    public function buildVmess($server)
    {
        $ip_type = Tools::isIP($server['address']);
        $address = ($ip_type === 'v6' ? '[%s]' : '%s');
        $url = sprintf(
            "vmess://%s@{$address}:%d?encryption=auto&host=%s&path=%s&flow=%s&security=%s&sni=%s&serviceName=%s&headerType=%s&type=%s#%s\n",
            $server['uuid'],
            $server['address'],
            $server['port'],
            $server['host'],
            $server['path'],
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

    public function buildVless($server)
    {
        $ip_type = Tools::isIP($server['address']);
        $address = ($ip_type === 'v6' ? '[%s]' : '%s');
        $url = sprintf(
            "vless://%s@{$address}:%d?encryption=auto&host=%s&path=%s&flow=%s&security=%s&sni=%s&serviceName=%s&headerType=%s&type=%s#%s\n",
            $server['uuid'],
            $server['address'],
            $server['port'],
            $server['host'],
            $server['path'],
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

    public function buildTrojan($server)
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

    public function buildHysteria($server)
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