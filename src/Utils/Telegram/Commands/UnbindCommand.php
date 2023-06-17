<?php
declare(strict_types=1);

namespace App\Utils\Telegram\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use App\Models\User;

/**
 * Class UnbindCommand.
 */
final class UnbindCommand extends Command
{
    protected string $name = 'unbind';
    protected string $description = '解除账户绑定';

    public function handle()
    {
        $this->replyWithChatAction(['action' => Actions::TYPING]);
        $message   = $this->getUpdate()->getMessage();
        $messageId = $message->getMessageId();
        $chatId    = $message->getChat()->getId();
        
        $user = User::where('telegram_id', $chatId)->first();
        if (is_null($user)) {
            $this->replyWithMessage(
                [
                    'text'                => '您还没有绑定账号',
                    'reply_to_message_id' => $messageId,
                ]
            );
            return;
        }
        $user->telegram_id = NULL;
        $user->save();

          // 回送信息
        $this->replyWithMessage(
            [
                'text'    => '您已经解除绑定',
                'chat_id' => $chatId,
            ]
        );
    }
}