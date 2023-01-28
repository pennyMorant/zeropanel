<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\{
    Product,
    Setting,
};
use Slim\Http\{
    Request,
    Response
};
use Pkly\I18Next\I18n;

final class ProductController extends BaseController
{
    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function product($request, $response, $args)
    {
        $trans = I18n::get();
        $products = Product::where('status', '1')
            ->orderBy('sort', 'asc')
            ->get();
        $product_tab_lists = [
            [
                'type' => 'cycle',
                'name' => $trans->t('cycle'),
            ],
            [
                'type' => 'traffic',
                'name' => $trans->t('traffic'),
            ],
            [
                'type' => 'other',
                'name' => $trans->t('other'),
            ],
        ];

        $product_lists = [
            'cycle' => '时间流量包',
            'traffic' => '流量包',
            'other' => '其他商品',
        ];
        $all_products = Product::where('status', '1')->get();
        $count = [
            'cycle' => $all_products->where('type', 'cycle')->count(),
            'traffic' => $all_products->where('type', 'traffic')->count(),
            'other' => $all_products->where('type', 'other')->count(),
        ];
        
        $configs = Setting::getClass('flash_sell');
        $this->view()
            ->assign('products', $products)
            ->assign('product_lists', $product_lists)
            ->assign('product_tab_lists', $product_tab_lists)
            ->assign('count', $count)
            ->assign('settings', $configs)
            ->display('user/product.tpl');
        return $response;
    }

}