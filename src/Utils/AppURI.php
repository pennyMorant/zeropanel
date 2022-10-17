<?php

namespace App\Utils;

use App\Models\{
    Setting
};

class AppURI
{
    public static function getShadowsocksURI(array $item)
    {
        $return = null;
        switch ($item['type']) {
            case 'ss':
                $return = 'ss://' . Tools::base64_url_encode($item['method'] . ':' . $item['passwd'] . '@' . $item['address'] . ':' . $item['port']);
                return $return . '#' . rawurlencode($item['remark']);
                break;
        }
        return $return;
    }

    public static function getV2RayNURI(array $item)
    {
        $return = null;
        switch ($item['type']) {
            case 'vmess':
                $node = [
                    'v' => "2",
                    'ps' => $item['remark'],
                    'add' => $item['add'],
                    'port' => (string)$item['port'],
                    'id' => $item['id'],
                    'aid' => (string)$item['aid'],
                    'net' => $item['net'],
                    'type' => $item['net'] == 'grpc' ? "multi" : $item['headertype'],
                    'host' => $item['net'] == 'grpc' ? '' : $item['host'],
                    'path' => $item['net'] == 'grpc' ? $item['servicename'] : $item['path'],
                    'tls' => $item['tls'],
                    'sni' => $item['sni']
                ];
                $return = ('vmess://' . base64_encode(json_encode($node, 320)));
                break;
            case 'vless':
                $return = ('vless://' . $item['id'] ."@".$item['add'].":".$item['port']."?encryption=none");
                $return.="&type=".$item['net'];
                $return.="&security=".$item['tls'];
                if($item['tls'] == "xtls"){
                   $return.="&flow=".$item['flow'];
                }
                if($item['host']!="")$return=$return."&host=". rawurlencode($item['host']);
                if($item['host']!="")$return=$return."&sni=".$item['host'];	
                if($item['path']!="")$return=$return."&path=".rawurlencode($item['path']);
                if($item['net'] == "grpc"){
                   if($item['net'] == "grpc")$return=$return."&mode=multi&serviceName=".$item['servicename'];
                }else{
                   if($item['headertype']!="")$return=$return."&headerType=".$item['headertype'];
                }
                if ($item['remark']!="")$return=$return."#". rawurlencode($item['remark']);
                break;
        }
        return $return;
    }

    public static function getSurgeURI(array $item)
    {
        $return = null;
            switch ($item['type']) {
                case 'ss':
                    $return = ($item['remark'] . ' = ss, ' . $item['address'] . ', ' . $item['port'] . ', encrypt-method=' . $item['method'] . ', password=' . $item['passwd'] . ', udp-relay=true');
                    break;
                case 'vmess':
                    if (!in_array($item['net'], ['ws', 'tcp'])) {
                        break;
                    }
                    if ($item['tls'] == 'tls') {
                        $tls = ', tls=true';
                        $sni = $item['sni'] ? ', ' . $item['sni'] : '';
                    }
                    $ws = ($item['net'] == 'ws'
                        ? ', ws=true, ws-path=' . $item['path'] . ', ws-headers=host:' . $item['host']
                        : '');
                    $return = $item['remark'] . ' = vmess, ' . $item['add'] . ', ' . $item['port'] . ', username = ' . $item['id'] . $ws . $tls . $sni;
                    break;
                case 'trojan':
                    $return = ($item['remark'] . ' = trojan, ' . $item['address'] . ', ' . $item['port'] . ', password= ' . $item['passwd']) . ', sni= ' . $item['host'];
                    break;
            }
        return $return;
    }

    public static function getQuantumultURI(array $item)
    {
        $return = null;
        switch ($item['type']) {
            case 'ss':
                $return = $item['remark'] . ' = shadowsocks, ' . $item['address'] . ', ' . $item['port'] . ', ' . $item['method'] . ', "' . $item['passwd'] . '", upstream-proxy=false, upstream-proxy-auth=false' . ', group=' . Setting::obtain('website_general_name') . '_ss';
                break;
            case 'vmess':
                if (!in_array($item['net'], ['ws', 'tcp', 'http'])) {
                    break;
                }
                $tls = ', over-tls=false, certificate=1';
                if ($item['tls'] == 'tls') {
                    $tls = ', over-tls=true, tls-host=' . $item['host'];
                    if ($item['verify_cert']) {
                        $tls .= ', certificate=1';
                    } else {
                        $tls .= ', certificate=0';
                    }
                }
                $obfs = '';
                if (in_array($item['net'], ['ws', 'http'])) {
                    $obfs = ', obfs=' . $item['net'] . ', obfs-path="' . $item['path'] . '", obfs-header="Host: ' . $item['host'] . '[Rr][Nn]User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 18_0_0 like Mac OS X) AppleWebKit/888.8.88 (KHTML, like Gecko) Mobile/6666666"';
                }
                $return = $item['remark'] . ' = vmess, ' . $item['add'] . ', ' . $item['port'] . ', chacha20-ietf-poly1305, "' . $item['id'] . '", group=' . Setting::obtain('website_general_name') . '_VMess' . $tls . $obfs;
                break;
        }
        return $return;
    }

