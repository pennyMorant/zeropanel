<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminController;
use App\Models\{
    Product,
    Bought,
    Order
};
use Slim\Http\{
    Request,
    Response
};

class ProductController extends AdminController
{
    /**
     * 后台商品页面
     *
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function index($request, $response, $args)
    {
        $table_config['total_column'] = array(
            'id'                    => 'ID',
            'sort'                  => '排序',   
            'name'                  => '商品名称',
            'type'                  => '产品类型',
            'period_sales'          => '周期销量',
            'status'                => '状态',
            'action'                => '操作'
        );
        $table_config['default_show_column'] = array();
        foreach ($table_config['total_column'] as $column => $value) {
            $table_config['default_show_column'][] = $column;
        }
        $table_config['ajax_url'] = 'product/ajax';
        $this->view()
            ->assign('table_config', $table_config)
            ->display('admin/product/product.tpl');
        return $response;
    }

    /**
     * 后台创建新商品页面
     *
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function createProductIndex($request, $response, $args)
    {
        $this->view()->display('admin/product/create.tpl');
        return $response;
    }

    /**
     * 后台添加新商品
     *
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function createProduct($request, $response, $args)
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
        $product->traffic = $request->getParam('traffic');
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

    /**
     * 后台编辑指定商品
     *
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function updateProductIndex($request, $response, $args)
    {
        $id = $args['id'];
        $product = Product::find($id);
        $this->view()
            ->assign('product', $product)
            ->display('admin/product/edit.tpl');
        return $response;
    }

    /**
     * 后台更新指定商品内容
     *
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function updateProduct($request, $response, $args)
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
        $product->traffic = $request->getParam('traffic');

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


    /**
     * 后台商品页面 AJAX
     *
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function productAjax($request, $response, $args)
    {
        $query = Product::getTableDataFromAdmin(
            $request,
            static function (&$order_field) {
                if (in_array($order_field, ['action', 'period_sales', 'name'])) {
                    $order_field = 'id';
                }
            }
        );

        $type = "'product'";
        $data  = [];
        foreach ($query['datas'] as $value) {
            /** @var Shop $value */

            $tempdata                         = [];
            $tempdata['id']                   = $value->id;
            $tempdata['sort']                 = $value->sort;
            $tempdata['name']                 = $value->name;
            $tempdata['type']                 = $value->type;
            $tempdata['period_sales']         = $value->sales;
            $tempdata['status']               = $value->status();
            $tempdata['action']               = '<div class="btn-group dropstart"><a class="btn btn-light-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false">操作</a>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="/admin/product/update/'.$value->id.'">编辑</a></li>
                                                        <li><a class="dropdown-item" type="button" onclick="zeroAdminDelete('.$type.', '.$value->id.')">删除</a></li>
                                                    </ul>
                                                </div>';

            $data[] = $tempdata;
        }

        return $response->withJson([
            'draw'            => $request->getParam('draw'),
            'recordsTotal'    => Product::count(),
            'recordsFiltered' => $query['count'],
            'data'            => $data,
        ]);
    }

    /**
     * 
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function updateProductStatus($request, $response, $args)
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

    /**
     * 
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function deleteProduct($request, $response, $args)
    {
        $id = $request->getParam('id');
        $product = Product::find($id);
        $product->delete();

        return $response->withJson([
            'ret' => 1,
            'msg' => '删除成功'
        ]);
    }
}