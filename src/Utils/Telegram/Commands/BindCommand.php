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
        $Uid = TelegramSessionManager::verifyBindSession($this->getUpdate()->getMessage());

        $BinsUser = User::where('id', $Uid)->first();
        $BinsUser->telegram_id = $this->getUpdate()->getMessage()->getChat()->getId();
        $BinsUser->save();
        $this->replyWithChatAction(['action' => Actions::TYPING]);
        if ($BinsUser->is_admin >= 1) {
            $text = '当前绑定邮箱为： ' . $BinsUser->email;
        } else {
            if ($BinsUser->class >= 1) {
                $text = '恭喜您绑定成功，当前绑定邮箱为： ' . $BinsUser->email;
            } else {
                $text = '绑定成功了，您的邮箱为：' . $BinsUser->email;
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