<?php

namespace App\Controllers;

use App\Models\Setting;
use App\Models\Node;

final class SubController
{
    public static function getShadowsocks(array $node_config)
    {
        switch ($node_config['type']) {
            case 'shadowsocks':
                if (Node::getShadowsocksSupportMethod($node_config['method'])) {
                    $url = sprintf(
                        'ss://%s@[%s]:%s#%s',
                        base64_encode($node_config['method'] . ':' . $node_config['passwd']),
                        $node_config['address'],
                        $node_config['port'],
                        rawurlencode($node_config['remark'])
                    );
                } else {
                    $url = sprintf(
                        'ss://%s@[%s]:%s#%s',
                        base64_encode($node_config['method'] . ':' . $node_config['server_psk'] . ':' . $node_config['passwd']),
                        $node_config['address'],
                        $node_config['port'],
                        rawurlencode($node_config['remark'])
                    );
                }
                break;
        }
        return $url;
    }

    public static function getV2RayN(array $node_config)
    {
        $url = null;
        switch ($node_config['type']) {
            case 'shadowsocks':
                $url = self::getShadowsocks($node_config);
                break;
            case 'vmess':
                $node = [
                    'v'           => "2",
                    'ps'          => $node_config['remark'],
                    'add'         => $node_config['address'],
                    'port'        => (string)$node_config['port'],
                    'id'          => $node_config['uuid'],
                    'aid'         => (string)$node_config['aid'],
                    'net'         => $node_config['net'],
                    'type'        => $node_config['net'] == 'grpc' ? "multi" : $node_config['headertype'],
                    'host'        => $node_config['host'],
                    'path'        => $node_config['path'],
                    'tls'         => $node_config['security'],
                    'sni'         => $node_config['sni'],
                    'serviceName' => $node_config['servicename'],
                ];
                $url = 'vmess://' . base64_encode(json_encode($node, 320));
                break;
            case 'vless':
                $url= sprintf(
                    'vmess://%s@%s:%d?encryption=none&host=%s&path=%s&flow=%s&security=%s&sni=%s&serviceName=%s&headerType=%s&type=%s#%s',
                    $node_config['uuid'],
                    $node_config['address'],
                    $node_config['port'],
                    $node_config['host'],
                    $node_config['path'],
                    $node_config['flow'],
                    $node_config['security'],
                    $node_config['sni'],
                    rawurlencode($node_config['servicename']),
                    $node_config['headertype'],
                    $node_config['net'],
                    rawurlencode($node_config['remark'])
                );
                break;
            case 'trojan':
                $url = self::getTrojan($node_config);
        }
        return $url;
    }

    public static function getSurge(array $node_config)
    {
        $node_info = null;
            switch ($node_config['type']) {
                case 'shadowsocks':
                    if (Node::getShadowsocksSupportMethod($node_config['method'])) {
                        $node_info = sprintf(
                            '%s = ss, %s, %s, encrypt-method=%s, password=%s, udp-relay=true',
                            $node_config['remark'],
                            $node_config['address'],
                            $node_config['port'],
                            $node_config['method'],
                            $node_config['passwd']
                        );
                    }                  
                    break;
                case 'vmess':                  
                    $vmess_params['ws'] = $node_config['net'] == 'ws' ? 'true' : 'false';
                    $vmess_params['tls'] = $node_config['security'] == 'tls' ? 'true' : 'false';
                    
                    $node_info = sprintf(
                        '%s = vmess, %s, %s, username=%s, ws=%s, ws-path=%s, ws-header=host:%s, tls=%s, sni=%s',
                        $node_config['remark'],
                        $node_config['address'],
                        $node_config['port'],
                        $node_config['uuid'],
                        $vmess_params['ws'],
                        $node_config['path'],
                        $node_config['host'],
                        $vmess_params['tls'],
                        $node_config['sni']
                    );
                    break;
                case 'trojan':
                    $node_info = sprintf(
                        '%s = trojan, %s, %s, password=%s, sni=%s',
                        $node_config['remark'],
                        $node_config['address'],
                        $node_config['port'],
                        $node_config['uuid'],
                        $node_config['sni']
                    );
                    break;
            }
        return $node_info;
    }

