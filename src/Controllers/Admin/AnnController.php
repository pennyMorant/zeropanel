<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminController;
use App\Models\{
    Ann,
    User,
    Setting
};
use App\Utils\Telegram;
use League\HTMLToMarkdown\HtmlConverter;
use Slim\Http\{
    Request,
    Response
};

class AnnController extends AdminController
{
    /**
     * 后台公告页面
     *
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function index($request, $response, $args)
    {
        $table_config['total_column'] = array(
            
            'id'      => 'ID',
            'date'    => '日期',
            'content' => '内容',
            'action'      => '操作',
        );
        
        $table_config['ajax_url'] = 'news/ajax';
        $this->view()->assign('table_config', $table_config)->display('admin/news/news.tpl');
        return $response;
    }

    /**
     * 后台公告页面 AJAX
     *
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function ajax($request, $response, $args)
    {
        $query = Ann::getTableDataFromAdmin(
            $request,
            static function (&$order_field) {
                if (in_array($order_field, ['action'])) {
                    $order_field = 'id';
                }
            }
        );
        $type = "'request'";
        $data  = [];
        foreach ($query['datas'] as $value) {
            /** @var Ann $value */

            $tempdata            = [];
            $tempdata['id']      = $value->id;
            $tempdata['date']    = $value->date;
            $tempdata['content'] = $value->content;
            $tempdata['action']                   = '<div class="dropdown"><a class="btn btn-light-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false">操作</a>
                                                        <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item" type="button" onclick="zeroAdminUpdateNews('.$type.', '.$value->id.')">编辑</a></li>
                                                            <li><a class="dropdown-item" href="#" onclick="KTAdminNode("'.$value->id.'")>删除</a></li>
                                                        </ul>
                                                    </div>';
            $data[] = $tempdata;
        }

        return $response->withJson([
            'draw'            => $request->getParam('draw'),
            'recordsTotal'    => Ann::count(),
            'recordsFiltered' => $query['count'],
            'data'            => $data,
        ]);
    }

    /**
     * 后台添加公告
     *
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function createNews($request, $response, $args)
    {
        $issend   = $request->getParam('issend');
        $content  = $request->getParam('content');
        $subject  = Setting::obtain('website_general_name') . '-公告';


        $ann           = new Ann();
        $ann->date     = date('Y-m-d H:i:s');
        $ann->content  = $content;

        if (!$ann->save()) {
            return $response->withJson([
                'ret' => 0,
                'msg' => '添加失败'
            ]);
        }

        if ($issend == 1) {
            $beginSend = ($request->getParam('page') - 1) * $_ENV['sendPageLimit'];
            $users     = User::where('class', '>=', 0)->skip($beginSend)->limit($_ENV['sendPageLimit'])->get();
            foreach ($users as $user) {
                $user->sendMail(
                    $subject,
                    'news/warn.tpl',
                    [
                        'user' => $user,
                        'text' => $content
                    ],
                    [],
                    $_ENV['email_queue']
                );
            }
            if (count($users) == $_ENV['sendPageLimit']) {
                return $response->withJson([
                    'ret' => 2,
                    'msg' => $request->getParam('page') + 1
                ]);
            }
        }
        $converter = new HtmlConverter();
        $html = $request->getParam('content');
        $markdown = $converter->convert($html);
        Telegram::PushToChanel($markdown);
        if ($issend == 1) {
            $msg = '公告添加成功，邮件发送成功';
        } else {
            $msg = '公告添加成功';
        }
        return $response->withJson([
            'ret' => 1,
            'msg' => $msg
        ]);
    }

    /**
     * 后台编辑公告提交
     *
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function updateNews($request, $response, $args)
    {   
        
        $ann           = Ann::find($request->getParam('id'));
        $ann->content  = $request->getParam('content');
        //$ann->markdown = $request->getParam('markdown');
        $ann->date     = date('Y-m-d H:i:s');
        if (!$ann->save()) {
            return $response->withJson([
                'ret' => 0,
                'msg' => '修改失败'
            ]);
        }
        $converter = new HtmlConverter();
        $html = $request->getParam('content');
        $markdown = $converter->convert($html);
        Telegram::PushToChanel('公告更新：' . PHP_EOL . $markdown);
        return $response->withJson([
            'ret' => 1,
            'msg' => '修改成功'
        ]);
    }

    /**
     * 后台删除公告
     *
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function delete($request, $response, $args)
    {
        $ann = Ann::find($request->getParam('id'));
        if (!$ann->delete()) {
            return $response->withJson([
                'ret' => 0,
                'msg' => '删除失败'
            ]);
        }
        return $response->withJson([
            'ret' => 1,
            'msg' => '删除成功'
        ]);
    }

    public function requestNews($request, $response, $args)
    {
        $id = $request->getParam('id');
        $news = Ann::find($id);
        return $response->withJson([
            'content'   => $news->content,
            'id'    => $news->id,
        ]);
    }
}