<?php
declare(strict_types=1);

namespace App\Utils\Telegram\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use App\Models\User;

/**
 * Class MyCommand.
 */
final class MyCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'my';

    /**
     * @var string Command Description
     */
    protected $description = '用户信息';

    public function handle()
    {
        $this->replyWithChatAction(['action' => Actions::TYPING]);
        $message = $this->getUpdate()->getMessage();
        $messageId = $message->getMessageId();
        $chatId = $message->getChat()->getId();
        
        $user = User::where('telegram_id', $chatId)->first();
        if (is_null($user)) {
            $this->replyWithMessage(
                [
                    'text' => '您还没有绑定账号',
                    'reply_to_message_id' => $messageId,
                ]
            );
            return;
        }
        
        $text = [
            '订阅到期时间: ' . $user->class_expire,
            '订阅流量: ' . $user->usedTraffic() . '/' . $user->enableTrafficInGB() . 'GB',
            '流量下次重置时间: ' . $user->productTrafficResetDate(),
            '账户余额: ' . $user->money,
        ];

        // 回送信息
        $this->replyWithMessage(
            [
                'text' => implode(PHP_EOL, $text),
                'parse_mode' => 'Markdown',
            ]
        );
    }
}