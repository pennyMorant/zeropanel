<?php


namespace App\Utils\Telegram\Callbacks;


use App\Utils\Telegram;
use App\Models\{
    Ticket,
    User,
};
use Telegram\Bot\Actions;
use voku\helper\AntiXSS;

class ReplayTicket
{
    /**
     * Bot
     */
    protected $bot;

    /**
     * 触发用户
     */
    protected $User;

    /**
     * 触发用户TG信息
     */
    protected $triggerUser;

    /**
     * 回调
     */
    protected $Callback;

    /**
     * 消息会话 ID
     */
    protected $ChatID;

    /**
     * 触发源信息 ID
     */
    protected $MessageID;

    /**
     * 工单ID
     */
    protected $TickedId;

    /**
     * @param \Telegram\Bot\Api $bot
     * @param \Telegram\Bot\Objects\Message $Message
     * @param $ticketId
     */
    public function __construct($bot, $Message, $ticketId)
    {
        $bot->sendChatAction([
            'chat_id' => $Message->getChat()->getId(),
            'action'  => Actions::TYPING,
        ]);
        $this->bot              = $bot;
        $AdminUser = User::where('is_admin', 1)->where('telegram_id', $Message->getFrom()->getId())->first();
        $this->User             = $AdminUser;
        $this->ChatID           = $Message->getChat()->getId();
        $this->MessageID        = $Message->getMessageId();
        $this->TickedId         = $ticketId;

        if ($this->ChatID < 0) {
            // 群组中不回应
            return;
        }
        // 如果不是管理员
        if (!$AdminUser){
            return;
        }
        if ($ticketId){
            $ticket = Ticket::where('id', $ticketId)->first();
            $user = User::where('id', $ticket->userid)->first();
            $comment = $Message->getText();
            $antiXss = new AntiXSS();

            $content_old = json_decode($ticket->content, true);
            $content_new = [
                [
                    'comment_id'      => $content_old[count($content_old) - 1]['comment_id'] + 1,
                    'commenter_email' => $user->email,
                    'comment'         => $antiXss->xss_clean($comment),
                    'datetime'        => time(),
                ],
            ];

            $ticket->content = json_encode(array_merge($content_old, $content_new));
            $ticket->updated_at = time();
            $ticket->status = 1;
            $ticket->save();
            $bot->sendMessage([
                'chat_id' => $this->ChatID,
                'text' => '回复成功',
                'reply_to_message_id' => $this->MessageID,
            ]);
        }
    }
}
