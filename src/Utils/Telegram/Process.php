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
                ]
            );
            $update = $bot->commandsHandler(true);
            $message = $update->getMessage();
            
            if ($update->getCallbackQuery() !== null) {
                new Callbacks\Callback($bot, $update->getCallbackQuery());
            } else if ($message->getReplyToMessage() != null) {
                if (preg_match("/[#](.*)/", $message->getReplyToMessage()->getText(), $match)) {
                    new Callbacks\ReplayTicket($bot, $message, $match[1]);
                }
            } else if ($message !== null) {
                new Message($bot, $update->getMessage());
            }
            
        } catch (Exception $e) {
            $e->getMessage();
        }
    }
}
