<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminController;
use App\Models\{
    Product,
    Bought,
    Order
};
use Slim\Http\Response;
use Slim\Http\ServerRequest;

class ProductController extends AdminController
{
    public function index(ServerRequest $request, Response $response, $args)
    {
        $table_config['total_column'] = [
            'id'        => 'ID',
            'sort'      => '排序',   
            'name'      => '商品名称',
            'type'      => '产品类型',
            'sales'     => '周期销量',
            'status'    => '状态',
            'action'    => '操作'
        ];
    
        $table_config['ajax_url'] = 'product/ajax';
        $this->view()
            ->assign('table_config', $table_config)
            ->display('admin/product/product.tpl');
        return $response;
    }

    public function createProductIndex(ServerRequest $request, Response $response, $args)
    {
        $this->view()->display('admin/product/create.tpl');
        return $response;
    }

    public function createProduct(ServerRequest $request, Response $response, $args): Response
    {
        $product = new Product();
        $product->name = $request->getParam('name');
        $product->month_price = (empty($request->getParam('month_price'))) ? NULL : $request->getParam('month_price');
        $product->quarter_price = (empty($request->getParam('quarter_price'))) ? NULL : $request->getParam('quarter_price');
        $product->half_year_price = (empty($request->getParam('half_year_price'))) ? NULL : $request->getParam('half_year_price');
        $product->year_price = (empty($request->getParam('year_price'))) ? NULL : $request->getParam('year_price');
        $product->two_year_price = (empty($request->getParam('two_year_price'))) ? NULL : $request->getParam('two_year_price');
        $product->type = $request->getParam('type');
        $product->sort = $request->getParam('sort');
        $product->traffic = (empty($request->getParam('traffic'))) ? NULL : $request->getParam('traffic');
        $product->user_group = $request->getParam('group');
        $product->class = $request->getParam('class');
        $product->reset_traffic_cycle = $request->getParam('reset');
        $product->speed_limit = $request->getParam('speed_limit');
        $product->ip_limit = $request->getParam('ip_limit');
        $product->stock = $request->getParam('stock');
        $product->status = 0;
        if (!$product->save()) {
            return $response->withJson([
                'ret' => 0,
                'msg' => '添加失败'
            ]);
        }
        return $response->withJson([
            'ret' => 1,
            'msg' => '添加成功'
        ]);
    }

    public function updateProductIndex(ServerRequest $request, Response $response, $args)
    {
        $id = $args['id'];
        $product = Product::find($id);
        $this->view()
            ->assign('product', $product)
            ->display('admin/product/edit.tpl');
        return $response;
    }

    public function updateProduct(ServerRequest $request, Response $response, $args): Response
    {
        $id = $request->getParam('id');
        $product = Product::find($id);

        $product->name = $request->getParam('name');
        $product->month_price = (empty($request->getParam('month_price'))) ? NULL : $request->getParam('month_price');
        $product->quarter_price = (empty($request->getParam('quarter_price'))) ? NULL : $request->getParam('quarter_price');
        $product->half_year_price = (empty($request->getParam('half_year_price'))) ? NULL : $request->getParam('half_year_price');
        $product->year_price = (empty($request->getParam('year_price'))) ? NULL : $request->getParam('year_price');
        $product->two_year_price = (empty($request->getParam('two_year_price'))) ? NULL : $request->getParam('two_year_price');
        $product->type = $request->getParam('type');
        $product->sort = $request->getParam('sort');
        $product->traffic = (empty($request->getParam('traffic'))) ? NULL : $request->getParam('traffic');

        $product->user_group = $request->getParam('node_group');
        $product->class = $request->getParam('class');
        $product->reset_traffic_cycle = $request->getParam('reset');
        $product->speed_limit = $request->getParam('speed_limit');
        $product->ip_limit = $request->getParam('ip_limit');
        $product->stock = $request->getParam('stock') - $product->sales;
        if (!$product->save()) {
            return $response->withJson([
                'ret' => 0,
                'msg' => '保存失败'
            ]);
        }
        return $response->withJson([
            'ret' => 1,
            'msg' => '保存成功'
        ]);
    }


    public function productAjax(ServerRequest $request, Response $response, $args): Response
    {
        $query = Product::getTableDataFromAdmin(
            $request,
            static function (&$order_field) {
                if (in_array($order_field, ['action', 'period_sales', 'name'])) {
                    $order_field = 'id';
                }
            }
        );

        $data = $query['datas']->map(function($rowData) {
            $type = "'product'";
            return [
                'id'    =>  $rowData->id,
                'sort'  =>  $rowData->sort,
                'name'  =>  $rowData->name,
                'type'  =>  $rowData->type(),
                'sales' =>  $rowData->sales,
                'status'    =>  $rowData->status(),
                'action'    =>  '<div class="btn-group dropstart"><a class="btn btn-light-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false">操作</a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="/admin/product/update/'.$rowData->id.'">编辑</a></li>
                                        <li><a class="dropdown-item" type="button" onclick="zeroAdminDelete('.$type.', '.$rowData->id.')">删除</a></li>
                                    </ul>
                                </div>',
            ];
        })->toArray();

        return $response->withJson([
            'draw'            => $request->getParam('draw'),
            'recordsTotal'    => Product::count(),
            'recordsFiltered' => $query['count'],
            'data'            => $data,
        ]);
    }

    public function updateProductStatus(ServerRequest $request, Response $response, $args): Response
    {
        $id = $request->getParam('id');
        $status = $request->getParam('status');
        $product = Product::find($id);
        $product->status = $status;
        $product->save();
        return $response->withJson([
            'ret'   => 1,
            'msg'   => 'success'
        ]);
    }

    public function deleteProduct(ServerRequest $request, Response $response, $args): Response
    {
        $id = $request->getParam('id');
        $product = Product::find($id);
        $product->delete();

        return $response->withJson([
            'ret' => 1,
            'msg' => '删除成功'
        ]);
    }

    public function getProductInfo(ServerRequest $request, Response $response, $args): Response
    {
        $id = $request->getParam('id');
        $product = Product::find($id);
        $data = [
            'name' => $product->name,
            'month_price'   =>  $product->month_price,
            'quarter_price' =>  $product->quarter_price,
            'half_year_price'   =>  $product->half_year_price,
            'year_price'    =>  $product->year_price,
            'type'  =>  $product->type,
        ];
        return $response->withJson($data);
    }
}