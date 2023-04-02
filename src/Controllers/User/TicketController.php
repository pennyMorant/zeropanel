<?php

namespace App\Controllers\User;

use App\Controllers\UserController;
use App\Models\{
    Ticket,
    Setting,
    Ann
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
        $tickets = Ticket::where('userid', $this->user->id)->orderBy('datetime', 'desc')->get();
        /*
        foreach ($tickets as $ticket) {
            $ticket->status = Tools::getTicketStatus($ticket);
            $ticket->type = Tools::getTicketType($ticket);
            $ticket->datetime = Tools::toDateTime((int) $ticket->datetime);
        }*/

        if ($request->getParam('json') === 1) {
            return $response->withJson([
                'ret' => 1,
                'tickets' => $tickets,
            ]);
        }

        $this->view()
            ->assign('tickets', $tickets)
            ->assign('anns', Ann::where('date', '>=', date('Y-m-d H:i:s', time() - 7 * 86400))->orderBy('date', 'desc')->get())
            ->display('user/ticket/ticket.tpl');
        return $response;
    }

    public function createTicket(ServerRequest $request, Response $response, array $args)
    {
        $title = $request->getParam('title');
        $comment = $request->getParam('comment');
        $type = $request->getParam('type');
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

        $ticket = new Ticket();
        $ticket->title = $antiXss->xss_clean($title);
        $ticket->content = json_encode($content);
        $ticket->userid = $this->user->id;
        $ticket->datetime = time();
        $ticket->status = 1;
        $ticket->type = $antiXss->xss_clean($type);
        $ticket->save();

        if (Setting::obtain('enable_push_ticket_message') == true) {
            $converter = new HtmlConverter();
            $messageText = '用户开启新工单' . PHP_EOL . '------------------------------' . PHP_EOL . '用户ID:' . $this->user->id . PHP_EOL . '标题：' . $title . PHP_EOL . '内容：' . $converter->convert($comment);
            $keyBoard = [
                [
                    [
                        'text' => '回复工单 #',
                        'url' => Setting::obtain('website_url') . '/' . Setting::obtain('website_admin_path') . '/ticket' 
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
        $id = $request->getParam('id');
        $comment = $request->getParam('comment');

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
                'comment_id' => $content_old[count($content_old) - 1]['comment_id'] + 1,
                'commenter_email' => $this->user->email,
                'comment' => $antiXss->xss_clean($comment),
                'datetime' => time(),
            ],
        ];

        $ticket->content = json_encode(array_merge($content_old, $content_new));
        $ticket->status = 1;
        $ticket->save();

        if (Setting::obtain('enable_push_ticket_message') == true) {
            $converter = new HtmlConverter();
            $messageText = '用户回复工单' . PHP_EOL . '------------------------------' . PHP_EOL . '用户ID:' . $this->user->id . PHP_EOL . '标题：' . $ticket->title . PHP_EOL . '内容：' . $converter->convert($comment);
            $keyBoard = [
                [
                    [
                        'text' => '回复工单 #' . $id,
                        'url' => Setting::obtain('website_url') . '/' . Setting::obtain('website_admin_path') . '/ticket/update/' . $id 
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
        $id = $args['id'];
        $ticket = Ticket::where('id', '=', $id)->where('userid', $this->user->id)->first();
        $comments = json_decode($ticket->content, true);

        //$ticket->status = Tools::getTicketStatus($ticket);
        //$ticket->type = Tools::getTicketType($ticket);
        //$ticket->datetime = Tools::toDateTime((int) $ticket->datetime);

        if (is_null($ticket)) {
            if ($request->getParam('json') === 1) {
                return $response->withJson([
                    'ret' => 0,
                    'msg' => '无访问权限',
                ]);
            }
            return $response->withStatus(302)->withHeader('Location', '/user/ticket');
        }

        $this->view()
            ->assign('ticket', $ticket)
            ->assign('comments', $comments)
            ->assign('anns', Ann::where('date', '>=', date('Y-m-d H:i:s', time() - 7 * 86400))->orderBy('date', 'desc')->get())
            ->display('user/ticket/view.tpl');
        return $response;  
    }
}
