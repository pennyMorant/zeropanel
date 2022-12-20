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
        $shop = new Product();
        $shop->name = $request->getParam('name');
        $shop->price = $request->getParam('price');
        $shop->type = $request->getParam('type');
        $shop->sort = $request->getParam('sort');
        $shop->traffic = $request->getParam('traffic');
        $shop->account_validity_period = $request->getParam('expire');
        $shop->user_group = $request->getParam('node_group');
        $shop->class = $request->getParam('class');
        $shop->class_validity_period = $request->getParam('class_expire');
        $shop->traffic_reset_period = $request->getParam('reset');
        $shop->traffic_reset_validity_period = $request->getParam('reset_exp');
        $shop->traffic_reset_value = $request->getParam('reset_value');
        $shop->speed_limit = $request->getParam('speed_limit');
        $shop->ip_limit = $request->getParam('ip_limit');
        $shop->stock = $request->getParam('stock');

        if (!$shop->save()) {
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
        $shop = Product::find($id);
        $this->view()
            ->assign('shop', $shop)
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
        $shop = Product::find($id);

        $shop->name = $request->getParam('name');
        $shop->price = $request->getParam('price');
        $shop->type = $request->getParam('type');
        $shop->sort = $request->getParam('sort');
        $shop->traffic = $request->getParam('traffic');
        $shop->account_validity_period = $request->getParam('expire');
        $shop->user_group = $request->getParam('node_group');
        $shop->class = $request->getParam('class');
        $shop->class_validity_period = $request->getParam('class_expire');
        $shop->traffic_reset_period = $request->getParam('reset');
        $shop->traffic_reset_validity_period = $request->getParam('reset_exp');
        $shop->traffic_reset_value = $request->getParam('reset_value');
        $shop->speed_limit = $request->getParam('speed_limit');
        $shop->ip_limit = $request->getParam('ip_limit');
        $shop->stock = $request->getParam('stock') - $shop->sales;
        $shop->status = 1;
        if (!$shop->save()) {
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