    public static function getQuantumultXURI(array $item)
    {
        $return = null;
        switch ($item['type']) {
            case 'ss':
                // ;shadowsocks=example.com:80, method=chacha20, password=pwd, obfs=http, obfs-host=bing.com, obfs-uri=/resource/file, fast-open=false, udp-relay=false, server_check_url=http://www.apple.com/generate_204, tag=ss-01
                // ;shadowsocks=example.com:80, method=chacha20, password=pwd, obfs=http, obfs-host=bing.com, obfs-uri=/resource/file, fast-open=false, udp-relay=false, tag=ss-02
                // ;shadowsocks=example.com:443, method=chacha20, password=pwd, obfs=tls, obfs-host=bing.com, fast-open=false, udp-relay=false, tag=ss-03
                // ;shadowsocks=example.com:80, method=aes-128-gcm, password=pwd, obfs=ws, fast-open=false, udp-relay=false, tag=ss-ws-01
                // ;shadowsocks=example.com:80, method=aes-128-gcm, password=pwd, obfs=ws, obfs-uri=/ws, fast-open=false, udp-relay=false, tag=ss-ws-02
                // ;shadowsocks=example.com:443, method=aes-128-gcm, password=pwd, obfs=wss, obfs-uri=/ws, fast-open=false, udp-relay=false, tag=ss-ws-tls
                $return = ('shadowsocks=' . $item['address'] . ':' . $item['port'] . ', method=' . $item['method'] . ', password=' . $item['passwd']);
                break;
            case 'vmess':
                // ;vmess=example.com:80, method=none, password=23ad6b10-8d1a-40f7-8ad0-e3e35cd32291, fast-open=false, udp-relay=false, tag=vmess-01
                // ;vmess=example.com:80, method=aes-128-gcm, password=23ad6b10-8d1a-40f7-8ad0-e3e35cd32291, fast-open=false, udp-relay=false, tag=vmess-02
                // ;vmess=example.com:443, method=none, password=23ad6b10-8d1a-40f7-8ad0-e3e35cd32291, obfs=over-tls, fast-open=false, udp-relay=false, tag=vmess-tls
                // ;vmess=example.com:80, method=chacha20-poly1305, password=23ad6b10-8d1a-40f7-8ad0-e3e35cd32291, obfs=ws, obfs-uri=/ws, fast-open=false, udp-relay=false, tag=vmess-ws
                // ;vmess=example.com:443, method=chacha20-poly1305, password=23ad6b10-8d1a-40f7-8ad0-e3e35cd32291, obfs=wss, obfs-uri=/ws, fast-open=false, udp-relay=false, tag=vmess-ws-tls
                if (!in_array($item['net'], ['ws', 'tcp'])) {
                    break;
                }
                $return = ('vmess=' . $item['add'] . ':' . $item['port'] . ', method=chacha20-poly1305' . ', password=' . $item['id']);
                switch ($item['net']) {
                    case 'ws':
                        $return .= ($item['tls'] == 'tls' ? ', obfs=wss' : ', obfs=ws');
                        $return .= ', obfs-uri=' . $item['path'] . ', obfs-host=' . $item['host'];
                        break;
                    case 'tcp':
                        $return .= ($item['tls'] == 'tls' ? ', obfs=over-tls' : '');
                        break;
                }
                $return .= (', tag=' . $item['remark']);
                break;
            case 'trojan':
                // ;trojan=example.com:443, password=pwd, over-tls=true, tls-verification=true, fast-open=false, udp-relay=false, tag=trojan-tls-01
                $return = ('trojan=' . $item['address'] . ':' . $item['port'] . ', password= ' . $item['passwd'] . ', tls-host=' . $item['host']);
                $return .= ', over-tls=true, tls-verification=true';
                $return .= (', tag=' . $item['remark']);
                break;
        }
        return $return;
    }

