<?php

declare(strict_types=1);

namespace App\Utils\Telegram\Commands;

use App\Models\Setting;
use App\Models\User;
use App\Utils\TelegramSessionManager;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Keyboard\Keyboard;

/**
 * Class StratCommand.
 */
final class StartCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'start';

    /**
     * @var string Command Description
     */
    protected $description = 'Bot 初始命令.';

    public function handle()
    {
        // 发送 '输入中' 会话状态
        $this->replyWithChatAction(['action' => Actions::TYPING]);

        $replyMarkup = $this->buildReplyKeyboardMarkup([
            ['text' =>  '绑定账号', 'callback_data' => '/bind_account'],
        ]);

        // 回送信息
        $this->replyWithMessage(
            [
                'text' => 'Hello',
                'reply_markup' => $replyMarkup,
            ]
        );
    }

    protected function buildReplyKeyboardMarkup($buttons)
    {
        return Keyboard::make([
            'keyboard' => $buttons,
            'resize_keyboard' => true,
            'one_time_keyboard' => true,
        ]);
    }

    public function bindingAccount($SendUser, $MessageText): void
    {
        $Uid = TelegramSessionManager::verifyBindSession($MessageText);
        if ($Uid === 0) {
            $text = '绑定失败了呢，经检查发现：【' . $MessageText . '】的有效期为 10 分钟，您可以在我们网站上的 **资料编辑** 页面刷新后重试.';
        } else {
            $BinsUser = User::where('id', $Uid)->first();
            $BinsUser->telegram_id = $SendUser['id'];
            $BinsUser->save();
            if ($BinsUser->is_admin >= 1) {
                $text = '尊敬的 **管理员** 您好，恭喜绑定成功。' . PHP_EOL . '当前绑定邮箱为： ' . $BinsUser->email;
            } else {
                if ($BinsUser->class >= 1) {
                    $text = '尊敬的 **VIP ' . $BinsUser->class . '** 用户您好.' . PHP_EOL . '恭喜您绑定成功，当前绑定邮箱为： ' . $BinsUser->email;
                } else {
                    $text = '绑定成功了，您的邮箱为：' . $BinsUser->email;
                }
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