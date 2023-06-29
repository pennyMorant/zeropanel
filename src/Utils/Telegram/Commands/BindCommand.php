<?php
declare(strict_types=1);

namespace App\Utils\Telegram\Commands;

use App\Models\User;
use App\Models\Token;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

final class BindCommand extends Command
{
    protected string $name = 'bind';
    protected string $description = '绑定账户';

    public function handle()
    {
        $this->replyWithChatAction(['action' => Actions::TYPING]);
        $response = $this->getUpdate();
        $message = $response->getMessage();
        $text = $message->getText();
        $messageId = $message->getMessageId();
        $chatId = $message->getChat()->getId();

        if ($text != '/bind') {
            $args = explode(' ', $text);
            $token = $args[1];
        } else {      
            $this->replyWithMessage(
                [
                    'text' => '请输出入绑定token, 格式: /bind token',
                    'reply_to_message_id' => $messageId,
                ]
            );
            return;
        }

        $result = Token::where('token', $token)->where('type', 1)->first();

        if ($result->expired_at < time()) {
            $this->replyWithMessage(
                [
                    'text' => '当前token已经失效，请刷新网页重新获取token',
                    'reply_to_message_id' => $messageId,
                ]
            );
            return;
        }

        $user = User::where('id', $result->user_id)->first();
        
        if (!is_null($user->telegram_id) == $chatId) {
            $this->replyWithMessage(
                [
                    'text' => '已经绑定了账号，无需再次绑定. 如需绑定其他账号，请先解除绑定。',
                    'reply_to_message_id' => $messageId,
                ]
            );
            return;
        }
        $user->telegram_id = $chatId;
        $user->save();
        $text = '绑定成功了，您的邮箱为：' . $user->email;

        // 回送信息
        $this->replyWithMessage(
            [
                'text'       => $text,
                'chat_id'    => $chatId,
                'parse_mode' => 'Markdown',
            ]
        );
    }
}