    public static function getSurfboardURI(array $item)
    {
        $return = null;
        switch ($item['type']) {
            case 'ss':
                $return = ($item['remark'] . ' = ss, ' . $item['address'] . ', ' . $item['port'] . ', ' . $item['method'] . ', ' . $item['passwd'] .  ', udp-relay=true');
                break;
            case 'vmess':
                if (!in_array($item['net'], ['ws', 'tcp'])) {
                    break;
                }
                if ($item['tls'] == 'tls') {
                    $tls = ', tls=true';
                    $sni = $item['sni'] ? ', ' . $item['sni'] : '';
                }
                $ws = ($item['net'] == 'ws'
                    ? ', ws=true, ws-path=' . $item['path'] . ', ws-headers=host:' . $item['host']
                    : '');
                $return = $item['remark'] . ' = vmess, ' . $item['add'] . ', ' . $item['port'] . ', username = ' . $item['id'] . $ws . $tls . $sni;
                break;
            case 'trojan':
                $return = ($item['remark'] . ' = trojan, ' . $item['address'] . ', ' . $item['port'] . ', password= ' . $item['passwd']) . ', sni= ' . $item['host'];
                break;    
        }
        return $return;
    }

    public static function getClashURI(array $item, bool $ssr_support = false)
    {
        $return = null;
        switch ($item['type']) {
            case 'ss':
                $return = [
                    'name' => $item['remark'],
                    'type' => 'ss',
                    'server' => $item['address'],
                    'port' => $item['port'],
                    'cipher' => $item['method'],
                    'password' => $item['passwd'],
                    'udp' => true
                ];
                break;
            case 'vmess':
                if (!in_array($item['net'], array('ws', 'tcp', 'grpc'))) {
                    break;
                }
                $return = [
                    'name' => $item['remark'],
                    'type' => 'vmess',
                    'server' => $item['add'],
                    'port' => $item['port'],
                    'uuid' => $item['id'],
                    'alterId' => $item['aid'],
                    'cipher' => 'auto',
                    'udp' => true
                ];
                if ($item['host']) {
                    $return['servername'] = $item['host'];
                }
                if ($item['net'] == 'ws') {
                    $return['network'] = 'ws';
                    $return['ws-opts']['path'] = $item['path'];
                    $return['ws-opts']['headers']['Host'] = ($item['host'] != '' ? $item['host'] : $item['add']);
                }
                if ($item['tls'] == 'tls') {
                    $return['tls'] = true;
                    if ($item['verify_cert'] == false) {
                        $return['skip-cert-verify'] = true;
                    }
                }
                if ($item['net'] == 'grpc') {
                    $return['network'] = 'grpc';
                    $return['servername'] = ($item['host'] != '' ? $item['host'] : $item['add']);
                    $return['grpc-opts']['grpc-service-name'] = ($item['servicename'] != '' ? $item['servicename'] : "");
                }
                break;
            case 'trojan':
                $return = [
                    'name' => $item['remark'],
                    'type' => 'trojan',
                    'server' => $item['address'],
                    'port' => $item['port'],
                    'password' => $item['passwd'],
                    'sni' => $item['host'],
                    'udp' => true
                ];
                if ($item['net'] == 'grpc') {
                    $return['network'] = 'grpc';
                    $return['grpc-opts']['grpc-service-name'] = ($item['servicename'] != '' ? $item['servicename'] : "");
                }
                break;
        }
        return $return;
    }

