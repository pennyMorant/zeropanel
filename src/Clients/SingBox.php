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
        
        for ($i = 0; $i < min(6,count($json['outbounds'])); $i++) {
            $json['outbounds'][$i]['outbounds'] = array_merge($json['outbounds'][$i]['outbounds'] ?? [], $proxies);
        }
        //$json['outbounds'][0]['outbounds'] = array_merge($json['outbounds'][0]['outbounds'], $proxies);
        array_splice($json['outbounds'], 6 + 1, 0, $proxy);
        return json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    public static function buildShadowsocks($server)
    {
        $passwd = Node::getShadowsocksSupportMethod($server['method']) ? $server['passwd'] : "{$server['server_psk']}:{$server['passwd']}";
        $node_info = [
            'domain_strategy' => '',
            'tag'             => $server['remark'],
            'method'          => $server['method'],
            'password'        => $passwd,
            'server'          => $server['address'],
            'server_port'     => (int)$server['port'],
            'type'            => 'shadowsocks',
        ];
        return $node_info;
    }

    public static function buildVmess($server)
    {
        $node_info = [
            'domain_strategy'      => '',
            'tag'                  => $server['remark'],
            'server'               => $server['address'],
            'server_port'          => (int)$server['port'],
            'uuid'                 => $server['uuid'],
            'type'                 => 'vmess',
            'security'             => 'auto',
            'alter_id'             => 0,
            'global_padding'       => false,
            'authenticated_length' => true,
            'packet_encoding'      => '',
            'multiplex'            => [
                'enabled'     => false,
                'protocol'    => 'smux',
                'max_streams' => 32,
            ],
        ];
        if ($server['security'] == 'tls') {
            $tls = [
                'tls' => [
                    'enabled'     => true ,
                    'server_name' => $server['host'],
                    'insecure'    => true ,
                    'utls'        => [
                        'enabled'     => false,
                        'fingerprint' => 'chrome',
                    ]
                ]
            ];
            $position = array_search('authenticated_length', array_keys($node_info)) + 1;

            $node_info = array_slice($node_info, 0, $position, true) + $tls + array_slice($node_info, $position, null, true);
        }
        if ($server['net'] == 'ws') {
            $ws = [
                'transport' => [
                    'type'    => 'ws',
                    'path'    => $server['path'],
                    'headers' => [
                        'Host' => $server['host'],
                    ]
                ]
            ];
            $position = array_search('packet_encoding', array_keys($node_info)) + 1;
            $node_info = array_slice($node_info, 0, $position, true) + $ws + array_slice($node_info, $position, null, true);
        }

        if ($server['net'] == 'grpc') {
            $grpc = [
                'transport' => [
                    'type'  => 'grpc',
                    'service_name' => $server['servicename'],
                    'idle_timeout' => '15s',
                    'ping_timeout' => '15s',
                    'permit_without_stream' => false,
                ]
            ];
            $position = array_search('packet_encoding', array_keys($node_info)) + 1;
            $node_info = array_slice($node_info, 0, $position, true) + $grpc + array_slice($node_info, $position, null, true);
        }

        return $node_info;
    }

    public static function buildVless($server)
    {
        $node_info = [
            'domain_strategy'      => '',
            'tag'                  => $server['remark'],
            'server'               => $server['address'],
            'server_port'          => (int)$server['port'],
            'uuid'                 => $server['uuid'],
            'type'                 => 'vless',
            'flow'                 => $server['flow'],       
            'packet_encoding'      => 'xudp',
            'multiplex'            => [
                'enabled'     => false,
                'protocol'    => 'smux',
                'max_streams' => 32,
            ],
        ];
        if ($server['security'] == 'reality') {
            $tls = [
                'tls' => [
                    'enabled'     => true ,
                    'server_name' => $server['host'],
                    'insecure'    => true ,
                    'utls'        => [
                        'enabled'     => true,
                        'fingerprint' => $server['fp'],
                    ],
                    'reality' => [
                        'enabled' => true,
                        'public_key' => $server['pbk'],
                        'short_id' => $server['sid'],
                    ]
                    
                ]
            ];
            $position = array_search('flow', array_keys($node_info)) + 1;

            $node_info = array_slice($node_info, 0, $position, true) + $tls + array_slice($node_info, $position, null, true);
        }

        if ($server['net'] == 'ws') {
            $ws = [
                'transport' => [
                    'type'    => 'ws',
                    'path'    => $server['path'],
                    'headers' => [
                        'Host' => $server['host'],
                    ]
                ]
            ];
            $position = array_search('packet_encoding', array_keys($node_info)) + 1;
            $node_info = array_slice($node_info, 0, $position, true) + $ws + array_slice($node_info, $position, null, true);
        }

        if ($server['net'] == 'grpc') {
            $grpc = [
                'transport' => [
                    'type'  => 'grpc',
                    'service_name' => $server['servicename'],
                    'idle_timeout' => '15s',
                    'ping_timeout' => '15s',
                    'permit_without_stream' => false,
                ]
            ];
            $position = array_search('packet_encoding', array_keys($node_info)) + 1;
            $node_info = array_slice($node_info, 0, $position, true) + $grpc + array_slice($node_info, $position, null, true);
        }

        return $node_info;
    }

    public static function buildTrojan($server)
    {
        $node_info = [
            'domain_strategy' => '',
            'tag'             => $server['remark'],
            'server'          => $server['address'],
            'server_port'     => (int)$server['port'],
            'password'        => $server['uuid'],
            'type'            => 'trojan',
            'tls'             => [
                'enabled' => true,
                'server_name' => $server['sni'],
                'insecure' => true,
                'utls' => [
                    'enabled' => false,
                    'fingerprint' => 'chrome',
                ],
            ],
            'multiplex' => [
                'enabled' => false,
                'protocol' => 'smux',
                'max_streams' => 32,
            ],
        ];

        if ($server['net'] == 'grpc') {
            $grpc = [
                'transport' => [
                    'type'  => 'grpc',
                    'service_name' => $server['servicename'],
                    'idle_timeout' => '15s',
                    'ping_timeout' => '15s',
                    'permit_without_stream' => false,
                ]
            ];
            $position = array_search('tls', array_keys($node_info)) + 1;
            $node_info = array_slice($node_info, 0, $position, true) + $grpc + array_slice($node_info, $position, null, true);
        }

        if ($server['net'] == 'ws') {
            $ws = [
                'transport' => [
                    'type'    => 'ws',
                    'path'    => $server['path'],
                    'headers' => [
                        'Host' => $server['host'],
                    ]
                ]
            ];
            $position = array_search('tls', array_keys($node_info)) + 1;
            $node_info = array_slice($node_info, 0, $position, true) + $ws + array_slice($node_info, $position, null, true);
        }

        return $node_info;
    }
}