    public static function getQuantumult(array $node_config)
    {
        $return = null;
        switch ($node_config['type']) {
            case 'shadowsocks':
                if (Node::getShadowsocksSupportMethod($node_config['method'])) {
                    $return = $node_config['remark'] . ' = shadowsocks, ' . $node_config['address'] . ', ' . $node_config['port'] . ', ' . $node_config['method'] . ', "' . $node_config['passwd'] . '", upstream-proxy=false, upstream-proxy-auth=false' . ', group=' . Setting::obtain('website_name') . '_ss';
                }
                break;
            case 'vmess':
                if (!in_array($node_config['net'], ['ws', 'tcp', 'http'])) {
                    break;
                }
                $tls = ', over-tls=false, certificate=1';
                if ($node_config['security'] == 'tls') {
                    $tls = ', over-tls=true, tls-host=' . $node_config['host'];
                    if ($node_config['verify_cert']) {
                        $tls .= ', certificate=1';
                    } else {
                        $tls .= ', certificate=0';
                    }
                }
                $obfs = '';
                if (in_array($node_config['net'], ['ws', 'http'])) {
                    $obfs = ', obfs=' . $node_config['net'] . ', obfs-path="' . $node_config['path'] . '", obfs-header="Host: ' . $node_config['host'] . '[Rr][Nn]User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 18_0_0 like Mac OS X) AppleWebKit/888.8.88 (KHTML, like Gecko) Mobile/6666666"';
                }
                $return = $node_config['remark'] . ' = vmess, ' . $node_config['address'] . ', ' . $node_config['port'] . ', chacha20-ietf-poly1305, "' . $node_config['uuid'] . '", group=' . Setting::obtain('website_name') . '_VMess' . $tls . $obfs;
                break;
        }
        return $return;
    }

    public static function getQuantumultX(array $node_config)
    {
        $return = null;
        switch ($node_config['type']) {
            case 'shadowsocks':
                if (Node::getShadowsocksSupportMethod($node_config['method'])) {
                    $return = (self::getShadowsocks($node_config));
                }
                break;
            case 'vmess':
                // ;vmess=example.com:80, method=none, password=23ad6b10-8d1a-40f7-8ad0-e3e35cd32291, fast-open=false, udp-relay=false, tag=vmess-01
                // ;vmess=example.com:80, method=aes-128-gcm, password=23ad6b10-8d1a-40f7-8ad0-e3e35cd32291, fast-open=false, udp-relay=false, tag=vmess-02
                // ;vmess=example.com:443, method=none, password=23ad6b10-8d1a-40f7-8ad0-e3e35cd32291, obfs=over-tls, fast-open=false, udp-relay=false, tag=vmess-tls
                // ;vmess=example.com:80, method=chacha20-poly1305, password=23ad6b10-8d1a-40f7-8ad0-e3e35cd32291, obfs=ws, obfs-uri=/ws, fast-open=false, udp-relay=false, tag=vmess-ws
                // ;vmess=example.com:443, method=chacha20-poly1305, password=23ad6b10-8d1a-40f7-8ad0-e3e35cd32291, obfs=wss, obfs-uri=/ws, fast-open=false, udp-relay=false, tag=vmess-ws-tls
                if (!in_array($node_config['net'], ['ws', 'tcp'])) {
                    break;
                }
                $return = ('vmess=' . $node_config['address'] . ':' . $node_config['port'] . ', method=chacha20-poly1305' . ', password=' . $node_config['uuid']);
                switch ($node_config['net']) {
                    case 'ws':
                        $return .= ($node_config['security'] == 'tls' ? ', obfs=wss' : ', obfs=ws');
                        $return .= ', obfs-uri=' . $node_config['path'] . ', obfs-host=' . $node_config['host'];
                        break;
                    case 'tcp':
                        $return .= ($node_config['security'] == 'tls' ? ', obfs=over-tls' : '');
                        break;
                }
                $return .= (', tag=' . $node_config['remark']);
                break;
            case 'trojan':
                // ;trojan=example.com:443, password=pwd, over-tls=true, tls-verification=true, fast-open=false, udp-relay=false, tag=trojan-tls-01
                $return = ('trojan=' . $node_config['address'] . ':' . $node_config['port'] . ', password= ' . $node_config['uuid'] . ', tls-host=' . $node_config['sni']);
                $return .= ', over-tls=true, tls-verification=true';
                $return .= (', tag=' . $node_config['remark']);
                break;
        }
        return $return;
    }

