<?php

namespace App\Zero;

use Exception;
use Telegram\Bot\Api;
use App\Models\{
    User, 
    Product, 
    Ticket, 
    Setting
};
use League\HTMLToMarkdown\HtmlConverter;

class Telegram
{
    /**
     *  用户充值 给管理员TG提醒
     */
    public static function pushTopUpResponse($user, $order)
    {
        $orders = Order::find($order);

        $messageText = '交易提醒' . PHP_EOL .
            '------------------------------' . PHP_EOL .
            '用户：' . $user->email . '  #' . $user->id . PHP_EOL .
            '充值金额：' . $orders->order_total . PHP_EOL .
            '完成时间：' . $orders->updated_time . PHP_EOL .

        $sendAdmin = Setting::obtain('telegram_admin_id');
        $admin_telegram_id = User::where('id', $sendAdmin)->where('is_admin', '1')->value('telegram_id');
        if ($admin_telegram_id != null) {
            self::Send($messageText, $admin_telegram_id);
        }
    }

    /**
     * deleteMessage 删除消息
     * editMessageText 编辑消息
     */
    public static function SendPost($Method, $Params)
    {

        //file_put_contents(BASE_PATH . '/storage/telegram.log', '发送：'.json_encode($Params, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) . PHP_EOL. PHP_EOL, FILE_APPEND);  // 记录日志
        $URL = 'https://api.telegram.org/bot' . Setting::obtain('telegram_bot_token') . '/' . $Method;
        $POSTData = json_encode($Params);
        $C = curl_init();
        curl_setopt($C, CURLOPT_URL, $URL);
        curl_setopt($C, CURLOPT_POST, 1);
        curl_setopt($C, CURLOPT_HTTPHEADER, ['Content-Type:application/json; charset=utf-8']);
        curl_setopt($C, CURLOPT_POSTFIELDS, $POSTData);
        curl_setopt($C, CURLOPT_TIMEOUT, 1);
        curl_exec($C);
        curl_close($C);
    }

    public static function Send($messageText, $chat_id, $keyboard = null)
    {
        // 发送给非群组时使用异步
        //$async = (!in_array($chat_id, (array)json_decode(Setting::obtain('telegram_admin_id'))));
        $bot = new Api(Setting::obtain('telegram_bot_token'), true);

        if ($keyboard !== null) {
            $reply_markup = json_encode(
                [
                    'inline_keyboard' => $keyboard
                ]
            );
        } else {
            $reply_markup = null;
        }

        $sendMessage = [
            'chat_id' => $chat_id,
            'text' => $messageText,
            'parse_mode' => '',
            'disable_web_page_preview' => false,
            'reply_to_message_id' => null,
            'reply_markup' => $reply_markup
        ];
        try {
            $bot->sendMessage($sendMessage);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
