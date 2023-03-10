<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\{
    Product,
    Setting,
    Ann,
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
        $currency_unit = Setting::obtain('currency_unit');
        $this->view()
            ->assign('products', $products)
            ->assign('product_lists', $product_lists)
            ->assign('product_tab_lists', $product_tab_lists)
            ->assign('currency_unit', $currency_unit)
            ->assign('count', $count)
            ->assign('anns', Ann::where('date', '>=', date('Y-m-d H:i:s', time() - 7 * 86400))->orderBy('date', 'desc')->get())
            ->display('user/product.tpl');
        return $response;
    }

}