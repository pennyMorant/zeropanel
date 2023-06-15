<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\{
    Product,
    Setting,
    Ann,
    Order
};
use Slim\Http\Response;
use Slim\Http\ServerRequest;
use Pkly\I18Next\I18n;

final class ProductController extends BaseController
{
    public function productIndex(ServerRequest $request, Response $response, array $args)
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
            ->display('user/product.tpl');
        return $response;
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
            'onetime_price'   => $product->onetime_price,
            'type'            => $product->type,
        ];
        return $response->withJson($data);
    }

}