<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminController;
use App\Models\{
    User,
    Ticket,
    Setting
};
use voku\helper\AntiXSS;
use Slim\Http\Response;
use Slim\Http\ServerRequest;

class TicketController extends AdminController
{
    public function ticketIndex(ServerRequest $request, Response $response, array $args)
    {
        $table_config['total_column'] = [
            'id'        => 'ID',
            'userid'    => '用户ID',
            'type'      => '类型',
            'title'     => '主题',
            'status'    => '状态',
            'datetime'  => '时间',
            'last_updated'  => '最后更新',                  
            'action'        => '操作',
        ];
        $table_config['ajax_url'] = 'ticket/ajax';
        $this->view()
            ->assign('table_config', $table_config)
            ->display('admin/ticket/ticket.tpl');
        return $response;
    }

    public function createTicket(ServerRequest $request, Response $response, array $args): Response
    {
        $title    = $request->getParam('title');
        $content  = $request->getParam('content');
        $userid   = $request->getParam('userid');
        if ($title == '' || $content == '') {
            return $response->withJson([
                'ret' => 0,
                'msg' => '非法输入'
            ]);
        }
        if (strpos($content, 'admin') !== false || strpos($content, 'user') !== false) {
            return $response->withJson([
                'ret' => 0,
                'msg' => '请求中有不当词语'
            ]);
        }

        $ticket           = new Ticket();
        $antiXss          = new AntiXSS();
        $ticket->title    = $antiXss->xss_clean($title);
        $ticket->content  = $antiXss->xss_clean($content);
        $ticket->rootid   = 0;
        $ticket->userid   = $userid;
        $ticket->datetime = time();
        $ticket->save();

        $user = User::find($userid);
        $user->sendMail(
            Setting::obtain('website_name') . '-新管理员工单被开启',
            'news/warn.tpl',
            [
                'text' => '管理员开启了新的工单，请您及时访问用户面板处理。'
            ],
            []
        );

        return $response->withJson([
            'ret' => 1,
            'msg' => '提交成功'
        ]);
    }

    public function updateTicket(ServerRequest $request, Response $response, array $args): Response
    {
        $id = $request->getParam('id');
        $comment = $request->getParam('comment');

        if ($comment === '') {
            return $response->withJson([
                'ret' => 0,
                'msg' => '非法输入',
            ]);
        }

        $ticket = Ticket::where('id', $id)->first();

        if (is_null($ticket)) {
            return $response->withStatus(302)->withHeader('Location', '/admin/ticket');
        }

        $antiXss = new AntiXSS();

        $content_old = json_decode($ticket->content, true);
        $content_new = [
            [
                'comment_id' => $content_old[count($content_old) - 1]['comment_id'] + 1,
                'commenter_email' => 'Admin',
                'comment' => $antiXss->xss_clean($comment),
                'datetime' => time(),
            ],
        ];

        $user = User::find($ticket->userid);
        $user->sendMail(
            Setting::obtain('website_name') . '-工单被回复',
            'news/warn.tpl',
            [
                'text' => '您好，有人回复了<a href="' . Setting::obtain('website_url') . '/user/ticket/view/' . $ticket->id . '">工单</a>，请您查看。',
            ],
            []
        );

        $ticket->content = json_encode(array_merge($content_old, $content_new));
        $ticket->status = 1;
        $ticket->save();

        return $response->withJson([
            'ret' => 1,
            'msg' => '提交成功'
        ]);
    }

    public function ticketViewIndex(ServerRequest $request, Response $response, array $args)
    {
        $id = $args['id'];
        $ticket = Ticket::where('id', '=', $id)->first();
        $comments = json_decode($ticket->content, true);

        if (is_null($ticket)) {
            return $response->withStatus(302)->withHeader('Location', '/admin/ticket');
        }
        $this->view()
            ->assign('ticket', $ticket)
            ->assign('comments', $comments)
            ->display('admin/ticket/view.tpl');
        return $response;
    }

    public function ticketAjax(ServerRequest $request, Response $response, array $args): Response
    {
        $query = Ticket::getTableDataFromAdmin(
            $request,
            static function (&$order_field) {
                if (in_array($order_field, ['action'])) {
                    $order_field = 'id';
                }
                if (in_array($order_field, ['title'])) {
                    $order_field = 'userid';
                }
            },
        );

        $data = $query['datas']->map(function($rowData) {
            $type = "'ticket'";
            $comments = json_decode($rowData->content, true);
            foreach ($comments as $comment) {
                $last_updated = date('Y-m-d H:i:s', $comment['datetime']);
            }
            return [
                'id'    => $rowData->id,
                'userid'    =>  $rowData->userid,
                'type'  =>  $rowData->type,
                'title' =>  $rowData->title,
                'status'    =>  $rowData->status(),
                'datetime'  =>  date('Y-m-d H:i:s', $rowData->datetime),
                'last_updated'  =>  $last_updated,
                'action'    =>  '<div class="btn-group dropstart"><a class="btn btn-light-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false">操作</a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="/admin/ticket/view/'.$rowData->id.'">编辑</a></li>
                                        <li><a class="dropdown-item" type="button" onclick="zeroAdminDelete('. $type . ', ' . $rowData->id. ')">删除</a></li>
                                        <li><a class="dropdown-item" type="button" onclick="zeroAdminCloseTicket(' . $rowData->id . ')">关闭</a></li>
                                    </ul>
                                </div>',
            ];
        })->toArray();

        return $response->withJson([
            'draw'            => $request->getParam('draw'),
            'recordsTotal'    => Ticket::count(),
            'recordsFiltered' => $query['count'],
            'data'            => $data,
        ]);
    }

    public function deleteTicket(ServerRequest $request, Response $response, array $args): Response
    {
        $id = $request->getParam('id');
        Ticket::find($id)->delete();
        return $response->withJson([
            'ret'   => 1,
            'msg'   => 'success'
        ]);
    }

    public function closeTicket(ServerRequest $request, Response $response, array $args): Response
    {
        $id = $request->getParam('id');
        $ticket = Ticket::find($id);
        $ticket->status = 0;
        $ticket->save();
        return $response->withJson([
            'ret'   => 1,
            'msg'   => 'success'
        ]);
    }
}