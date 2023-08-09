<?php

namespace App\Clients;

use App\Models\Setting;
use App\Models\Node;
use App\Utils\Tools;

class Surfboard
{
    public $flag = 'surfboard';
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
        $website_name = Setting::obtain('website_name');
        header("content-disposition:attachment;filename*=UTF-8''".rawurlencode($website_name).".conf");
        $proxies = '';
        $proxyGroup = '';

        foreach ($servers as $server) {
            $type = $server['type'];
            $buildMethod = 'build' . ucfirst($type);
            $isValidServer = true;
        
            if ($type === 'shadowsocks' && !Node::getShadowsocksSupportMethod($server['method'])) {
                $isValidServer = false;
            }
        
            if ($isValidServer && method_exists($this, $buildMethod)) {
                $proxies .= $this->$buildMethod($server);
                $proxyGroup .= $server['remark'] . ', ';
            }
        }

        $surge_config = dirname(__FILE__,3).'/resources/conf/surfboard/surfboard.conf';
        $config = file_get_contents("$surge_config");

        $sub_url = Setting::obtain('subscribe_address_url')."/api/v1/client/subscribe?token={$user->subscription_token}";
        $sub_domain = $_SERVER['HTTP_HOST'];
        $upload = round($user->u / (1024*1024*1024), 2);
        $download = round($user->d / (1024*1024*1024), 2);
        $useTraffic = $upload + $download;
        $totalTraffic = round($user->transfer_enable / (1024*1024*1024), 2);
        $expireDate = substr($user->class_expire, 0, 10);
        $subscribeInfo = "title={$website_name}订阅信息, content=上传流量：{$upload}GB\\n下载流量:{$download}GB\\n剩余流量:{$useTraffic}GB\\n套餐流量:{$totalTraffic}GB\\n到期时间:{$expireDate}";

        $search = ['$subs_link', '$subs_domain', '$proxies', '$proxy_group', '$subscribe_info'];
        $replace = [$sub_url, $sub_domain, $proxies, rtrim($proxyGroup, ', '), $subscribeInfo];

        $config = str_replace($search, $replace, $config);

        return $config;
    }

    public static function buildShadowsocks($server)
    {   
        $uri = sprintf(
            "%s = ss, %s, %d, encrypt-method=%s, password=%s, udp-relay=true\n",
            $server['remark'],
            $server['address'],
            $server['port'],
            $server['method'],
            $server['passwd']
        );
        return $uri;
    }

    public static function buildVmess($server)
    {
        $vmess_params['ws'] = $server['net'] == 'ws' ? 'true' : 'false';
        $vmess_params['tls'] = $server['security'] == 'tls' ? 'true' : 'false';
        
        $uri = sprintf(
            "%s = vmess, %s, %d, username=%s, ws=%s, ws-path=%s, ws-header=host:%s, tls=%s, sni=%s\n",
            $server['remark'],
            $server['address'],
            $server['port'],
            $server['uuid'],
            $vmess_params['ws'],
            $server['path'],
            $server['host'],
            $vmess_params['tls'],
            $server['sni']
        );

        return $uri;
    }

    public static function buildTrojan($server)
    {
        $uri = sprintf(
            "%s = trojan, %s, %d, password=%s, sni=%s\n",
            $server['remark'],
            $server['address'],
            $server['port'],
            $server['uuid'],
            $server['sni']
        );

        return $uri;
    }
}