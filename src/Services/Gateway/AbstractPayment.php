<?php
/**
 * Created by PhpStorm.
 * User: tonyzou
 * Date: 2018/9/24
 * Time: 下午4:23
 */

namespace App\Services\Gateway;

use App\Models\User;
use App\Models\Code;
use App\Models\Order;
use App\Models\Payback;
use App\Models\Setting;
use Slim\Http\{
    Request,
    Response
};
use App\Zero\{
    Zero,
    Telegram
};

abstract class AbstractPayment
{
    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    abstract public function purchase($user_id, $method, $order_no, $amount);

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    abstract public function notify($request, $response, $args);

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    abstract public function getReturnHTML($request, $response, $args);

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    abstract public function getStatus($request, $response, $args);

    abstract public function getPurchaseHTML();
    
    protected static function getCallbackUrl() {
        return Setting::obtain('website_url') . '/payment/notify/' . (get_called_class())::_name();
    }
    public function postPayment($pid, $method)
    {
        $p = Order::where('tradeno', $pid)->first();

        if ($p->status == 1) {
            return ['errcode' => 0, 'errmsg' => '交易已完成'];
        }

        $p->status = 1;
        $p->save();
        $user = User::find($p->userid);
        $user->money = bcadd($user->money, $p->total, 2);
        $user->save();
        $codeq = new Code();
        $codeq->code = $method;
        $codeq->isused = 1;
        $codeq->type = -1;
        $codeq->number = $p->total;
        $codeq->usedatetime = date('Y-m-d H:i:s');
        $codeq->userid = $user->id;
        $codeq->save();
        
        if ($p->shop != null) {
            $p_buy = Zero::zeropay_buyshop($pid);
        }
        if ($user->agent === 1) {
            if ($user->ref_by != '' && $user->ref_by != 0 && $user->ref_by != null) {
                Zero::getCommission( User::find($user->ref_by), $user, $codeq->number);
            }
        } else {
            // 返利
            if ($user->ref_by > 0 && Setting::obtain('invitation_mode') == 'after_topup' && $user->agent === 0) {
                Payback::rebate($user->id, $p->total);
            }
        }

        if (Setting::obtain('enable_push_top_up_message') == true) {
            Telegram::SendPayment($user, $p, $codeq);
        }

        return ['errcode' => 1, 'errmsg' => '回调处理完成'];;
    }

    public static function generateGuid()
    {
        mt_srand((double)microtime() * 10000);
        $charid = strtoupper(md5(uniqid(mt_rand() + time(), true)));
        $hyphen = chr(45);
        $uuid = chr(123)
            . substr($charid, 0, 8) . $hyphen
            . substr($charid, 8, 4) . $hyphen
            . substr($charid, 12, 4) . $hyphen
            . substr($charid, 16, 4) . $hyphen
            . substr($charid, 20, 12)
            . chr(125);
        $uuid = str_replace(['}', '{', '-'], '', $uuid);
        $uuid = substr($uuid, 0, 12);
        return $uuid;
    }
}
