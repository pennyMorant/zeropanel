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

        $replyMarkup = $this->buildInlineKeyboardMarkup([
            ['绑定账号'],
        ]);

        // 回送信息
        $this->replyWithMessage(
            [
                'text' => 'Hello:\n'.$replyMarkup,
                'parse_mode' => 'MarkdownV2',
            ]
        );
    }

    protected function buildInlineKeyboardMarkup($buttons)
    {
        $result = '';
        foreach ($buttons as $row) {
            $buttonRow = '';
            foreach ($row as $button) {
                $buttonRow .= "[$button] ";
            }
            $result .= rtrim($buttonRow) . "\n";
        }
        return $result;
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