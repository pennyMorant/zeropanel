<?php

declare(strict_types=1);

namespace App\Utils\Telegram\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Keyboard\Keyboard;

/**
 * Class StratCommand.
 */
final class StartCommand extends Command
{
    protected string $name = 'start';
    protected string $description = 'Bot 初始命令.';

    public function handle()
    {
        $chatId = $this->getUpdate()->getMessage()->getChat()->getId();

        // 发送 '输入中' 会话状态
        $this->replyWithChatAction(['action' => Actions::TYPING]);
/*
        $replyMarkup = $this->buildReplyKeyboardMarkup([
            ['绑定账号', '解除绑定'],
        ]);
*/
        // 回送信息
        $this->replyWithMessage(
            [
                'text'    => '绑定账户命令: /bind "telegram token"',
                'chat_id' => $chatId,
            ]
        );
    }
}