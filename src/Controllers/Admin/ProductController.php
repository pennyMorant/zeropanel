<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminController;
use App\Models\Product;
use Slim\Http\Response;
use Slim\Http\ServerRequest;

class ProductController extends AdminController
{
    public function index(ServerRequest $request, Response $response, array $args): Response
    {
        $table_config['total_column'] = [
            'id'        => 'ID',
            'sort'      => '排序',   
            'name'      => '商品名称',
            'type'      => '产品类型',
            'sales'     => '周期销量',
            'status'    => '状态',
            'renew'     => '续费 <i class="bi bi-question-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="在商品停止销售时，已购用户是否可以续费"></i>',
            'action'    => '操作'
        ];
    
        $table_config['ajax_url'] = 'product/ajax';
        $this->view()
            ->assign('table_config', $table_config)
            ->display('admin/product/product.tpl');
        return $response;
    }

    public function createProductIndex(ServerRequest $request, Response $response, array $args): Response
    {
        $this->view()->display('admin/product/create.tpl');
        return $response;
    }

    public function createProduct(ServerRequest $request, Response $response, array $args): Response
    {
        $postdata                     = $request->getParsedBody();
        $product                      = new Product();
        $product->name                = $postdata['name'];
        $product->month_price         = $postdata['month_price']     == '' ? NULL : $postdata['month_price'];
        $product->quarter_price       = $postdata['quarter_price']   == '' ? NULL : $postdata['quarter_price'];
        $product->half_year_price     = $postdata['half_year_price'] == '' ? NULL : $postdata['half_year_price'];
        $product->year_price          = $postdata['year_price']      == '' ? NULL : $postdata['year_price'];
        $product->two_year_price      = $postdata['two_year_price']  == '' ? NULL : $postdata['two_year_price'];
        $product->onetime_price       = $postdata['onetime_price']   == '' ? NULL : $postdata['onetime_price'];
        $product->custom_content      = $postdata['custom_content']  == '' ? NULL : $postdata['custom_content'];
        $product->type                = $postdata['type'];
        $product->sort                = $postdata['sort'] ?: 0;
        $product->traffic             = $postdata['traffic'];
        $product->user_group          = $postdata['group'] ?: 0;
        $product->class               = $postdata['class'] ?: 0;
        $product->reset_traffic_cycle = $postdata['reset'];
        $product->speed_limit         = $postdata['speed_limit'] == '' ? NULL : $postdata['speed_limit'];
        $product->ip_limit            = $postdata['ip_limit'] == '' ? NULL : $postdata['ip_limit'];
        $product->stock               = $postdata['stock'] == '' ? NULL : $postdata['stock'];
        $product->status              = 0;
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

    public function updateProductIndex(ServerRequest $request, Response $response, array $args): Response
    {
        $id = $args['id'];
        $product = Product::find($id);
        $this->view()
            ->assign('product', $product)
            ->display('admin/product/edit.tpl');
        return $response;
    }

    public function updateProduct(ServerRequest $request, Response $response, array $args): Response
    {
        $putdata = $request->getParsedBody();
        $id      = $putdata['id'];
        $product = Product::find($id);

        $product->name                = $putdata['name'];
        $product->month_price         = $putdata['month_price']     == '' ? NULL : $putdata['month_price'];
        $product->quarter_price       = $putdata['quarter_price']   == '' ? NULL : $putdata['quarter_price'];
        $product->half_year_price     = $putdata['half_year_price'] == '' ? NULL : $putdata['half_year_price'];
        $product->year_price          = $putdata['year_price']      == '' ? NULL : $putdata['year_price'];
        $product->two_year_price      = $putdata['two_year_price']  == '' ? NULL : $putdata['two_year_price'];
        $product->onetime_price       = $putdata['onetime_price']   == '' ? NULL : $putdata['onetime_price'];
        $product->custom_content      = $putdata['custom_content']  == '' ? NULL : $putdata['custom_content'];
        $product->type                = $putdata['type'];
        $product->sort                = $putdata['sort'] ?: 0;
        $product->traffic             = $putdata['traffic'];
        $product->user_group          = $putdata['group'] ?: 0;
        $product->class               = $putdata['class'] ?: 0;
        $product->reset_traffic_cycle = $putdata['reset'];
        $product->speed_limit         = $putdata['speed_limit'] == '' ? NULL : $putdata['speed_limit'];
        $product->ip_limit            = $putdata['ip_limit'] == '' ? NULL : $putdata['ip_limit'];
        $product->stock               = $putdata['stock'] == '' ? NULL : $putdata['stock'];
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


    public function productAjax(ServerRequest $request, Response $response, array $args): Response
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
                'id'     => $rowData->id,
                'sort'   => $rowData->sort,
                'name'   => $rowData->name,
                'type'   => $rowData->type(),
                'sales'  => $rowData->cumulativeSales(),
                'status' => $rowData->status(),
                'renew'  => $rowData->renew(),
                'action' => '<div class="btn-group dropstart"><a class="btn btn-light-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false">操作</a>
                                    <ul    class = "dropdown-menu">
                                    <li><a class = "dropdown-item" href = "product/update/'.$rowData->id.'">编辑</a></li>
                                    <li><a class = "dropdown-item" type = "button" onclick = "zeroAdminDelete('.$type.', '.$rowData->id.')">删除</a></li>
                                    </ul>
                                </div>',
            ];
        })->toArray();

        return $response->withJson([
            'draw'            => $request->getParsedBodyParam('draw'),
            'recordsTotal'    => Product::count(),
            'recordsFiltered' => $query['count'],
            'data'            => $data,
        ]);
    }

    public function updateProductStatus(ServerRequest $request, Response $response, array $args): Response
    {
        $putData = $request->getParsedBody();
        $id      = $putData['id'];
        $type    = $putData['type'];
        $method  = $putData['method'];
        $product = Product::find($id);
        if ($method == 'status') {
            $product->status = $type;
        } else if ($method == 'renew') {
            $product->renew = $type;
        }
        $product->save();
        return $response->withJson([
            'ret' => 1,
            'msg' => 'success'
        ]);
    }

    public function deleteProduct(ServerRequest $request, Response $response, array $args): Response
    {
        $id = $request->getParsedBodyParam('id');
        $product = Product::find($id);
        $product->delete();

        return $response->withJson([
            'ret' => 1,
            'msg' => '删除成功'
        ]);
    }

    public function getProductInfo(ServerRequest $request, Response $response, array $args): Response
    {
        $id = $request->getParsedBodyParam('id');
        $product = Product::find($id);
        $data = [
            'name'            => $product->name,
            'month_price'     => $product->month_price,
            'quarter_price'   => $product->quarter_price,
            'half_year_price' => $product->half_year_price,
            'year_price'      => $product->year_price,
            'two_year_price'  => $product->two_year_price,
            'type'            => $product->type,
        ];
        return $response->withJson($data);
    }
}