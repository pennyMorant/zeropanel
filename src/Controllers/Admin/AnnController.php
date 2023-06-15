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
use Slim\Http\Response;
use Slim\Http\ServerRequest;

class AnnController extends AdminController
{
    public function index(ServerRequest $request, Response $response, array $args)
    {
        $table_config['total_column'] = [
            
            'id'         => 'ID',
            'created_at' => '创建日期',
            'updated_at' => '更新日期',
            'action'     => '操作',
        ];
        
        $table_config['ajax_url'] = 'news/ajax';
        $this->view()->assign('table_config', $table_config)->display('admin/news/news.tpl');
        return $response;
    }

    public function ajax(ServerRequest $request, Response $response, array $args): Response
    {
        $query = Ann::getTableDataFromAdmin(
            $request,
            static function (&$order_field) {
                if (in_array($order_field, ['action'])) {
                    $order_field = 'id';
                }
            }
        );

        $data = $query['datas']->map(function($rowData) {          
            return [
                'id'         => $rowData->id,
                'created_at' => date('Y-m-d H:i:s', $rowData->created_at),
                'updated_at' => date('Y-m-d H:i:s', $rowData->updated_at),
                'action'     => '<div class="btn-group dropstart"><a class="btn btn-light-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false">操作</a>
                                    <ul    class = "dropdown-menu">
                                    <li><a class = "dropdown-item" type = "button" onclick = "zeroAdminUpdateNews(\'request\', '.$rowData->id.')">编辑</a></li>
                                    <li><a class = "dropdown-item" type = "button" onclick = "zeroAdminDelete(\'news\', '.$rowData->id.')">删除</a></li>
                                    </ul>
                                </div>',
            ];
        })->toArray();

        return $response->withJson([
            'draw'            => $request->getParsedBodyParam('draw'),
            'recordsTotal'    => Ann::count(),
            'recordsFiltered' => $query['count'],
            'data'            => $data,
        ]);
    }

    public function createNews(ServerRequest $request, Response $response, array $args): Response
    {
        $postdata = $request->getParsedBody();
        $issend   = $postdata['issend'];
        $content  = $postdata['content'];
        $subject  = Setting::obtain('website_name') . '-公告';


        $ann           = new Ann();
        $ann->created_at     = time();
        $ann->updated_at     = time();
        $ann->content  = $content;

        if (!$ann->save()) {
            return $response->withJson([
                'ret' => 0,
                'msg' => '添加失败'
            ]);
        }

        if ($issend == 1) {
            $beginSend = ($postdata['page'] - 1) * $_ENV['sendPageLimit'];
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
        }
        $converter = new HtmlConverter();
        $html = $postdata['content'];
        $markdown = $converter->convert($html);
        Telegram::pushToChannel($markdown);
        
        return $response->withJson([
            'ret' => 1,
            'msg' => ($issend == 1 ? '公告添加成功，邮件发送成功' : '公告添加成功'),
        ]);
    }

    public function updateNews(ServerRequest $request, Response $response, array $args): Response
    {   
        $ann           = Ann::find($request->getParsedBodyParam('id'));
        $ann->content  = $request->getParsedBodyParam('content');
        //$ann->markdown = $datas['markdown');
        $ann->updated_at     = time();
        if (!$ann->save()) {
            return $response->withJson([
                'ret' => 0,
                'msg' => '修改失败'
            ]);
        }
        $converter = new HtmlConverter();
        $html = $request->getParsedBodyParam('content');
        $markdown = $converter->convert($html);
        Telegram::pushToChannel('公告更新：' . PHP_EOL . $markdown);
        return $response->withJson([
            'ret' => 1,
            'msg' => '修改成功'
        ]);
    }

    public function deleteNews(ServerRequest $request, Response $response, array $args): Response
    {
        $id = $request->getParsedBodyParam('id');
        $ann = Ann::find($id);
        $ann->delete();
        return $response->withJson([
            'ret' => 1,
            'msg' => '删除成功'
        ]);
    }

    public function requestNews(ServerRequest $request, Response $response, array $args): Response
    {
        $id = $request->getParsedBodyParam('id');
        $news = Ann::find($id);
        return $response->withJson([
            'content' => $news->content,
            'id'      => $news->id,
        ]);
    }
}