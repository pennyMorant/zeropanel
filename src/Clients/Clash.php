<?php

namespace App\Clients;

use Symfony\Component\Yaml\Yaml;
use App\Models\Setting;
use App\Models\Node;

class Clash
{
    public $flag = 'clash';
    private $user;
    private $servers;

    public function __construct($user, $servers)
    {
        $this->user = $user;
        $this->servers = $servers;
    }

    public function  handle()
    {
        $user = $this->user;
        $servers = $this->servers;
        $appName = Setting::obtain('website_name');
        header("subscription-userinfo: upload={$user->u}; download={$user->d}; total={$user->transfer_enable}; expire=".strtotime($user->class_expire));
        header('profile-update-interval: 24');
        header("content-disposition:attachment;filename*=UTF-8''".rawurlencode($appName).".yaml");
        header("profile-web-page-url:" . Setting::obtain('website_url'));
        $clash_config = dirname(__FILE__,3).'/resources/conf/clash/clash.yaml';
        $config = Yaml::parseFile($clash_config);
        $proxy = [];
        $proxies = [];

        foreach ($servers as $server) {
            switch ($server['type']) {
                case 'shadowsocks':
                    if (Node::getShadowsocksSupportMethod($server['method'])) {
                        array_push($proxy, self::buildShadowsocks($server));
                        array_push($proxies, $server['remark']);
                    }
                    break;
                case 'vmess':
                    array_push($proxy, self::buildVmess($user->uuid, $server));
                    array_push($proxies, $server['remark']);
                    break;
                case 'trojan':
                    array_push($proxy, self::buildTrojan($user->uuid, $server));
                    array_push($proxies, $server['remark']);
                    break;

            }
        }
            $config['proxies'] = array_merge($config['proxies'] ? $config['proxies'] : [], $proxy);
            foreach ($config['proxy-groups'] as $k => $v) {
                if (!is_array($config['proxy-groups'][$k]['proxies'])) $config['proxy-groups'][$k]['proxies'] = [];
                $isFilter = false;
                foreach ($config['proxy-groups'][$k]['proxies'] as $src) {
                    foreach ($proxies as $dst) {
                        if (!$this->isRegex($src)) continue;
                        $isFilter = true;
                        $config['proxy-groups'][$k]['proxies'] = array_values(array_diff($config['proxy-groups'][$k]['proxies'], [$src]));
                        if ($this->isMatch($src, $dst)) {
                            array_push($config['proxy-groups'][$k]['proxies'], $dst);
                        }
                    }
                    if ($isFilter) continue;
                }
                if ($isFilter) continue;
                $config['proxy-groups'][$k]['proxies'] = array_merge($config['proxy-groups'][$k]['proxies'], $proxies);
            }

            $config['proxy-groups'] = array_filter($config['proxy-groups'], function($group) {
                return $group['proxies'];
            });
            $config['proxy-groups'] = array_values($config['proxy-groups']);
            // Force the current subscription domain to be a direct rule
            $subsDomain = $_SERVER['HTTP_HOST'];
            if ($subsDomain) {
                array_unshift($config['rules'], "DOMAIN,{$subsDomain},DIRECT");
            }

            $yaml = Yaml::dump($config, 2, 4, Yaml::DUMP_EMPTY_ARRAY_AS_SEQUENCE);
            return $yaml;
    }

    public static function buildShadowsocks($server)
    {
        $node_info = [
            'name'     => $server['remark'],
            'type'     => 'ss',
            'server'   => $server['address'],
            'port'     => $server['port'],
            'cipher'   => $server['method'],
            'password' => $server['passwd'],
            'udp'      => true
        ];

        return $node_info;
    }

    public static function buildVmess($uuid, $server)
    {
        $ws = $server['net'] == 'ws' ? 'ws' : '';
        $tls = $server['security'] == 'tls' ? true : false;
        $node_info = [
            'name'             => $server['remark'],
            'type'             => 'vmess',
            'server'           => $server['address'],
            'port'             => $server['port'],
            'uuid'             => $server['uuid'],
            'alterId'          => $server['aid'],
            'cipher'           => 'auto',
            'udp'              => true,
            'servername'       => $server['host'],
            'network'          => $ws,
            'tls'              => $tls,
            'skip-cert-verify' => true,
            'ws-opts'          => [
                'path'    => $server['path'],
                'headers' => [
                    'Host' => $server['host'],
                ]
            ],
            'grpc-opts' =>  [
                'grpc-service-name' => $server['servicename'],
            ]
        ];

        return $node_info;
    }

    public static function buildTrojan($uuid, $server)
    {
        $node_info = [
            'name'     => $server['remark'],
            'type'     => 'trojan',
            'server'   => $server['address'],
            'port'     => $server['port'],
            'password' => $server['uuid'],
            'sni'      => $server['sni'],
            'udp'      => true
        ];

        return $node_info;
    }

    private function isMatch($exp, $str)
    {
        return @preg_match($exp, $str);
    }

    private function isRegex($exp)
    {
        return @preg_match($exp, null) !== false;
    }
}