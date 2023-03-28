<?php

namespace App\Utils\Telegram;

use Telegram\Bot\Api;
use Exception;

class Process
{
    public static function index()
    {
        try {
            $bot = new Api(Setting::obtain('telegram_bot_token'));
            $bot->addCommands(
                [
                    Commands\PingCommand::class,
                    Commands\StartCommand::class,
                    Commands\UnbindCommand::class,
                ]
            );
            $update = $bot->commandsHandler(true);
            $Message = $update->getMessage();
            
            if ($update->getCallbackQuery() !== null) {
                new Callbacks\Callback($bot, $update->getCallbackQuery());
            } else if ($Message->getReplyToMessage() != null) {
                if (preg_match("/[#](.*)/", $Message->getReplyToMessage()->getText(), $match)) {
                    new Callbacks\ReplayTicket($bot, $Message, $match[1]);
                }
            } else if ($Message !== null) {
                new Message($bot, $update->getMessage());
            }
            
        } catch (Exception $e) {
            $e->getMessage();
        }
    }
}
