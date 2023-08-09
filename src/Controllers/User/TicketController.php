<?php

namespace App\Controllers\User;

use App\Controllers\UserController;
use App\Models\{
    Ticket,
    Setting,
};
use voku\helper\AntiXSS;
use Slim\Http\Response;
use Slim\Http\ServerRequest;
use App\Utils\Telegram;
use Pkly\I18Next\I18n;
use League\HTMLToMarkdown\HtmlConverter;

class TicketController extends UserController
{
    public function ticketIndex(ServerRequest $request, Response $response, array $args)
    {
        $this->view()
            ->display('user/ticket/ticket.tpl');
        return $response;
    }

    public function createTicket(ServerRequest $request, Response $response, array $args)
    {
        $title   = $request->getParsedBodyParam('title');
        $comment = $request->getParsedBodyParam('comment');
        $type    = $request->getParsedBodyParam('type');
        if ($title === '' || $comment === '') {
            return $response->withJson([
                'ret' => 0,
                'msg' => '非法输入',
            ]);
        }

        $antiXss = new AntiXSS();

        $content = [
            [
                'comment_id' => 0,
                'commenter_email' => $this->user->email,
                'comment' => $antiXss->xss_clean($comment),
                'datetime' => time(),
            ],
        ];

        $ticket           = new Ticket();
        $ticket->title    = $antiXss->xss_clean($title);
        $ticket->content  = json_encode($content);
        $ticket->userid   = $this->user->id;
        $ticket->created_at = time();
        $ticket->updated_at = time();
        $ticket->status   = 1;
        $ticket->type     = $antiXss->xss_clean($type);
        $ticket->save();

        if (Setting::obtain('enable_push_ticket_message')) {
            $converter = new HtmlConverter();
            $messageText = sprintf(
                "有工单需要处理 #%s\n———————————————\n用户ID:%s\n工单类型:%s\n工单内容:%s",
                $ticket->id,
                $ticket->userid,
                $ticket->type,
                $converter->convert($comment)
            );
            $keyBoard = [
                [
                    [
                        'text' => '回复工单 #',
                        'url' => Setting::obtain('website_url') . '/' . Setting::obtain('website_admin_path') . '/ticket/view/' . $ticket->id  
                    ]
                ]
            ];
            Telegram::pushToAdmin($messageText, $keyBoard);
        }

        return $response->withJson(
            [
                'ret' => 1,
                'id' => $ticket->id,
                'msg' => I18n::get()->t('success')
            ]
        );
    }

    public function updateTicket(ServerRequest $request, Response $response, array $args)
    {
        $id      = $request->getParsedBodyParam('id');
        $comment = $request->getParsedBodyParam('comment');

        if ($comment === '') {
            return $response->withJson([
                'ret' => 0,
                'msg' => '非法输入',
            ]);
        }

        $ticket = Ticket::where('id', $id)->where('userid', $this->user->id)->first();

        if (is_null($ticket)) {
            return $response->withStatus(302)->withHeader('Location', '/user/ticket');
        }

        $antiXss = new AntiXSS();

        $content_old = json_decode($ticket->content, true);
        $content_new = [
            [
                'comment_id'      => $content_old[count($content_old) - 1]['comment_id'] + 1,
                'commenter_email' => $this->user->email,
                'comment'         => $antiXss->xss_clean($comment),
                'datetime'        => time(),
            ],
        ];

        $ticket->content = json_encode(array_merge($content_old, $content_new));
        $ticket->updated_at = time();
        $ticket->status = 1;
        $ticket->save();
        if (Setting::obtain('enable_push_ticket_message')) {
            $converter = new HtmlConverter();
            $messageText = sprintf(
                "有工单需要处理 #%s\n———————————————\n用户ID:%s\n工单类型:%s\n工单内容:%s",
                $ticket->id,
                $ticket->userid,
                $ticket->type,
                $converter->convert($comment)
            );
            $keyBoard = [
                [
                    [
                        'text' => '回复工单 #' . $id,
                        'url' => Setting::obtain('website_url') . '/' . Setting::obtain('website_admin_path') . '/ticket/view/' . $id 
                    ]
                ]
            ];
            
            Telegram::pushToAdmin($messageText, $keyBoard);
            
        }
        return $response->withJson(
            [
                'ret' => 1,
                'msg' => I18n::get()->t('success')
            ]
        );
    }

    public function ticketViewIndex(ServerRequest $request, Response $response, array $args)
    {
        $id       = $args['id'];
        $ticket   = Ticket::where('id', '=', $id)->where('userid', $this->user->id)->first();
        $comments = json_decode($ticket->content, true);

        $this->view()
            ->assign('ticket', $ticket)
            ->assign('comments', $comments)
            ->display('user/ticket/view.tpl');
        return $response;  
    }
}
