<?php

namespace App\Utils;

use App\Models\{
    User,
    Node
};

class URL
{
    /**
     * 获取 SS URL
     */
    public static function getShadowsocksURL(User $user, Node $node, bool $emoji = false): string
    {
        $node_config = $node->getShadowsocksConfig($user, $node->custom_config, $emoji);
        $ip_type = Tools::isIP($node_config['address']);
        $address = ($ip_type === 'v6' ? '[%s]' : '%s');              
        if (Node::getShadowsocksSupportMethod($node_config['method'])) {                   
            $url = sprintf(
                'ss://%s@'.$address.':%d#%s',
                base64_encode($node_config['method'] . ':' . $node_config['passwd']),
                $node_config['address'],
                $node_config['port'],
                rawurlencode($node_config['remark'])
            );
        } else {
            $url = sprintf(
                'ss://%s@'.$address.':%d#%s',
                base64_encode($node_config['method'] . ':' . $node_config['server_psk'] . ':' . $node_config['passwd']),
                $node_config['address'],
                $node_config['port'],
                rawurlencode($node_config['remark'])
            );
        }
        return $url;
    }


    /**
     * 获取 Vmess URL
     */
    public static function getVmessURL(User $user, Node $node, bool $emoji = false): string
    {
        $node_config = $node->getVmessConfig($user, $node->custom_config, $emoji);
        $url= sprintf(
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
        return $url;
    }

    /**
     * 获取 VLESS URL
     */
    public static function getVlessURL(User $user, Node $node, bool $emoji = false): string
    {
        $node_config = $node->getVlessConfig($user, $node->custom_config, $emoji);

        $url= sprintf(
            'vless://%s@%s:%d?encryption=none&host=%s&path=%s&flow=%s&security=%s&sni=%s&serviceName=%s&headerType=%s&type=%s#%s',
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
        return $url;
    }
    
    /**
     * 获取 Trojan URL
     */
    public static function getTrojanURL(User $user, Node $node, bool $emoji = false): string
    {
        $node_config = $node->getTrojanConfig($user, $node->custom_config, $emoji);
        $url= sprintf(
            'trojan://%s@%s:%s?flow=%s&security=%s&sni=%s#%s',
            $node_config['uuid'],
            $node_config['address'],
            $node_config['port'],
            $node_config['flow'],
            $node_config['security'],
            $node_config['sni'],
            rawurlencode($node_config['remark'])
        );
        return $url;
    }

    public static function getHysteriaURL(User $user, Node $node, bool $emoji = false): string
    {
        $node_config = $node->getHysteriaConfig($user, $node->custom_config, $emoji);
        $ip_type = Tools::isIP($node_config['address']);
        $address = ($ip_type === 'v6' ? '[%s]' : '%s');
        $url = sprintf(
            'hysteria://'.$address.':%d?protocol=%s&auth=%s&peer=%s&upmbps=%s&downmbps=%s&obfs=%s&obfsParam=%s#%s',
            $node_config['address'],
            $node_config['port'],
            $node_config['protocol'],
            $node_config['auth'],
            $node_config['peer'],
            $node_config['upmbps'],
            $node_config['downmbps'],
            $node_config['obfs'],
            $node_config['obfsParam'],
            rawurlencode($node_config['remark'])
        );

        return $url;
    }
}
