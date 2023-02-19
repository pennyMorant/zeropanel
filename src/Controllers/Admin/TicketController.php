<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminController;
use App\Models\{
    User,
    Ticket,
    Setting
};
use App\Utils\Tools;
use voku\helper\AntiXSS;
use Slim\Http\{
    Request,
    Response
};

class TicketController extends AdminController
{
    /**
     * 后台工单页面
     *
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function index($request, $response, $args)
    {
        $table_config['total_column'] = array(
            'id'        => 'ID',
            'datetime'  => '时间',
            'title'     => '标题',
            'userid'    => '用户ID',
            'status'    => '状态',
            'action'        => '操作',
        );
        $table_config['ajax_url'] = 'ticket/ajax';
        $this->view()
            ->assign('table_config', $table_config)
            ->display('admin/ticket/ticket.tpl');
        return $response;
    }

    /**
     * 後臺創建新工單
     *
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function add($request, $response, $args)
    {
        $title    = $request->getParam('title');
        $content  = $request->getParam('content');
        $markdown = $request->getParam('markdown');
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
            Setting::obtain('website_general_name') . '-新管理员工单被开启',
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

    /**
     * 后台 更新工单内容
     *
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function updateTicket($request, $response, $args)
    {
        $id      = $request->getParam('id');
        $content = $request->getParam('content');
        $status  = $request->getParam('status');
        if ($content == '' || $status == '') {
            return $response->withJson([
                'ret' => 0,
                'msg' => '请填全'
            ]);
        }
        if (strpos($content, 'admin') !== false || strpos($content, 'user') !== false) {
            return $response->withJson([
                'ret' => 0,
                'msg' => '请求中有不正当的词语。'
            ]);
        }
        $main = Ticket::find($id);
        $user = User::find($main->userid);
        $user->sendMail(
            Setting::obtain('website_general_name') . '-工单被回复',
            'news/warn.tpl',
            [
                'text' => '您好，有人回复了<a href="' . Setting::obtain('website_url') . '/user/ticket/' . $main->id . '/view">工单</a>，请您查看。'
            ],
            []
        );

        $antiXss                = new AntiXSS();
        $ticket                 = new Ticket();
        $ticket->title          = $antiXss->xss_clean($main->title);
        $ticket->content        = $antiXss->xss_clean($content);
        $ticket->rootid         = $main->id;
        $ticket->userid         = $this->user->id;
        $ticket->datetime       = time();
        $ticket->save();
        $main->status           = $status;
        $main->save();

        return $response->withJson([
            'ret' => 1,
            'msg' => '提交成功'
        ]);
    }

    /**
     * 后台 查看指定工单
     *
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function updateTicketIndex($request, $response, $args)
    {
        $id            = $args['id'];
        $ticket = Ticket::where('id','=', $id)->first();
        if($ticket == null) {
            return $response->withStatus(302)->withHeader('Location', '/admin/ticket');
        }

        $pageNum       = $request->getQueryParams()['page'] ?? 1;
        $ticket_details     = Ticket::where('id', $id)->orWhere('rootid', '=', $id)->orderBy('datetime', 'desc')->paginate(5, ['*'], 'page', $pageNum);
        $ticket_details->setPath('/admin/ticket/' . $id . '/view');

        $render = Tools::paginate_render($ticket_details);
        $this->view()
            ->assign('ticket_details', $ticket_details)
            ->assign('id', $id)
            ->assign('render', $render)
            ->display('admin/ticket/update.tpl');
        return $response;
    }

    /**
     * 后台工单页面 AJAX
     *
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function ajax($request, $response, $args)
    {
        $query = Ticket::getTableDataFromAdmin(
            $request,
            static function (&$order_field) {
                if (in_array($order_field, ['action'])) {
                    $order_field = 'id';
                }
                if (in_array($order_field, ['user_id'])) {
                    $order_field = 'userid';
                }
            },
            static function ($query) {
                $query->where('rootid', 0);
            },
        );

        $data  = [];
        foreach ($query['datas'] as $value) {
            /** @var Ticket $value */

            if ($value->user() == null) {
                Ticket::user_is_null($value);
                continue;
            }
            $tempdata               = [];
            $tempdata['id']         = $value->id;
            $tempdata['datetime']   = $value->datetime();
            $tempdata['title']      = $value->title;
            $tempdata['userid']     = $value->userid;
            $tempdata['status']     = $value->status();
            $tempdata['action']     = '<div class="btn-group dropstart"><a class="btn btn-light-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false">操作</a>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="/admin/ticket/update/'.$value->id.'">编辑</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="KTAdminNode("'.$value->id.'")>删除</a></li>
                                            </ul>
                                        </div>';
            $data[] = $tempdata;
        }

        return $response->withJson([
            'draw'            => $request->getParam('draw'),
            'recordsTotal'    => Ticket::count(),
            'recordsFiltered' => $query['count'],
            'data'            => $data,
        ]);
    }
}