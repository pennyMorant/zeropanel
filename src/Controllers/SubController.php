<?php

namespace App\Controllers;

use App\Models\{
    Setting
};

final class SubController
{
    public static function getShadowsocks(array $node_config)
    {
        switch ($node_config['type']) {
            case 'shadowsocks':
                $return = 'ss://' . base64_encode($node_config['method'] . ':' . $node_config['passwd']) . '@' . $node_config['address'] . ':' . $node_config['port'];
                return $return . '#' . rawurlencode($node_config['remark']);
                break;
        }
    }

    public static function getV2RayN(array $node_config)
    {
        $return = null;
        switch ($node_config['type']) {
            case 'shadowsocks':
                $return = self::getShadowsocks($node_config);
                break;
            case 'vmess':
                $node = [
                    'v' => "2",
                    'ps' => $node_config['remark'],
                    'add' => $node_config['address'],
                    'port' => (string)$node_config['port'],
                    'id' => $node_config['uuid'],
                    'aid' => (string)$node_config['aid'],
                    'net' => $node_config['net'],
                    'type' => $node_config['net'] == 'grpc' ? "multi" : $node_config['headertype'],
                    'host' => $node_config['host'],
                    'path' => $node_config['path'],
                    'tls' => $node_config['security'],
                    'sni' => $node_config['sni'],
                    'serviceName' => $node_config['servicename'],
                ];
                $return = 'vmess://' . base64_encode(json_encode($node, 320));
                break;
            case 'vless':
                $return = 'vless://' . $node_config['uuid'] . '@' . $node_config['address'] . ':' . $node_config['port'] . '?encryption=none&flow=' . 
                $node_config['flow'] . '&security=' . $node_config['security'] . '&sni=' . $node_config['sni'] . '&host=' . $node_config['host'] . 
                '&serviceName=' . $node_config['servicename'] . '&type=' . $node_config['net'] . '#' . rawurlencode($node_config['remark']);
                break;
            case 'trojan':
                $return = 'trojan://' . $node_config['uuid'] . '@' . $node_config['address'] . ':' . $node_config['port'] . '?flow=' . 
                $node_config['flow'] . '&security=' . $node_config['security'] . '&sni=' . $node_config['sni'] . '#' . rawurlencode($node_config['remark']);
        }
        return $return;
    }

    public static function getSurge(array $node_config)
    {
        $return = null;
            switch ($node_config['type']) {
                case 'shadowsocks':
                    $return = ($node_config['remark'] . ' = ss, ' . $node_config['address'] . ', ' . $node_config['port'] . ', encrypt-method=' . $node_config['method'] . ', password=' . $node_config['passwd'] . ', udp-relay=true');
                    break;
                case 'vmess':
                    if (!in_array($node_config['net'], ['ws', 'tcp'])) {
                        break;
                    }
                    if ($node_config['security'] == 'tls') {
                        $tls = ', tls=true';
                        $sni = $node_config['sni'] ? ', ' . $node_config['sni'] : '';
                    } else {
                        $tls = ', ';
                        $sni = ', ';
                    }
                    $ws = ($node_config['net'] == 'ws'
                        ? ', ws=true, ws-path=' . $node_config['path'] . ', ws-headers=host:' . $node_config['host']
                        : '');
                    $return = $node_config['remark'] . ' = vmess, ' . $node_config['address'] . ', ' . $node_config['port'] . ', username = ' . $node_config['uuid'] . $ws . $tls . $sni;
                    break;
                case 'trojan':
                    $return = $node_config['remark'] . ' = trojan, ' . $node_config['address'] . ', ' . $node_config['port'] . ', password= ' . $node_config['uuid'] . ', sni= ' . $node_config['sni'];
                    break;
            }
        return $return;
    }