    public static function getShadowrocketURI(array $item)
    {
        $return = null;
        switch ($item['type']) {
            case 'ss':
                    $return = (self::getShadowsocksURI($item));
                break;
            case 'vmess':
                if (!in_array($item['net'], ['tcp', 'ws', 'http', 'h2'])) {
                    break;
                }
                $obfs = '';
                switch ($item['net']) {
                    case 'ws':
                        $obfs .= ($item['host'] != ''
                            ? ('&obfsParam=' . $item['host'] . '&path=' . $item['path'] . '&obfs=websocket')
                            : ('&obfsParam=' . $item['add'] . '&path=' . $item['path'] . '&obfs=websocket'));
                        break;
                    case 'kcp':
                        $obfs .= 'obfsParam={"header":' . '"' . ($item['headertype'] == '' || $item['headertype'] == 'noop' ? 'none' : $item['headertype']) . '"' . '}&obfs=mkcp';
                        break;
                    case 'mkcp':
                        $obfs .= 'obfsParam={"header":' . '"' . ($item['headertype'] == '' || $item['headertype'] == 'noop' ? 'none' : $item['headertype']) . '"' . '}&obfs=mkcp';
                        break;
                    case 'h2':
                        $obfs .= ($item['host'] != ''
                            ? ('&obfsParam=' . $item['host'] . '&path=' . $item['path'] . '&obfs=h2')
                            : ('&obfsParam=' . $item['add'] . '&path=' . $item['path'] . '&obfs=h2'));
                        break;
                    default:
                        $obfs .= '&obfs=none';
                        break;
                }
                $tls = '';
                if ($item['tls'] == 'tls') {
                    $tls = '&tls=1';
                    if ($item['verify_cert'] == false) {
                        $tls .= '&allowInsecure=1';
                    }
                    $tls .= ($item['sni']
                        ? ('&peer=' . $item['sni'])
                        : ('&peer=' . $item['host']));
                }
                $return = ('vmess://' . Tools::base64_url_encode('auto:' . $item['id'] . '@' . $item['add'] . ':' . $item['port']) . '?remarks=' . rawurlencode($item['remark']) . $obfs . $tls . '&alterId=' . $item['aid']);
                break;
            case 'vless':
                if (!in_array($item['net'], ['tcp', 'ws', 'http', 'h2'])) {
                    break;
                }
                $obfs = '';
                switch ($item['net']) {
                    case 'ws':
                        $obfs .= ($item['host'] != ''
                            ? ('&obfsParam=' . $item['host'] . '&path=' . $item['path'] . '&obfs=websocket')
                            : ('&obfsParam=' . $item['add'] . '&path=' . $item['path'] . '&obfs=websocket'));
                        break;
                    case 'kcp':
                        $obfs .= 'obfsParam={"header":' . '"' . ($item['headertype'] == '' || $item['headertype'] == 'noop' ? 'none' : $item['headertype']) . '"' . '}&obfs=mkcp';
                        break;
                    case 'mkcp':
                        $obfs .= 'obfsParam={"header":' . '"' . ($item['headertype'] == '' || $item['headertype'] == 'noop' ? 'none' : $item['headertype']) . '"' . '}&obfs=mkcp';
                        break;
                    case 'h2':
                        $obfs .= ($item['host'] != ''
                            ? ('&obfsParam=' . $item['host'] . '&path=' . $item['path'] . '&obfs=h2')
                            : ('&obfsParam=' . $item['add'] . '&path=' . $item['path'] . '&obfs=h2'));
                        break;
                    default:
                        $obfs .= '&obfs=none';
                        break;
                }
                $tls = '';
                if ($item['tls'] == 'tls') {
                    $tls = '&tls=1';
                    if ($item['verify_cert'] == false) {
                        $tls .= '&allowInsecure=1';
                    }
                    $tls .= ($item['sni']
                        ? ('&peer=' . $item['sni'])
                        : ('&peer=' . $item['host']));
                } else {
                    $tls = '&tls=1';
                    
                    if ($item['verify_cert'] == false) {
                        $tls .= '&allowInsecure=1';
                    }
                    $tls .= ($item['sni']
                        ? ('&peer=' . $item['sni'])
                        : ('&peer=' . $item['host']));
                    $tls .= '&xtls=1';
                }
                $return = ('vless://' . Tools::base64_url_encode('auto:' . $item['id'] . '@' . $item['add'] . ':' . $item['port']) . '?remarks=' . rawurlencode($item['remark']) . $obfs . $tls . '&alterId=' . $item['aid']);
                break;
            case 'trojan':
                $return = ('trojan://' . $item['passwd'] . '@' . $item['address'] . ':' . $item['port']);
                $return .= ('?peer=' . $item['host'] . '#' . rawurlencode($item['remark']));
                break;
        }
        return $return;
    }

