<?php

namespace App\Clients;

use App\Models\Setting;
use App\Models\Node;

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
        $appName = Setting::obtain('website_name');
        header("content-disposition:attachment;filename*=UTF-8''".rawurlencode($appName).".conf");
        $proxies = '';
        $proxyGroup = '';

        foreach ($servers as $server) {
            switch ($server['type']) {
                case 'shadowsocks':
                    $proxies .= self::buildShadowsocks($server);
                    $proxyGroup .= $server['remark'] . ', ';
                    break;
                case 'vmess':
                    $proxies .= self::buildVmess($server);
                    $proxyGroup .= $server['remark'] . ', ';
                    break;
                case 'trojan':
                    $proxies .= self::buildTrojan($server);
                    $proxyGroup .= $server['remark'] . ', ';
                    break;
            }
        }

        $surge_config = dirname(__FILE__,3).'/resources/conf/surfboard/surfboard.conf';
        $config = file_get_contents("$surge_config");

        $sub_url = Setting::obtain('website_url')."/api/v1/client/subscribe?token={$user->subscription_token}";
        $sub_domain = $_SERVER['HTTP_HOST'];
        $config = str_replace('$subs_link', $sub_url, $config);
        $config = str_replace('$subs_domain', $sub_domain, $config);
        $config = str_replace('$proxies', $proxies, $config);
        $config = str_replace('$proxy_group', rtrim($proxyGroup, ', '), $config);

        $upload = round($user->u / (1024*1024*1024), 2);
        $download = round($user->d / (1024*1024*1024), 2);
        $useTraffic = $upload + $download;
        $totalTraffic = round($user->transfer_enable / (1024*1024*1024), 2);
        $expireDate = $user->class_expire;
        $subscribeInfo = "title={$appName}订阅信息, content=上传流量：{$upload}GB\\n下载流量：{$download}GB\\n剩余流量：{$useTraffic}GB\\n套餐流量：{$totalTraffic}GB\\n到期时间：{$expireDate}";
        $config = str_replace('$subscribe_info', $subscribeInfo, $config);

        return $config;
    }

    public function buildShadowsocks($server)
    {
        if (Node::getShadowsocksSupportMethod($server['method'])) {
            $uri = sprintf(
                "%s = ss, %s, %d, encrypt-method=%s, password=%s, udp-relay=true\n",
                $server['remark'],
                $server['address'],
                $server['port'],
                $server['method'],
                $server['passwd']
            );
        }
        return $uri;
    }

    public function buildVmess($server)
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

    public function buildTrojan($server)
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