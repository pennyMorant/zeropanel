<?php

namespace App\Services;

use App\Utils\Geetest;
use App\Models\Setting;

class Captcha
{
    public static function generate(): array
    {
        switch (Setting::obtain('captcha_provider'))
        {
            case 'turnstile':
                return [
                    'turnstile_sitekey' => Setting::obtain('turnstile_sitekey'),
                ];
                break;
        }

        return [];
    }

    /**
     * 获取验证结果
     */
    public static function verify($param): bool
    {
        $result = false;
        switch (Setting::obtain('captcha_provider'))
        {
            case 'turnstile':
                if (isset($param['turnstile'])) {
                    $postdata = http_build_query(
                        [
                            'secret' => Setting::obtain('turnstile_secret'),
                            'response' => $param['turnstile'],
                        ]
                    );
                    $opts = ['http' => [
                        'method' => 'POST',
                        'header' => 'Content-Type: application/x-www-form-urlencoded',
                        'content' => $postdata,
                    ],
                    ];
                    $json = @file_get_contents('https://challenges.cloudflare.com/turnstile/v0/siteverify', false, stream_context_create($opts));
                    $result = \json_decode($json)->success;
                }
                break;
        }
        return $result;
    }
}