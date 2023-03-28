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
        }
    }

    /**
     * Handle a Telegram update.
     *
     * @param array $update
     *
     * @return void
     * @throws TelegramResponseException
     */
    public function handleUpdate($update)
    {
        $message = $update['message'];

        if (isset($message['text'])) {
            $this->handleMessage($message);
        }
    }

    /**
     * Handle an incoming message.
     *
     * @param array $message
     *
     * @return void
     */
    public function handleMessage($message)
    {
        $chatId = $message->getChat()->getId();
        $text = $message->getText();
        
        switch ($text) {
            case '绑定账号':
                // 处理绑定帐户命令
                $this->replyWithMessage([
                    'text' => '请输入您的帐户信息。',
                    'chat_id' => $chatId,
                ]);
                break;
            default:
                // 处理其他消息
                $this->replyWithMessage([
                    'text' => '您选择的是: ' . $text,
                    'chat_id' => $chatId,
                ]);
                break;
        }
    }
}