    public static function getSurfboard(array $node_config)
    {
        $node_info = null;
            switch ($node_config['type']) {
                case 'shadowsocks':
                    if (Node::getShadowsocksSupportMethod($node_config['method'])) {
                        $node_info = sprintf(
                            '%s = ss, %s, %s, encrypt-method=%s, password=%s, udp-relay=true',
                            $node_config['remark'],
                            $node_config['address'],
                            $node_config['port'],
                            $node_config['method'],
                            $node_config['passwd']
                        );    
                    }                
                    break;
                case 'vmess':                  
                    $vmess_params['ws']  = $node_config['net']      == 'ws' ? 'true' : 'false';
                    $vmess_params['tls'] = $node_config['security'] == 'tls' ? 'true' : 'false';
                    
                    $node_info = sprintf(
                        '%s = vmess, %s, %s, username=%s, ws=%s, ws-path=%s, ws-header=host:%s, tls=%s, sni=%s, skip-cert-verify=true, vmess-aead=true',
                        $node_config['remark'],
                        $node_config['address'],
                        $node_config['port'],
                        $node_config['uuid'],
                        $vmess_params['ws'],
                        $node_config['path'],
                        $node_config['host'],
                        $vmess_params['tls'],
                        $node_config['sni']
                    );
                    break;
                case 'trojan':
                    $node_info = sprintf(
                        '%s = trojan, %s, %s, password=%s, sni=%s, skip-cert-verify=true',
                        $node_config['remark'],
                        $node_config['address'],
                        $node_config['port'],
                        $node_config['uuid'],
                        $node_config['sni']
                    );
                    break;
            }
        return $node_info;
    }

    public static function getClash(array $node_config)
    {
        $node_info = null;
        switch ($node_config['type']) {
            case 'shadowsocks':
                if (Node::getShadowsocksSupportMethod($node_config['method'])) {
                    $node_info = [
                        'name'     => $node_config['remark'],
                        'type'     => 'ss',
                        'server'   => $node_config['address'],
                        'port'     => $node_config['port'],
                        'cipher'   => $node_config['method'],
                        'password' => $node_config['passwd'],
                        'udp'      => true
                    ];
                }
                break;
            case 'vmess':
                $ws = $node_config['net'] == 'ws' ? 'ws' : '';
                $tls = $node_config['security'] == 'tls' ? true : false;
                $node_info = [
                    'name'             => $node_config['remark'],
                    'type'             => 'vmess',
                    'server'           => $node_config['address'],
                    'port'             => $node_config['port'],
                    'uuid'             => $node_config['uuid'],
                    'alterId'          => $node_config['aid'],
                    'cipher'           => 'auto',
                    'udp'              => true,
                    'servername'       => $node_config['host'],
                    'network'          => $ws,
                    'tls'              => $tls,
                    'skip-cert-verify' => true,
                    'ws-opts'          => [
                        'path'    => $node_config['path'],
                        'headers' => [
                            'Host' => $node_config['host'],
                        ]
                    ],
                    'grpc-opts' =>  [
                        'grpc-service-name' => $node_config['servicename'],
                    ]
                ];
                break;
            case 'trojan':
                $node_info = [
                    'name'     => $node_config['remark'],
                    'type'     => 'trojan',
                    'server'   => $node_config['address'],
                    'port'     => $node_config['port'],
                    'password' => $node_config['uuid'],
                    'sni'      => $node_config['sni'],
                    'udp'      => true
                ];
                break;
        }
        return $node_info;
    }

