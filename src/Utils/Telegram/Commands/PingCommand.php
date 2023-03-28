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
    /**
     * @var string Command Name
     */
    protected $name = 'ping';

    /**
     * @var string Command Description
     */
    protected $description = '获取我的唯一ID';

    /**
     * {@inheritdoc}
     */
    public function handle($arguments)
    {
        $Update = $this->getUpdate();
        $Message = $Update->getMessage();

        // 消息会话 ID
        $ChatID = $Message->getChat()->getId();

        // 发送 '输入中' 会话状态
        $this->replyWithChatAction(['action' => Actions::TYPING]);

        $text = [
            'Pong！',
            '这个群组的 ID 是 ' . $ChatID . '.',
        ];

        // 回送信息
        $this->replyWithMessage(
            [
                'text'       => implode(PHP_EOL, $text),
                'parse_mode' => 'Markdown',
            ]
        );
        
    }
}
