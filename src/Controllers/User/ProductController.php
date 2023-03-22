<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\{
    Product,
    Setting,
    Ann,
};
use Slim\Http\Response;
use Slim\Http\ServerRequest;
use Pkly\I18Next\I18n;

final class ProductController extends BaseController
{
    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function product(ServerRequest $request, Response $response, $args)
    {
        $trans = I18n::get();
        $products = Product::where('status', '1')
            ->orderBy('sort', 'desc')
            ->get();
        $product_tab_lists = [
            [
                'type' => 1,
                'name' => $trans->t('cycle'),
            ],
            [
                'type' => 2,
                'name' => $trans->t('traffic'),
            ],
            [
                'type' => 3,
                'name' => $trans->t('other'),
            ],
        ];

        $product_lists = [
            1 => $trans->t('cycle'),
            2 => $trans->t('traffic'),
            3 => $trans->t('other'),
        ];
        $all_products = Product::where('status', '1')->get();
        $count = [
            1 => $all_products->where('type', 1)->count(),
            2 => $all_products->where('type', 2)->count(),
            3 => $all_products->where('type', 3)->count(),
        ];
        if (Setting::obtain('enable_permission_group') == true) {
            $permission_group = json_decode(Setting::obtain('permission_group_detail'), true);
        } else {
            $permission_group = [
                0   =>  'LV-0',
                1   =>  'LV-1', 
                2   =>  'LV-2', 
                3   =>  'LV-3', 
                4   =>  'LV-4', 
                5   =>  'LV-5', 
                6   =>  'LV-6', 
                7   =>  'LV-7',
                8   =>  'LV-8', 
                9   =>  'LV-9', 
                10  =>  'LV-10',
            ];
        }
        $currency_unit = Setting::obtain('currency_unit');
        $this->view()
            ->assign('products', $products)
            ->assign('product_lists', $product_lists)
            ->assign('product_tab_lists', $product_tab_lists)
            ->assign('currency_unit', $currency_unit)
            ->assign('permission_group', $permission_group)
            ->assign('count', $count)
            ->assign('anns', Ann::where('date', '>=', date('Y-m-d H:i:s', time() - 7 * 86400))->orderBy('date', 'desc')->get())
            ->display('user/product.tpl');
        return $response;
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