    public static function getQuantumult(array $node_config)
    {
        $return = null;
        switch ($node_config['type']) {
            case 'shadowsocks':
                $return = $node_config['remark'] . ' = shadowsocks, ' . $node_config['address'] . ', ' . $node_config['port'] . ', ' . $node_config['method'] . ', "' . $node_config['passwd'] . '", upstream-proxy=false, upstream-proxy-auth=false' . ', group=' . Setting::obtain('website_name') . '_ss';
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
                // ;shadowsocks=example.com:80, method=chacha20, password=pwd, obfs=http, obfs-host=bing.com, obfs-uri=/resource/file, fast-open=false, udp-relay=false, server_check_url=http://www.apple.com/generate_204, tag=ss-01
                // ;shadowsocks=example.com:80, method=chacha20, password=pwd, obfs=http, obfs-host=bing.com, obfs-uri=/resource/file, fast-open=false, udp-relay=false, tag=ss-02
                // ;shadowsocks=example.com:443, method=chacha20, password=pwd, obfs=tls, obfs-host=bing.com, fast-open=false, udp-relay=false, tag=ss-03
                // ;shadowsocks=example.com:80, method=aes-128-gcm, password=pwd, obfs=ws, fast-open=false, udp-relay=false, tag=ss-ws-01
                // ;shadowsocks=example.com:80, method=aes-128-gcm, password=pwd, obfs=ws, obfs-uri=/ws, fast-open=false, udp-relay=false, tag=ss-ws-02
                // ;shadowsocks=example.com:443, method=aes-128-gcm, password=pwd, obfs=wss, obfs-uri=/ws, fast-open=false, udp-relay=false, tag=ss-ws-tls
                $return = (self::getShadowsocks($node_config));
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
        $return = null;
        switch ($node_config['type']) {
            case 'shadowsocks':
                $return = ($node_config['remark'] . ' = shadowsocks, ' . $node_config['address'] . ', ' . $node_config['port'] . ', encrypt-method=' . $node_config['method'] . ', password=' . $node_config['passwd'] .  ', udp-relay=true');
                break;
            case 'vmess':
                if (!in_array($node_config['net'], ['ws', 'tcp'])) {
                    break;
                }
                if ($node_config['security'] == 'tls') {
                    $tls = ', tls=true';
                    $sni = $node_config['sni'] ? ', ' . $node_config['sni'] : '';
                } else {
                    $tls = ', ';
                    $sni = ', ';
                }
                $ws = ($node_config['net'] == 'ws'
                    ? ', ws=true, ws-path=' . $node_config['path'] . ', ws-headers=host:' . $node_config['host']
                    : '');
                $return = $node_config['remark'] . ' = vmess, ' . $node_config['address'] . ', ' . $node_config['port'] . ', username= ' . $node_config['uuid'] . $ws . $tls . $sni;
                break;
            case 'trojan':
                $return = $node_config['remark'] . ' = trojan, ' . $node_config['address'] . ', ' . $node_config['port'] . ', password= ' . $node_config['uuid'] . ', sni= ' . $node_config['sni'];
                break;    
        }
        return $return;
    }

    public static function getClash(array $node_config)
    {
        $return = null;
        switch ($node_config['type']) {
            case 'shadowsocks':
                $return = [
                    'name' => $node_config['remark'],
                    'type' => 'ss',
                    'server' => $node_config['address'],
                    'port' => $node_config['port'],
                    'cipher' => $node_config['method'],
                    'password' => $node_config['passwd'],
                    'udp' => true
                ];
                break;
            case 'vmess':
                if (!in_array($node_config['net'], array('ws', 'tcp', 'grpc'))) {
                    break;
                }
                $return = [
                    'name' => $node_config['remark'],
                    'type' => 'vmess',
                    'server' => $node_config['address'],
                    'port' => $node_config['port'],
                    'uuid' => $node_config['uuid'],
                    'alterId' => $node_config['aid'],
                    'cipher' => 'auto',
                    'udp' => true
                ];
                if ($node_config['host']) {
                    $return['servername'] = $node_config['host'];
                }
                if ($node_config['net'] == 'ws') {
                    $return['network'] = 'ws';
                    $return['ws-opts']['path'] = $node_config['path'];
                    $return['ws-opts']['headers']['Host'] = ($node_config['host'] != '' ? $node_config['host'] : $node_config['address']);
                }
                if ($node_config['security'] == 'tls') {
                    $return['tls'] = true;
                    if ($node_config['verify_cert'] == false) {
                        $return['skip-cert-verify'] = true;
                    }
                }
                if ($node_config['net'] == 'grpc') {
                    $return['network'] = 'grpc';
                    $return['servername'] = ($node_config['host'] != '' ? $node_config['host'] : $node_config['address']);
                    $return['grpc-opts']['grpc-service-name'] = ($node_config['servicename'] != '' ? $node_config['servicename'] : "");
                }
                break;
            case 'trojan':
                $return = [
                    'name' => $node_config['remark'],
                    'type' => 'trojan',
                    'server' => $node_config['address'],
                    'port' => $node_config['port'],
                    'password' => $node_config['uuid'],
                    'sni' => $node_config['sni'],
                    'udp' => true
                ];
                if ($node_config['net'] == 'grpc') {
                    $return['network'] = 'grpc';
                    $return['grpc-opts']['grpc-service-name'] = ($node_config['servicename'] != '' ? $node_config['servicename'] : "");
                }
                break;
        }
        return $return;
    }

    public static function getShadowrocket(array $node_config)
    {
        $return = null;
        switch ($node_config['type']) {
            case 'shadowsocks':
                    $return = (self::getShadowsocks($node_config));
                break;
            case 'vmess':
                if (!in_array($node_config['net'], ['tcp', 'ws', 'http', 'h2'])) {
                    break;
                }
                $obfs = '';
                switch ($node_config['net']) {
                    case 'ws':
                        $obfs .= ($node_config['host'] != ''
                            ? ('&obfsParam=' . $node_config['host'] . '&path=' . $node_config['path'] . '&obfs=websocket')
                            : ('&obfsParam=' . $node_config['address'] . '&path=' . $node_config['path'] . '&obfs=websocket'));
                        break;
                    case 'kcp':
                        $obfs .= 'obfsParam={"header":' . '"' . ($node_config['headertype'] == '' || $node_config['headertype'] == 'noop' ? 'none' : $node_config['headertype']) . '"' . '}&obfs=mkcp';
                        break;
                    case 'mkcp':
                        $obfs .= 'obfsParam={"header":' . '"' . ($node_config['headertype'] == '' || $node_config['headertype'] == 'noop' ? 'none' : $node_config['headertype']) . '"' . '}&obfs=mkcp';
                        break;
                    case 'h2':
                        $obfs .= ($node_config['host'] != ''
                            ? ('&obfsParam=' . $node_config['host'] . '&path=' . $node_config['path'] . '&obfs=h2')
                            : ('&obfsParam=' . $node_config['address'] . '&path=' . $node_config['path'] . '&obfs=h2'));
                        break;
                    default:
                        $obfs .= '&obfs=none';
                        break;
                }
                $tls = '';
                if ($node_config['security'] == 'tls') {
                    $tls = '&tls=1';
                    if ($node_config['verify_cert'] == false) {
                        $tls .= '&allowInsecure=1';
                    }
                    $tls .= ($node_config['sni']
                        ? ('&peer=' . $node_config['sni'])
                        : ('&peer=' . $node_config['host']));
                }
                $return = 'vmess://auto:' . $node_config['uuid'] . '@' . $node_config['address'] . ':' . $node_config['port'] . '?remarks=' . rawurlencode($node_config['remark']) . $obfs . $tls . '&alterId=' . $node_config['aid'];
                break;
            case 'vless':
                if (!in_array($node_config['net'], ['tcp', 'ws', 'http', 'h2'])) {
                    break;
                }
                $obfs = '';
                switch ($node_config['net']) {
                    case 'ws':
                        $obfs .= ($node_config['host'] != ''
                            ? ('&obfsParam=' . $node_config['host'] . '&path=' . $node_config['path'] . '&obfs=websocket')
                            : ('&obfsParam=' . $node_config['address'] . '&path=' . $node_config['path'] . '&obfs=websocket'));
                        break;
                    case 'kcp':
                        $obfs .= 'obfsParam={"header":' . '"' . ($node_config['headertype'] == '' || $node_config['headertype'] == 'noop' ? 'none' : $node_config['headertype']) . '"' . '}&obfs=mkcp';
                        break;
                    case 'mkcp':
                        $obfs .= 'obfsParam={"header":' . '"' . ($node_config['headertype'] == '' || $node_config['headertype'] == 'noop' ? 'none' : $node_config['headertype']) . '"' . '}&obfs=mkcp';
                        break;
                    case 'h2':
                        $obfs .= ($node_config['host'] != ''
                            ? ('&obfsParam=' . $node_config['host'] . '&path=' . $node_config['path'] . '&obfs=h2')
                            : ('&obfsParam=' . $node_config['address'] . '&path=' . $node_config['path'] . '&obfs=h2'));
                        break;
                    default:
                        $obfs .= '&obfs=none';
                        break;
                }
                $tls = '';
                if ($node_config['security'] == 'tls') {
                    $tls = '&tls=1';
                    if ($node_config['verify_cert'] == false) {
                        $tls .= '&allowInsecure=1';
                    }
                    $tls .= ($node_config['sni']
                        ? ('&peer=' . $node_config['sni'])
                        : ('&peer=' . $node_config['host']));
                } else {
                    $tls = '&tls=1';
                    
                    if ($node_config['verify_cert'] == false) {
                        $tls .= '&allowInsecure=1';
                    }
                    $tls .= ($node_config['sni']
                        ? ('&peer=' . $node_config['sni'])
                        : ('&peer=' . $node_config['host']));
                    $tls .= '&xtls=1';
                }
                $return = 'vless://auto:' . $node_config['uuid'] . '@' . $node_config['address'] . ':' . $node_config['port'] . '?remarks=' . rawurlencode($node_config['remark']) . $obfs . $tls . '&alterId=' . $node_config['aid'];
                break;
            case 'trojan':
                $return = 'trojan://' . $node_config['uuid'] . '@' . $node_config['address'] . ':' . $node_config['port'] . '?peer=' . $node_config['sni'] . '#' . rawurlencode($node_config['remark']);
                break;
        }
        return $return;
    }

    public static function getKitsunebi(array $node_config)
    {
        $return = null;
        switch ($node_config['type']) {
            case 'shadowsocks':
                $return = (self::getShadowsocks($node_config));
                break;
            case 'vmess':
                $network = ($node_config['net'] == 'tls'
                    ? '&network=tcp'
                    : ('&network=' . $node_config['net']));
                $protocol = '';
                switch ($node_config['net']) {
                    case 'kcp':
                        $protocol .= ('&kcpheader=' . $node_config['headertype']);
                        break;
                    case 'ws':
                        $protocol .= ('&wspath=' . $node_config['path'] . '&wsHost=' . $node_config['host']);
                        break;
                    case 'h2':
                        $protocol .= ('&h2Path=' . $node_config['path'] . '&h2Host=' . $node_config['host']);
                        break;
                }
                $tls = '';
                if ($node_config['secty'] == 'tls') {
                    $tls = '&tls=1';
                    if ($node_config['verify_cert'] == false) {
                        $tls .= '&allowInsecure=1';
                    }
                }
                $return .= 'vmess://auto:' . $node_config['uuid'] . '@' . $node_config['address'] . ':' . $node_config['port'] . '?remark=' . rawurlencode($node_config['remark']) . $network . $protocol . '&aid=' . $node_config['aid'] . $tls;
                break;
        }
        return $return;
    }
    
    public static function getTrojan(array $node_config)
    {
        $return = null;
        switch ($node_config['type']) {
            case 'trojan':
                $return  = ('trojan://' . $node_config['uuid'] . '@' . $node_config['address'] . ':' . $node_config['port']);
                $return .= ('?peer=' . $node_config['sni'] . '&sni=' . $node_config['sni']);
                if($node_config['security'] == "xtls"){
                   $return.=("&security=".$node_config['security']."&flow=".$node_config['flow']);
                }
                if ($node_config['net'] === 'grpc') {
                    $params = [];
                    // shadowrocket
                    $params['obfs'] = 'grpc';
                    $params['path'] = $node_config['servicename'];
                    $params['obfsParam'] = $node_config['host'];
                    // v2rayn
                    $params['type'] = 'grpc';
                    $params['security'] = 'tls';
                    $params['serviceName'] = $node_config['servicename'];
                    $return .= '&' . http_build_query($params);
                }
                $return.=('#' .  rawurlencode($node_config['remark']));
                break;
        }
        return $return;
    }

    public static function getAnXray(array $node_config)
    {
        $return = null;
        switch ($node_config['type']) {
            case 'shadowsocks':
                $return = self::getShadowsocks($node_config);
                break;				
            case 'vmess':
                $return = 'vmess://' . $node_config['uuid'] . '@' . $node_config['address'] . ':' . $node_config['port'] . '?encryption=auto&host=' . 
                            $node_config['host'] . '&path=' . $node_config['path'] . '&flow=' . $node_config['flow'] . '&security=' . $node_config['security'] . 
                            '&sni=' . $node_config['sni'] . '&serviceName=' . $node_config['servicename'] . '&headerType=' . $node_config['headertype'] . '&type=' . 
                            $node_config['net']  . '#' . rawurlencode($node_config['remark']);
                break;
            case 'trojan':
                $return = 'trojan://' . $node_config['uuid'] . '@' . $node_config['address'] . ':' . $node_config['port'] . '?flow=' . $node_config['flow'] . 
                        '&security=' . $node_config['security'] . '&sni=' . $node_config['sni'] . '#' . rawurlencode($node_config['remark']);
                break;
            case 'vless':
                $return = 'vless://' . $node_config['uuid'] . '@' . $node_config['address'] . ':' . $node_config['port'] . '?encryption=none&flow=' . 
                            $node_config['flow'] . '&security=' . $node_config['security'] . '&sni=' . $node_config['sni'] . '&host=' . $node_config['host'] . 
                            '&type=' . $node_config['net'] . '#' . rawurlencode($node_config['remark']);
                break;
        }
        return $return;
    }	
}
