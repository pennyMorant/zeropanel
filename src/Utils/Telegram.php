<?php

namespace App\Utils;

use Exception;
use Telegram\Bot\Api;
use App\Models\Setting;

class Telegram
{
    /**
     * 发送讯息，默认给群组发送
     *
     * @param string $messageText
     * @param int    $chat_id
     */
    public static function Send($messageText, $chat_id = 0): void
    {
        if ($chat_id === 0) {
            $chat_id = Setting::obtain('telegram_group_id');
        }
        if (Setting::obtain('enable_telegram_bot') == true) {
            
            $bot = new Api(Setting::obtain('telegram_bot_token'), true);
            $sendMessage = [
                'chat_id'                   => $chat_id,
                'text'                      => $messageText,
                'parse_mode'                => '',
                'disable_web_page_preview'  => false,
                'reply_to_message_id'       => null,
                'reply_markup'              => null
            ];
            $bot->sendMessage($sendMessage);
            
        }
    }

    /**
     * 以 Markdown 格式发送讯息，默认给群组发送
     *
     * @param string $messageText
     * @param int    $chat_id
     */
    public static function SendMarkdown(string $messageText, int $chat_id = 0): void
    {
        if ($chat_id === 0) {
            $chat_id = Setting::obtain('telegram_group_id');
        }
        if (Setting::obtain('enable_telegram_bot') == true) {
            
                // 发送给非群组时使用异步
                $async = ($chat_id != Setting::obtain('telegram_group_id'));
                $bot = new Api(Setting::obtain('telegram_bot_token'), $async);
                $sendMessage = [
                    'chat_id'                   => $chat_id,
                    'text'                      => $messageText,
                    'parse_mode'                => 'Markdown',
                    'disable_web_page_preview'  => false,
                    'reply_to_message_id'       => null,
                    'reply_markup'              => null
                ];
                try {
                    $bot->sendMessage($sendMessage);
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
        }
    }
    
    /**
     * 发送讯息，默认给管理员
     *
     * @param string $messageText
     * @param int    $chat_id
     */
    public static function pushToAdmin($messageText, $keyBoard = null): void
    {
        $chat_ids = json_decode(Setting::obtain('telegram_admin_id'), true);
        $enable_bot = Setting::obtain('enable_telegram_bot');
        if (array_filter($chat_ids) && $enable_bot) {         
            $bot = new Api(Setting::obtain('telegram_bot_token'), false);
            if (!is_null($keyBoard)) {
                $reply_markup = json_encode(
                    [
                        'inline_keyboard' => $keyBoard
                    ]
                );
            } else {
                $reply_markup = null;
            }
            foreach ($chat_ids as $chat_id) {
                $sendMessage = [
                    'chat_id'                   => $chat_id,
                    'text'                      => $messageText,
                    'parse_mode'                => '',
                    'disable_web_page_preview'  => false,
                    'reply_to_message_id'       => null,
                    'reply_markup'              => $reply_markup
                ];
                try {
                    $bot->sendMessage($sendMessage);
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
            }
        }
    }

    /**
     * push message to channel
     *
     * @param string $messageText
     * @param int    $chat_id
     */
    public static function pushToChannel($messageText): void
    { 
        if (Setting::obtain('enable_telegram_bot')) {
            $chat_id = Setting::obtain('telegram_channel_id');
            $bot = new Api(Setting::obtain('telegram_bot_token'), false);
            $sendMessage = [
                'chat_id'                   => $chat_id,
                'text'                      => $messageText,
                'parse_mode'                => 'Markdown',
                'disable_web_page_preview'  => false,
            ];
            try {
                $bot->sendMessage($sendMessage);
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }
    }
}