    public static function getKitsunebiURI(array $item)
    {
        $return = null;
        switch ($item['type']) {
            case 'ss':
                $return = (self::getShadowsocksURI($item));
                break;
            case 'vmess':
                $network = ($item['net'] == 'tls'
                    ? '&network=tcp'
                    : ('&network=' . $item['net']));
                $protocol = '';
                switch ($item['net']) {
                    case 'kcp':
                        $protocol .= ('&kcpheader=' . $item['headertype']);
                        break;
                    case 'ws':
                        $protocol .= ('&wspath=' . $item['path'] . '&wsHost=' . $item['host']);
                        break;
                    case 'h2':
                        $protocol .= ('&h2Path=' . $item['path'] . '&h2Host=' . $item['host']);
                        break;
                }
                $tls = '';
                if ($item['tls'] == 'tls') {
                    $tls = '&tls=1';
                    if ($item['verify_cert'] == false) {
                        $tls .= '&allowInsecure=1';
                    }
                }
                $return .= ('vmess://' . base64_encode('auto:' . $item['id'] . '@' . $item['add'] . ':' . $item['port']) . '?remark=' . rawurlencode($item['remark']) . $network . $protocol . '&aid=' . $item['aid'] . $tls);
                break;
        }
        return $return;
    }
    
    public static function getTrojanURI(array $item)
    {
        $return = null;
        switch ($item['type']) {
            case 'trojan':
                $return  = ('trojan://' . $item['passwd'] . '@' . $item['address'] . ':' . $item['port']);
                $return .= ('?peer=' . $item['host'] . '&sni=' . $item['host']);
                if($item['tls'] == "xtls"){
                   $return.=("&security=".$item['tls']."&flow=".$item['flow']);
                }
                if ($item['net'] === 'grpc') {
                    $params = [];
                    // shadowrocket
                    $params['obfs'] = 'grpc';
                    $params['path'] = $item['servicename'];
                    $params['obfsParam'] = $item['host'];
                    // v2rayn
                    $params['type'] = 'grpc';
                    $params['security'] = 'tls';
                    $params['serviceName'] = $item['servicename'];
                    $return .= '&' . http_build_query($params);
                }
                $return.=('#' .  rawurlencode($item['remark']));
                break;
        }
        return $return;
    }

    public static function getAnXrayURI(array $item)
    {
        $return = null;
        switch ($item['type']) {
            case 'ss':
                $return = self::getShadowsocksURI($item);
                break;				
            case 'vmess':
                $return = ('vmess://' . $item['id'] ."@".$item['add'].":".$item['port']."?encryption=auto");
                $return.="&type=".$item['net'];
                $return.="&security=".$item['tls'];
                if($item['tls'] == "xtls"){
                   $return.="&flow=".$item['flow'];
                }
                if($item['host']!="")$return=$return."&host=". rawurlencode($item['host']);
                if($item['host']!="")$return=$return."&sni=".$item['host'];	
                if($item['path']!="")$return=$return."&path=".rawurlencode($item['path']);
                if($item['net'] == "grpc"){
                   if($item['net'] == "grpc")$return=$return."&mode=multi&serviceName=".$item['servicename'];
                }else{
                   if($item['headertype']!="")$return=$return."&headerType=".$item['headertype'];
                }
                if ($item['remark']!="")$return=$return."#". rawurlencode($item['remark']);
                break;
            case 'trojan':
                $return  = ('trojan://' . $item['passwd'] . '@' . $item['address'] . ':' . $item['port']);
                $return .= ('?peer=' . $item['host'] . '&sni=' . $item['host'] );
                if($item['tls'] == "xtls"){
                   $return.=("&security=".$item['tls']."&flow=".$item['flow']);
                }
                $return.=('#' .  rawurlencode($item['remark']));
                break;
            case 'vless':
                $return = ('vless://' . $item['id'] ."@".$item['add'].":".$item['port']."?encryption=none");
                $return.="&type=".$item['net'];
                $return.="&security=".$item['tls'];
                if($item['tls'] == "xtls"){
                   $return.="&flow=".$item['flow'];
                }
                if($item['host']!="")$return=$return."&host=". rawurlencode($item['host']);
                if($item['host']!="")$return=$return."&sni=".$item['host'];	
                if($item['path']!="")$return=$return."&path=".rawurlencode($item['path']);
                if($item['net'] == "grpc"){
                   if($item['net'] == "grpc")$return=$return."&mode=multi&serviceName=".$item['servicename'];
                }else{
                   if($item['headertype']!="")$return=$return."&headerType=".$item['headertype'];
                }
                if ($item['remark']!="")$return=$return."#". rawurlencode($item['remark']);
                break;
        }
        return $return;
    }	
}
