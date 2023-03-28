<?php
declare(strict_types=1);

namespace App\Utils\Telegram\Commands;

use App\Models\User;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use App\Utils\TelegramSessionManager;

final class BindCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'bind';

    /**
     * @var string Command Description
     */
    protected $description = '绑定账户';

    public function handle()
    {
        $this->replyWithChatAction(['action' => Actions::TYPING]);
        $message = $this->getUpdate()->getMessage();
        $text = $message->getText();
        $messageId = $message->getMessageId();
        $chatId = $message->getChat()->getId();
        $args = explode('', $text);
        $token = $args[1];

        
        if (is_null($token)) {
            $this->replyWithMessage(
                [
                    'text' => '请输入telegram token',
                    'reply_to_message_id' => $messageId,
                ]
            );
            return;
        }
        $id = TelegramSessionManager::verifyBindSession($token);

        $user = User::where('id', $id)->first();
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
        
        if ($user->is_admin >= 1) {
            $text = '当前绑定邮箱为： ' . $user->email;
        } else {
            if ($user->class >= 1) {
                $text = '恭喜您绑定成功，当前绑定邮箱为： ' . $user->email;
            } else {
                $text = '绑定成功了，您的邮箱为：' . $user->email;
            }
        }

        // 回送信息
        $this->replyWithMessage(
            [
                'text' => $text,
                'parse_mode' => 'Markdown',
            ]
        );
    }
}