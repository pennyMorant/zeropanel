<?php

namespace App\Clients;

use App\Models\Node;
use App\Models\Setting;
use Sentry\Util\JSON;

class SingBox
{
    public $flag = 'sing-box';
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
        header("Content-type: application/json");
        header("content-disposition:attachment;filename*=UTF-8''".rawurlencode($website_name).".json");
        $singbox_json = dirname(__FILE__,3).'/resources/conf/singbox/singbox.json';
        $json = json_decode(file_get_contents($singbox_json), true);
        $proxy = [];
        $proxies = [];
        foreach ($servers as $server) {
            $buildMethod = 'build' . ucfirst($server['type']);
            if (method_exists($this, $buildMethod)) {
                array_push($proxy, $this->$buildMethod($server));
                array_push($proxies, $server['remark']);
            }
        }
        $json['outbounds'][0]['outbounds'] = array_merge($json['outbounds'][0]['outbounds'], $proxies);
        array_splice($json['outbounds'], 6 + 1, 0, $proxy);
        return json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    public function buildShadowsocks($server)
    {
        $node_info = [
            'domain_strategy' => '',
            'tag'             => $server['remark'],
            'method'          => $server['method'],
            'password'        => $server['passwd'],
            'server'          => $server['address'],
            'server_port'     => (int)$server['port'],
            'type'            => 'shadowsocks',
        ];
        return $node_info;
    }
}