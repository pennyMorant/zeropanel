<?php
declare(strict_types=1);

namespace App\Utils\Telegram\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

/**
 * Class PingCommand.
 */
final class PingCommand extends Command
{
    protected string $name = 'ping';
    protected string $description = '获取我的唯一ID';

    public function handle()
    {
        // 发送 '输入中' 会话状态
        $this->replyWithChatAction(['action' => Actions::TYPING]);

        $update = $this->getUpdate();
        $message = $update->getMessage();
        // 消息会话 ID
        $chatId = $message->getChat()->getId();

        // 回送信息
        $this->replyWithMessage(
            [
                'text'    => '您的 ID 是: ' . $chatId,
                'chat_id' => $chatId,
            ]
        );
        
    }
}
