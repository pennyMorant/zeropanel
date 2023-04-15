<?php
namespace App\Controllers\Admin;

use App\Controllers\AdminController;
use App\Models\Coupon;
use App\Utils\Tools;
use Slim\Http\Response;
use Slim\Http\ServerRequest;

class CouponController extends AdminController
{
    public function couponIndex(ServerRequest $request, Response $response, array $args)
    {
        $table_config['total_column'] = [
            'id'              => 'ID',
            'code'            => '优惠码',
            'expire'          => '过期时间',
            'limited_product' => '限定商品ID',
            'discount'        => '额度',
            'per_use_count'   => '每个用户次数',
            'total_use_count' => '优惠码总使用次数'
        ];
        $table_config['ajax_url'] = 'coupon/ajax';
        $this->view()->assign('table_config', $table_config)->display('admin/coupon.tpl');
        return $response;
    }

    public function createCoupon(ServerRequest $request, Response $response, array $args)
    {
        $postdata = $request->getParsedBody();
        $generate_type = $postdata['generate_type'];
        $final_code    = $postdata['code'];
        if (empty($final_code) && in_array($generate_type, [1, 3])) {
            return $response->withJson([
                'ret' => 0,
                'msg' => '优惠码不能为空'
            ]);
        }

        if ($generate_type == 1) {
            if (Coupon::where('code', $final_code)->count() != 0) {
                 return $response->withJson([
                    'ret' => 0,
                    'msg' => '优惠码已存在'
                ]);
            }
        } else {
            while (true) {
                $code_str = Tools::genRandomChar(8);
                if ($generate_type == 3) {
                    $final_code .= $code_str;
                } else {
                    $final_code  = $code_str;
                }

                if (Coupon::where('code', $final_code)->count() == 0) {
                    break;
                }
            }
        }
        $code                  = new Coupon();
        $code->per_use_count   = $postdata['per_use_count'];
        $code->total_use_count = $postdata['total_use_count'];
        $code->code            = $final_code;
        $code->expire          = time() + $postdata['expire'] * 3600;
        $code->limited_product = $postdata['limited_product'];
        $code->discount        = $postdata['discount'];

        $code->save();

         return $response->withJson([
            'ret' => 1,
            'msg' => '优惠码添加成功'
        ]);
    }

    public function couponAjax(ServerRequest $request, Response $response, array $args)
    {
        $query = Coupon::getTableDataFromAdmin(
            $request
        );
        $data = $query['datas']->map(function($rowData) {
            return [
                'id'              => $rowData->id,
                'code'            => $rowData->code,
                'expire'          => date('Y-m-d H:i:s', $rowData->expire),
                'limited_product' => $rowData->limited_product == '' ? '无限制' : $rowData->limited_product,
                'discount'        => $rowData->discount,
                'per_use_count'   => $rowData->per_use_count == '-1' ? '无限次使用' : $rowData->per_use_count,
                'total_use_count' => $rowData->total_use_count == '-1' ? '无限次使用' : $rowData->total_use_count,
            ];
        })->toArray();

        return $response->WithJson([
            'draw'              => $request->getParam('draw'),
            'recordsTotal'      => Coupon::count(),
            'recordsFiltered'   => $query['count'],
            'data'              => $data
        ]);
        
    }
}