<?php

declare(strict_types=1);

namespace App\Utils\Telegram\Commands;

use App\Models\Setting;
use App\Models\User;
use App\Utils\Telegram\Reply;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use function json_encode;

/**
 * Class MyCommand.
 */
final class MyCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'my';

    /**
     * @var string Command Description
     */
    protected $description = '[群组/私聊] 我的个人信息.';

    /**
     * {@inheritdoc}
     */
    public function handle()
    {
        $Update = $this->getUpdate();
        $Message = $Update->getMessage();

        // 消息 ID
        $MessageID = $Message->getMessageId();

        // 消息会话 ID
        $ChatID = $Message->getChat()->getId();

        if ($ChatID < 0) {
            if ($ChatID !== Setting::obtain('telegram_group_id')) {
                // 非我方群组
                return null;
            }
        }

        // 发送 '输入中' 会话状态
        $this->replyWithChatAction(['action' => Actions::TYPING]);

        // 触发用户
        $SendUser = [
            'id' => $Message->getFrom()->getId(),
            'name' => $Message->getFrom()->getFirstName() . ' ' . $Message->getFrom()->getLastName(),
            'username' => $Message->getFrom()->getUsername(),
        ];

        $User = User::where('telegram_id', $SendUser['id'])->first();
        
            if ($ChatID > 0) {
                // 私人
                $response = $this->triggerCommand('menu');
            } else {
                // 群组
                $response = $this->group($User, $SendUser, $ChatID, $Message, $MessageID);
            }
        

        return $response;
    }

    public function group($User, $SendUser, $ChatID, $Message, $MessageID)
    {
        $text = Reply::getUserTitle($User);
        $text .= PHP_EOL . PHP_EOL;
        $text .= Reply::getUserTrafficInfo($User);
        $text .= PHP_EOL;
        $text .= '流量重置时间：' . $User->productTrafficResetDate();
        // 回送信息
        return $this->replyWithMessage(
            [
                'text' => $text,
                'parse_mode' => 'Markdown',
                'reply_to_message_id' => $MessageID,
            ]
        );
    }
}