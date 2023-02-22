<?php

namespace App\Controllers;

use App\Models\{
    User,
    Coupon,
    Order,
    Node,
    Payback
};
use App\Utils\{
    Tools,
    DatatablesHelper
};
use App\Services\{
    Analytics
};
use Countable;
use Ozdemir\Datatables\Datatables;
use Slim\Http\{
    Request,
    Response
};

/**
 *  Admin Controller
 */
class AdminController extends UserController
{
    /**
     * 后台首页
     *
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function index($request, $response, $args)
    {
        $sts = new Analytics();
        $start_time_this_month = mktime(0,0,0,date('m'),1,date('Y'));
        $end_time_this_month = mktime(23,59,59,date('m'),date('t'),date('Y'));
        $start_time_last_month = mktime(0, 0, 0, date("m") - 1, 1, date("Y"));
        $end_time_last_month = mktime(23, 59, 59, date("m"), 0, date("Y"));
        $income_this_month = $sts->getIncome($start_time_this_month, $end_time_this_month);
        $new_users_this_month = $sts->getNewUsers(date("Y-m-d H:i:s", $start_time_this_month), date("Y-m-d H:i:s", $end_time_this_month));

        $this->view()
            ->assign('income_this_month', $income_this_month)
            ->assign('new_users_this_month', $new_users_this_month)
            ->display('admin/index.tpl');
        return $response;
    }

    /**
     * 节点列表
     *
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function node($request, $response, $args)
    {
        $this->view()->assign('nodes', Node::all())->display('admin/node.tpl');
        return $response;
    }
 
    /**
     * 统计信息
     *
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function sys($request, $response, $args)
    {
        $this->view()->display('admin/index.tpl');
        return $response;
    }

    /**
     * 后台邀请返利页面
     *
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function invite($request, $response, $args)
    {
        $table_config['total_column'] = array(
            'id'              => 'ID',
            'total'           => '原始金额',
            'userid'   => '发起用户ID',
            'ref_by'     => '获利用户ID',
            'ref_get'         => '获利金额',
            'datetime'        => '时间'
        );
        $table_config['default_show_column'] = array();
        $table_config['default_show_column'] = array_keys($table_config['total_column']);
        $table_config['ajax_url'] = 'payback/ajax';
        $this->view()->assign('table_config', $table_config)->display('admin/invite.tpl');
        return $response;
    }

    /**
     * 后台邀请返利页面 AJAX
     *
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function ajaxPayBack($request, $response, $args)
    {
        $query = Payback::getTableDataFromAdmin(
            $request
        );
        $data = [];
        foreach ($query['datas'] as $value) {
            $tempdata                   = [];
            $tempdata['id']             = $value->id;
            $tempdata['total']          = $value->total;
            $tempdata['userid']         = $value->userid;
            $tempdata['ref_by']         = $value->ref_by;
            $tempdata['ref_get']        = $value->ref_get;
            $tempdata['datetime']       = date('Y-m-d H:i:s', $value->datetime);
            $data[] = $tempdata;
        }
        return $response->WithJson([
            'draw'              => $request->getParam('draw'),
            'recordsTotal'      => Payback::count(),
            'recordsFiltered'   => $query['count'],
            'data'              => $data
        ]);
        return $response;
    }

    /**
     * 后台商品优惠码页面
     *
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function coupon($request, $response, $args)
    {
        $table_config['total_column'] = array(
            'id'        => 'ID',
            'code'      => '优惠码',
            'expire'    => '过期时间',
            'limited_product'      => '限定商品ID',
            'discount'    => '额度',
            'per_use_count'   => '每个用户次数',
            'total_use_count' => '优惠码总使用次数'
        );
        $table_config['default_show_column'] = array();
        foreach ($table_config['total_column'] as $column => $value) {
            $table_config['default_show_column'][] = $column;
        }
        $table_config['ajax_url'] = 'coupon/ajax';
        $this->view()->assign('table_config', $table_config)->display('admin/coupon.tpl');
        return $response;
    }
    
    /**
     * 后台商品优惠码页面 AJAX
     *
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function ajaxCoupon($request, $response, $args)
    {
        $query = Coupon::getTableDataFromAdmin(
            $request
        );
        $data = [];
        foreach ($query['datas'] as $value) {
            $tempdata['id'] = $value->id;
            $tempdata['code'] = $value->code;
            $tempdata['expire'] = date('Y-m-d H:i:s', $value->expire);
            $tempdata['limited_product'] = $value->limited_product == '' ? '无限制' : $value->limited_product;
            $tempdata['discount'] = $value->discount;
            $tempdata['per_use_count'] = $value->per_use_count == '-1' ? '无限次使用' : $value->per_use_count;
            $tempdata['total_use_count'] = $value->total_use_count == '-1' ? '无限次使用' : $value->total_use_count;

            $data[] = $tempdata;
        }
        return $response->WithJson([
            'draw'              => $request->getParam('draw'),
            'recordsTotal'      => Coupon::count(),
            'recordsFiltered'   => $query['count'],
            'data'              => $data
        ]);
        return $response;
    }

       /**
     * 添加优惠码
     *
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function addCoupon($request, $response, $args)
    {
        $generate_type = (int) $request->getParam('generate_type');
        $final_code    = $request->getParam('code');
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
        $code          = new Coupon();
        $code->per_use_count = $request->getParam('per_use_count');
        $code->total_use_count = $request->getParam('total_use_count');
        $code->code    = $final_code;
        $code->expire  = time() + $request->getParam('expire') * 3600;
        $code->limited_product    = $request->getParam('limited_product');
        $code->discount  = $request->getParam('discount');

        $code->save();

         return $response->withJson([
            'ret' => 1,
            'msg' => '优惠码添加成功'
        ]);
    }

    /**
     * 后台销售收入统计
     *
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function getIncome($request, $response, $args)
    {
        $time_a = strtotime(date('Y-m-d',$_SERVER['REQUEST_TIME'])) + 86400;
        $time_b = $time_a + 86400;
        $times = (strtotime(date("Y-m-d")) - strtotime("2020-1-1")) / 86400;
        $datas = [];
        for ($i=0; $i < $times ; $i++) {
            $time_a -= 86400;
            $time_b -= 86400;
            $total   = Order::where('order_status', 2)->where('order_payment', '!=', 'creditpay')->where('paid_time', '>=', $time_a)->where('paid_time', '<=', $time_b)->sum('order_total');
            $datas[] = [
                'x'  => date('Y-m-d', $time_a),
                'y' => $total ?? 0,
            ];
        }
        $response->getBody()->write(json_encode(array_reverse($datas)));
        return $response;
    }

    /**
     * 后台用户增加统计
     *
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function newUsers($request, $response, $args)
    {
        $time_a = strtotime(date('Y-m-d',$_SERVER['REQUEST_TIME'])) + 86400;
        $time_b = $time_a + 86400;
        $times = (strtotime(date("Y-m-d")) - strtotime("2020-1-1")) / 86400;
        $datas = [];
        for ($i=0; $i < $times ; $i++) {
            $time_a -= 86400;
            $time_b -= 86400;
            $total   = User::where('signup_date', '>=', date("Y-m-d",$time_a))->where('signup_date', '<', date("Y-m-d",$time_b))->count();
            $datas[] = [
                'x'  => date('Y-m-d', $time_a),
                'y' => $total ?? 0,
            ];
        }
        $response->getBody()->write(json_encode(array_reverse($datas)));
        return $response;
    }
}
