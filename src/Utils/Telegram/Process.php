<?php
declare(strict_types=1);

namespace App\Utils\Telegram;

use Telegram\Bot\Api;
use App\Models\Setting;
use App\Utils\Telegram\Callbacks\ReplayTicket;

final class Process
{
    public static function commandBot()
    {
        $bot = new Api(Setting::obtain('telegram_bot_token'));
        $bot->addCommands(
            [
                Commands\PingCommand::class,
                Commands\StartCommand::class,
                Commands\BindCommand::class,
                Commands\UnbindCommand::class,
                Commands\MyCommand::class,
            ]
        );
        $update = $bot->commandsHandler(true);
        // 处理用户发送的消息
        
        $update_2 = $bot->getWebhookUpdate();
        $message = $update_2->getMessage();
        if ($message->getReplyToMessage() != null) {
            // 如果用户发送了一条文本消息，我们就直接回复这条消息
            
            $chat_id = $update_2->getMessage()->getChat()->getId();
            $reply = $message->getReplyToMessage()->getText();
            
            if (preg_match('/#(\d+)/', $reply, $matches)) {
                new ReplayTicket($bot, $message, $matches[1]);
            }
        }
    }
}
