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
            'op'                    => '操作',
            'id'                    => 'ID',
            'sort'                  => 'Sort',   
            'name'                  => '商品名称',
            'price'                 => '价格',
            'type'                  => '产品类型',
            'period_sales'          => '周期销量'
        );
        $table_config['default_show_column'] = array();
        foreach ($table_config['total_column'] as $column => $value) {
            $table_config['default_show_column'][] = $column;
        }
        $table_config['ajax_url'] = 'shop/ajax';
        $this->view()
            ->assign('table_config', $table_config)
            ->display('admin/shop/index.tpl');
        return $response;
    }

    /**
     * 后台创建新商品页面
     *
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function create($request, $response, $args)
    {
        $this->view()->display('admin/shop/create.tpl');
        return $response;
    }

    /**
     * 后台添加新商品
     *
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function add($request, $response, $args)
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
        $product->user_group = $request->getParam('node_group');
        $product->class = $request->getParam('class');
        $product->reset_traffic_cycle = $request->getParam('reset');
        $product->speed_limit = $request->getParam('speed_limit');
        $product->ip_limit = $request->getParam('ip_limit');
        $product->stock = $request->getParam('stock');
        $product->status = 1;
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
    public function edit($request, $response, $args)
    {
        $id = $args['id'];
        $product = Product::find($id);
        $this->view()
            ->assign('product', $product)
            ->display('admin/shop/edit.tpl');
        return $response;
    }

    /**
     * 后台更新指定商品内容
     *
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function update($request, $response, $args)
    {
        $id = $args['id'];
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
        $product->status = 1;
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
    public function ajaxShop($request, $response, $args)
    {
        $query = Product::getTableDataFromAdmin(
            $request,
            static function (&$order_field) {
                if (in_array($order_field, ['op', 'period_sales'])) {
                    $order_field = 'id';
                }
            }
        );

        $data  = [];
        foreach ($query['datas'] as $value) {
            /** @var Shop $value */

            $tempdata                         = [];
            $tempdata['op']                   = '<a class="btn btn-brand" href="/admin/shop/' . $value->id . '/edit">编辑</a> <a class = "btn btn-brand-accent" ' . ($value->status == 0 ? 'disabled' : 'id="row_delete_' . $value->id . '" href="javascript:void(0);" onClick="delete_modal_show(\'' . $value->id . '\')"') . '>下架</a>';
            $tempdata['id']                   = $value->id;
            $tempdata['sort']                 = $value->sort;
            $tempdata['name']                 = $value->name;
            $tempdata['price']                = $value->price;
            $tempdata['type']                 = $value->type;
            
            $tempdata['period_sales']         = $value->sales;

            $data[] = $tempdata;
        }

        return $response->withJson([
            'draw'            => $request->getParam('draw'),
            'recordsTotal'    => Product::count(),
            'recordsFiltered' => $query['count'],
            'data'            => $data,
        ]);
    }
}