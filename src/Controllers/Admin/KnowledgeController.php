<?php

namespace App\Controllers\Admin;

use App\Models\Knowledge;
use App\Controllers\AdminController;
use Slim\Http\Response;
use Slim\Http\ServerRequest;

class KnowledgeController extends AdminController
{
    public function knowledgeIndex(ServerRequest $request, Response $response, array $args): Response
    {
        $table_config['total_column'] = [
            'id'       => 'ID',
            'title'    => '标题',
            'platform' => '平台',
            'client'   => '客户端',
            'date'     => '日期',
            'action'   => '操作',
        ];
        
        $table_config['ajax_url'] = 'knowledge/ajax';
        $this->view()->assign('table_config', $table_config)->display('admin/knowledge.tpl');
        return $response;
    }

    public function createKnowledge(ServerRequest $request, Response $response, array $args): Response
    {
        $postData                = $request->getParsedBody();
        $knowledge               = new Knowledge();
        $knowledge->title        = $postData['title'];
        $knowledge->platform     = $postData['platform'];
        $knowledge->client       = $postData['client'];
        $knowledge->content      = $postData['content'];
        $knowledge->created_at   = time();
        $knowledge->updated_at   = time();
        $knowledge->save();

        return $response->withJson([
            'ret'   => 1,
            'msg'   => '添加成功',
        ]);
    }

    public function updateKnowledge(ServerRequest $request, Response $response, array $args): Response
    {
        $postData                = $request->getParsedBody();
        $knowledge               = Knowledge::find($postData['id']);
        $knowledge->title        = $postData['title'];
        $knowledge->platform     = $postData['platform'];
        $knowledge->client       = $postData['client'];
        $knowledge->content      = $postData['content'];
        //$knowledge->created_at   = time();
        $knowledge->updated_at   = time();
        $knowledge->save();

        return $response->withJson([
            'ret'   => 1,
            'msg'   => '更新成功',
        ]);
    }

    public function knowledgeAjax(ServerRequest $request, Response $response, array $args): Response
    {
        $query = Knowledge::getTableDataFromAdmin(
            $request,
            static function (&$order_field) {
                if (in_array($order_field, ['action'])) {
                    $order_field = 'id';
                }
            }
        );

        $data = $query['datas']->map(function($rowData) {
            return [
                'id'       => $rowData->id,
                'title'    => $rowData->title,
                'platform' => $rowData->platform,
                'client'   => $rowData->client,
                'date'     => date('Y-m-d H:i:s', $rowData->created_at),
                'action'   => '<div class="btn-group dropstart"><a class="btn btn-light-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false">操作</a>
                                    <ul    class = "dropdown-menu">
                                    <li><a class = "dropdown-item" type = "button" onclick = "zeroAdminKnowledgeGetInfo(' . $rowData->id . ')">编辑</a></li>
                                    <li><a class = "dropdown-item" type = "button" onclick = "zeroAdminDelete(\'knowledge\', ' . $rowData->id . ')">删除</a></li>
                                    </ul>
                                </div>',
            ];
        })->toArray();

        return $response->withJson([
            'draw'            => $request->getParsedBodyParam('draw'),
            'recordsTotal'    => Knowledge::count(),
            'recordsFiltered' => $query['count'],
            'data'            => $data,
        ]);
    }

    public function getInfoKnowledge(ServerRequest $request, Response $response, array $args): Response
    {
        $knowledge = Knowledge::find($request->getParsedBodyParam('id'));
        $data = [
            'title'    => $knowledge->title,
            'platform' => $knowledge->platform,
            'client'   => $knowledge->client,
            'content'  => $knowledge->content,
        ];

        return $response->withJson($data);
    }

    public function deleteKnowledge(ServerRequest $request, Response $response, array $args): Response
    {
        $knowledge = Knowledge::find($request->getParsedBodyParam('id'));
        $knowledge->delete();

        return $response->withJson([
            'ret' => 1,
            'msg' => '删除成功',
        ]);
    }
}