    public static function getShadowrocket(array $node_config)
    {
        $url = null;
        switch ($node_config['type']) {
            case 'shadowsocks':
                $url = self::getShadowsocks($node_config);
                break;
            case 'vmess':
                $tls = $node_config['security'] == 'tls' ? 1 : 0;
                $url= sprintf(
                    'vmess://%s@%s:%d?encryption=auto&host=%s&path=%s&flow=%s&tls=%s&sni=%s&serviceName=%s&headerType=%s&type=%s#%s',
                    $node_config['uuid'],
                    $node_config['address'],
                    $node_config['port'],
                    $node_config['host'],
                    $node_config['path'],
                    $node_config['flow'],
                    $tls,
                    $node_config['sni'],
                    rawurlencode($node_config['servicename']),
                    $node_config['headertype'],
                    $node_config['net'],
                    rawurlencode($node_config['remark'])
                );
                break;
            case 'vless':
                $tls = $node_config['security'] == 'tls' ? 1 : 0;
                $url= sprintf(
                    'vmess://%s@%s:%d?encryption=none&host=%s&path=%s&flow=%s&tls=%s&sni=%s&serviceName=%s&headerType=%s&type=%s#%s',
                    $node_config['uuid'],
                    $node_config['address'],
                    $node_config['port'],
                    $node_config['host'],
                    $node_config['path'],
                    $node_config['flow'],
                    $tls,
                    $node_config['sni'],
                    rawurlencode($node_config['servicename']),
                    $node_config['headertype'],
                    $node_config['net'],
                    rawurlencode($node_config['remark'])
                );
                break;
            case 'trojan':
                $url = self::getTrojan($node_config);
                break;
        }
        return $url;
    }
    
    public static function getTrojan(array $node_config)
    {
        $url = null;
        switch ($node_config['type']) {
            case 'trojan':
                $url= sprintf(
                    'trojan://%s@%s:%s?flow=%s&security=%s&sni=%s&#%s',
                    $node_config['uuid'],
                    $node_config['address'],
                    $node_config['port'],
                    $node_config['flow'],
                    $node_config['security'],
                    $node_config['sni'],
                    rawurlencode($node_config['remark'])
                );
                return $url;
                break;
        }
    }

    public static function getAnXray(array $node_config)
    {
        $url = null;
        switch ($node_config['type']) {
            case 'shadowsocks':
                $url = self::getShadowsocks($node_config);
                break;				
            case 'vmess':
                $url = sprintf(
                        'vmess://%s@%s:%d?encryption=auto&host=%s&path=%s&flow=%s&security=%s&sni=%s&serviceName=%s&headerType=%s&type=%s#%s',
                        $node_config['uuid'],
                        $node_config['address'],
                        $node_config['port'],
                        $node_config['host'],
                        $node_config['path'],
                        $node_config['flow'],
                        $node_config['security'],
                        $node_config['sni'],
                        rawurlencode($node_config['servicename']),
                        $node_config['headertype'],
                        $node_config['net'],
                        rawurlencode($node_config['remark'])
                );
                break;
            case 'trojan':
                $url = self::getTrojan($node_config);
                break;
            case 'vless':
                $url= sprintf(
                    'vmess://%s@%s:%d?encryption=none&host=%s&path=%s&flow=%s&security=%s&sni=%s&serviceName=%s&headerType=%s&type=%s#%s',
                    rawurlencode($node_config['uuid']),
                    $node_config['address'],
                    $node_config['port'],
                    $node_config['host'],
                    rawurlencode($node_config['path']),
                    $node_config['flow'],
                    $node_config['security'],
                    $node_config['sni'],
                    rawurlencode($node_config['servicename']),
                    $node_config['headertype'],
                    $node_config['net'],
                    rawurlencode($node_config['remark'])
                );
                break;
        }
        return $url;
    }	
}
