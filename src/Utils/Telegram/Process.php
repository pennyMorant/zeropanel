<?php
declare(strict_types=1);

namespace App\Utils\Telegram;

use Telegram\Bot\Api;
use App\Models\Setting;
use Telegram\Bot\Exceptions\TelegramResponseException;

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
        $Message = $update->getMessage();
   
        if (!is_null($Message->getReplyToMessage())) {
            if (preg_match('/#(\d+)/', $Message->getReplyToMessage()->getText(), $match)) {
                new Callbacks\ReplayTicket($bot, $Message, $match[1]);
            }
        }
    }
}
