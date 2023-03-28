<?php

declare(strict_types=1);

namespace App\Utils\Telegram;

use App\Models\Bought;
use App\Models\User;

final class Reply
{
    /**
     * [admin]获取用户信息
     */
    public static function getUserInfoFromAdmin(User $user, int $ChatID): string
    {
        $strArray = [
            '#' . $user->id . ' ' . $user->user_name . ' 的用户信息',
            '',
            '用户邮箱：' . TelegramTools::getUserEmail($user->email, $ChatID),
            '账户余额：' . $user->money,
            '账户状态：' . ((int) $user->is_banned === 1 ? '封禁' : '正常'),
            '用户等级：' . $user->class,
            '剩余流量：' . $user->unusedTraffic(),
            '等级到期：' . $user->class_expire,
        ];
        return implode(PHP_EOL, $strArray);
    }
}