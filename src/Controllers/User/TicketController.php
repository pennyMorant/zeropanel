<?php

namespace App\Controllers\User;

use App\Controllers\UserController;
use App\Models\{
    User,
    Ticket,
    Setting,
    Ann
};
use voku\helper\AntiXSS;
use Slim\Http\{
    Request,
    Response
};
use App\Zero\Telegram;
use Pkly\I18Next\I18n;

/**
 *  TicketController
 */
class TicketController extends UserController
{
    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function ticket($request, $response, $args)
    {
        $pageNum = $request->getQueryParams()['page'] ?? 1;
        $tickets = Ticket::where('userid', $this->user->id)->where('rootid', 0)->orderBy('datetime', 'desc')->paginate(15, ['*'], 'page', $pageNum);
        $tickets_all = Ticket::where('userid', $this->user->id)->where('rootid', 0)->orderBy('datetime', 'desc')->get();
        $tickets->setPath('/user/ticket');

        if ($request->getParam('json') == 1) {
            return $response->withJson(
                [
                    'ret'     => 1,
                    'tickets' => $tickets
                ]
            );
        }

        $this->view()
            ->assign('tickets', $tickets)
            ->assign('tickets_all', $tickets_all)
            ->assign('anns', Ann::where('date', '>=', date('Y-m-d H:i:s', time() - 7 * 86400))->orderBy('date', 'desc')->get())
            ->display('user/ticket/ticket.tpl');
        return $response;
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function ticketCreate($request, $response, $args)
    {
        $this->view() ->display('user/ticket_create.tpl');
        return $response;

    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function ticketAdd($request, $response, $args)
    {
        $title    = $request->getParam('title');
        $content  = $request->getParam('content');
        $markdown = $request->getParam('markdown');

        if ($title == '' || $content == '') {
            return $response->withJson(
                [
                    'ret' => 0,
                    'msg' => I18n::get()->t('user.ticket.notify.error_0')
                ]
            );
        }

        if (strpos($content, 'admin') != false || strpos($content, 'user') != false) {
            return $response->withJson(
                [
                    'ret' => 0,
                    'msg' => I18n::get()->t('user.ticket.notify.error_1')
                ]
            );
        }

        $ticket  = new Ticket();
        $antiXss = new AntiXSS();

        $ticket->title    = $antiXss->xss_clean($title);
        $ticket->content  = $antiXss->xss_clean($content);
        $ticket->rootid   = 0;
        $ticket->userid   = $this->user->id;
        $ticket->datetime = time();
        $ticket->save();

        if (Setting::obtain('enable_ticket_telegram_notify') == true) {
            Telegram::SendTicket($this->user->id, $title, $content);
        }

        return $response->withJson(
            [
                'ret' => 1,
                'tid' => $ticket->id,
                'msg' => I18n::get()->t('user.ticket.notify.success_0')
            ]
        );
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function ticketUpdate($request, $response, $args)
    {
        $id       = $args['id'];
        $content  = $request->getParam('content');
        $status   = $request->getParam('status');
        $markdown = $request->getParam('markdown');

        if ($content == '' || $status == '') {
            return $response->withJson(
                [
                    'ret' => 0,
                    'msg' => I18n::get()->t('user.ticket.notify.error_0')
                ]
            );
        }

        if (strpos($content, 'admin') != false || strpos($content, 'user') != false) {
            return $response->withJson(
                [
                    'ret' => 0,
                    'msg' => I18n::get()->t('user.ticket.notify.error_1')
                ]
            );
        }

        $ticket_main = Ticket::where('id', '=', $id)->where('rootid', '=', 0)->first();
        if ($ticket_main->userid != $this->user->id) {
            $newResponse = $response->withStatus(302)->withHeader('Location', '/user/ticket');
            return $newResponse;
        }

        if ($status == 1 && $ticket_main->status != $status) {
            if (Setting::obtain('enable_ticket_telegram_notify') == true) {
                Telegram::SendTicket($this->user->id, $ticket_main->title, $content, 'restart');
            }
        } else {
            if (Setting::obtain('enable_ticket_telegram_notify') == true) {
                Telegram::SendTicket($this->user->id, $ticket_main->title, $content, 'update');
            }
        }

        $antiXss              = new AntiXSS();

        $ticket               = new Ticket();
        $ticket->title        = $antiXss->xss_clean($ticket_main->title);
        $ticket->content      = $antiXss->xss_clean($content);
        $ticket->rootid       = $ticket_main->id;
        $ticket->userid       = $this->user->id;
        $ticket->datetime     = time();
        $ticket_main->status  = $status;

        $ticket_main->save();
        $ticket->save();

        return $response->withJson(
            [
                'ret' => 1,
                'msg' => I18n::get()->t('user.ticket.notify.success_0')
            ]
        );
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function ticketView($request, $response, $args)
    {
        $id           = $args['id'];
        $ticket_main  = Ticket::where('id', '=', $id)->where('rootid', '=', 0)->first();
        if ($ticket_main->userid != $this->user->id) {
            if ($request->getParam('json') == 1) {
                return $response->withJson(
                    [
                        'ret' => 0,
                        'msg' => I18n::get()->t('user.ticket.notify.error_2')
                    ]
                );
            }
            $newResponse = $response->withStatus(302)->withHeader('Location', '/user/ticket');
            return $newResponse;
        }

        $pageNum = $request->getQueryParams()['page'] ?? 1;

        $ticket_detail = Ticket::where('id', $id)->orWhere('rootid', '=', $id)->orderBy('datetime', 'desc')->paginate(5, ['*'], 'page', $pageNum);
        $ticket_detail->setPath('/user/ticket/' . $id . '/view');

        if ($request->getParam('json') == 1) {
            foreach ($ticket_detail as $set) {
                $set->username = $set->User()->name;
                $set->datetime = $set->datetime();
            }
            return $response->withJson(
                [
                    'ret'     => 1,
                    'tickets' => $ticket_detail
                ]
            );
        }

        $this->view()
            ->assign('tickets', $ticket_main)
            ->assign('ticket_detail', $ticket_detail)
            ->assign('id', $id)
            ->assign('anns', Ann::where('date', '>=', date('Y-m-d H:i:s', time() - 7 * 86400))->orderBy('date', 'desc')->get())
            ->display('user/ticket/ticket_detail.tpl');
        return $response;  
    }
}
