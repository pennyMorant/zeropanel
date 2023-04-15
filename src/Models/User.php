<?php

namespace App\Models;

use App\Utils\{
    Tools,
};
use App\Services\{
    Mail, 
};
use App\Models\Ip;
use Pkly\I18Next\I18n;
use Ramsey\Uuid\Uuid;
use Exception;

/**
 * User Model
 *
 * @property-read   int $id         ID
 * @property        bool    $is_admin           是否管理员
 * @todo More property
 * @property        bool $expire_notified    If user is notified for expire
 * @property        bool $traffic_notified   If user is noticed for low traffic
 */
class User extends Model
{
    protected $connection = 'default';
    protected $table = 'user';
    protected $dates = ['signup_date'];

    public bool $isLogin;

    /**
     * 强制类型转换
     *
     * @var array
     */
    protected $casts = [
        't'               => 'float',
        'u'               => 'float',
        'd'               => 'float',
        'transfer_enable' => 'float',
        'enable'          => 'int',
        'is_admin'        => 'boolean',
        'node_speedlimit' => 'float',
        'config'          => 'array',
        'ref_by'          => 'int'
    ];

    /**
     * Gravatar 头像地址
     */
    public function getGravatarAttribute()
    {
        // QQ邮箱用户使用QQ头像
        $email_su = substr($this->attributes['email'], -6);
        $email_pre = substr($this->attributes['email'], 0, -7);
        if ($email_su == "qq.com" and is_numeric($email_pre)) {
            return "https://q4.qlogo.cn/g?b=qq&nk=" . $this->attributes['email'] . "&s=3";
        }
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return 'https://gravatar.loli.net/avatar/' . $hash . "?&d=monsterid";
    }

    /**
     * 最后使用时间
     */
    public function lastUseTime(): string
    {
        return $this->t == 0 ? i18n::get()->t('never used') : Tools::toDateTime($this->t);
    }

    /**
     * 生成邀请码
     */
    public function addInviteCode(): string
    {
        while (true) {
            $temp_code = Tools::genRandomChar(10);
            if (is_null(InviteCode::where('code', $temp_code)->first())) {
                if (InviteCode::where('user_id', $this->id)->count() == 0) {
                    $code          = new InviteCode();
                    $code->code    = $temp_code;
                    $code->user_id = $this->id;
                    $code->save();
                    return $temp_code;
                } else {
                    return (InviteCode::where('user_id', $this->id)->first())->code;
                }
            }
        }
    }

    /*
     * 总流量[自动单位]
     */
    public function enableTraffic(): string
    {
        return Tools::flowAutoShow($this->transfer_enable);
    }

    /*
     * 总流量[GB]，不含单位
     */
    public function enableTrafficInGB(): float
    {
        return Tools::flowToGB($this->transfer_enable);
    }

    /*
     * 已用流量[自动单位]
     */
    public function usedTraffic(): string
    {
        return Tools::flowAutoShow($this->u + $this->d);
    }

    /*
     * 已用流量占总流量的百分比
     */
    public function trafficUsagePercent(): int
    {
        if ($this->transfer_enable == 0) {
            return 0;
        }
        $percent  = ($this->u + $this->d) / $this->transfer_enable;
        $percent  = round($percent, 2);
        $percent *= 100;
        return $percent;
    }

    /*
     * 剩余流量[自动单位]
     */
    public function unusedTraffic(): string
    {
        return Tools::flowAutoShow($this->transfer_enable - ($this->u + $this->d));
    }

    /*
     * 剩余流量占总流量的百分比
     */
    public function unusedTrafficPercent(): int
    {
        if ($this->transfer_enable == 0) {
            return 0;
        }
        $unused   = $this->transfer_enable - ($this->u + $this->d);
        $percent  = $unused / $this->transfer_enable;
        $percent  = round($percent, 2);
        $percent *= 100;
        return $percent;
    }

    /*
     * 今天使用的流量[自动单位]
     */
    public function TodayusedTraffic(): string
    {
        return Tools::flowAutoShow($this->u + $this->d - $this->last_day_t);
    }

    /*
     * 今天使用的流量占总流量的百分比
     */
    public function TodayusedTrafficPercent(): int
    {
        if ($this->transfer_enable == 0) {
            return 0;
        }
        $Todayused = $this->u + $this->d - $this->last_day_t;
        $percent   = $Todayused / $this->transfer_enable;
        $percent   = round($percent, 2);
        $percent  *= 100;
        return $percent;
    }

    /*
     * 今天之前已使用的流量[自动单位]
     */
    public function LastusedTraffic(): string
    {
        return Tools::flowAutoShow($this->last_day_t);
    }

    /*
     * 今天之前已使用的流量占总流量的百分比
     */
    public function LastusedTrafficPercent(): int
    {
        if ($this->transfer_enable == 0) {
            return 0;
        }
        $Lastused = $this->last_day_t;
        $percent  = $Lastused / $this->transfer_enable;
        $percent  = round($percent, 2);
        $percent *= 100;
        return $percent;
    }

    /*
     * @param traffic 单位 MB
     */
    public function addTraffic($traffic)
    {
    }

    /**
     * 获取用户的邀请码
     */
    public function getInviteCode()
    {
        return InviteCode::where('user_id', $this->id)->first();
    }

    /**
     * 删除用户的邀请码
     */
    public function clearInviteCode()
    {
        InviteCode::where('user_id', $this->id)->delete();
    }

    /**
     * 在线 IP 个数
     */
    public function onlineIPCount(): int
    {
        $total_ip = IP::selectRaw('userid, COUNT(DISTINCT ip) AS count')
            ->where('datetime', '>=', time() - 180)
            ->where('userid', $this->id)
            ->groupBy('userid')
            ->first();

        $res = $total_ip->count ?? 0;
        return $res;
    }

    /**
     * 销户
     */
    public function deleteUser(): bool
    {
        $uid   = $this->id;
        DetectBanLog::where('user_id', '=', $uid)->delete();
        DetectLog::where('user_id', '=', $uid)->delete();
        InviteCode::where('user_id', '=', $uid)->delete();
        Ip::where('userid', '=', $uid)->delete();
        SigninIp::where('userid', '=', $uid)->delete();
        Token::where('user_id', '=', $uid)->delete();
        UserSubscribeLog::where('user_id', '=', $uid)->delete();

        $this->delete();

        return true;
    }

    /**
     * 累计充值金额
     */
    public function getTotalIncome(): float
    {
        $number = Order::where('user_id', $this->id)->where('order_status', 2)->sum('order_total');
        return is_null($number) ? 0.00 : round($number, 2);
    }

    /**
     * 获取累计收入
     *
     * @param string $req
     */
    public function calIncome(string $req): float
    {
        switch ($req) {
            case "yesterday":
                $number = Order::whereDate('paid_time', '=', date('Y-m-d', strtotime('-1 days')))->sum('order_total');
                break;
            case "today":
                $number = Order::whereDate('paid_time', '=', date('Y-m-d'))->sum('order_total');
                break;
            case "this month":
                $number = Order::whereYear('paid_time', '=', date('Y'))->whereMonth('usedatetime', '=', date('m'))->sum('order_total');
                break;
            case "last month":
                $number = Order::whereYear('paid_time', '=', date('Y'))->whereMonth('paid_time', '=', date('m', strtotime('last month')))->sum('order_total');
                break;
            default:
                $number = Order::sum('order_total');
                break;
        }
        return is_null($number) ? 0.00 : round($number, 2);
    }

    /**
     * 获取付费用户总数
     */
    public function paidUserCount(): int
    {
        return self::where('class', '>', '0')->count();
    }

    public function allUserCount()
    {
        return self::count();
    }

    public function transformation()
    {
        $all = $this->allUserCount();
        $paid = $this->paidUserCount();

        return round($paid / $all * 100) . '%';
    }

    /**
     * 获取用户被封禁的理由
     */
    public function disableReason(): string
    {
        $reason_id = DetectLog::where('user_id', $this->id)->orderBy('id', 'DESC')->first();
        $reason    = DetectRule::find($reason_id->list_id);
        if (is_null($reason)) {
            return '特殊原因被禁用，了解详情请联系管理员';
        }
        return $reason->text;
    }

    /**
     * 最后一次被封禁的时间
     */
    public function last_detect_ban_time(): string
    {
        return ($this->last_detect_ban_time == '1989-06-04 00:05:00' ? '未被封禁过' : $this->last_detect_ban_time);
    }

    /**
     * 当前解封时间
     */
    public function relieve_time(): string
    {
        $logs = DetectBanLog::where('user_id', $this->id)->orderBy('id', 'desc')->first();
        if ($this->enable == 0 && !is_null($logs)) {
            $time = ($logs->end_time + $logs->ban_time * 60);
            return date('Y-m-d H:i:s', $time);
        } else {
            return '当前未被封禁';
        }
    }

    /**
     * 累计被封禁的次数
     */
    public function detect_ban_number(): int
    {
        return DetectBanLog::where('user_id', $this->id)->count();
    }

    /**
     * 最后一次封禁的违规次数
     */
    public function user_detect_ban_number(): int
    {
        $logs = DetectBanLog::where('user_id', $this->id)->orderBy('id', 'desc')->first();
        return $logs->detect_number;
    }

    /**
     * 当前用户产品流量重置周期
     */
    public function userTrafficResetCycle()
    {
        $product = Product::find($this->product_id);
        $cycle = $product->reset_traffic_cycle;
        return $cycle;
    }

    /**
     * 用户下次流量重置时间
     */
    public function productTrafficResetDate()
    {
        if (!is_null($this->reset_traffic_date)) {
            $reset_d = $this->reset_traffic_date;
            $today = date('d');
            if ($today >= $reset_d) {               
                $ym = date('Y-m', strtotime('+1 month'));
                $reset_date = $ym.'-'.$reset_d;
                return $reset_date;
            } else if ($today < $reset_d) {
                $ym = date('Y-m');
                $reset_date = $ym.'-'.$reset_d;
                return $reset_date;
            }          
        } else {
            return I18n::get()->t('no need to reset');
        }
    }

    /**
     * 发送邮件
     *
     * @param string $subject
     * @param string $template
     * @param array  $ary
     * @param array  $files
     */
    public function sendMail(string $subject, string $template, array $ary = [], array $files = [], $is_queue = false): bool
    {
        $result = false;
        if ($is_queue) {
            $new_emailqueue           = new EmailQueue;
            $new_emailqueue->to_email = $this->email;
            $new_emailqueue->subject  = $subject;
            $new_emailqueue->template = $template;
            $new_emailqueue->time     = time();
            $ary                      = array_merge(['user' => $this], $ary);
            $new_emailqueue->array    = json_encode($ary);
            $new_emailqueue->save();
            return true;
        }
          // 验证邮箱地址是否正确
        if (Tools::isEmail($this->email)) {
              // 发送邮件
            try {
                Mail::send(
                    $this->email,
                    $subject,
                    $template,
                    array_merge(
                        [
                            'user' => $this
                        ],
                        $ary
                    ),
                    $files
                );
                $result = true;
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }
        return $result;
    }

    
    /**
     * 记录登录 IP
     *
     * @param string $ip
     * @param int    $type 登录失败为 1
     */
    public function collectSigninIp(string $ip, int $type = 0): bool
    {
        $signin           = new SigninIp();
        $signin->ip       = $ip;
        $signin->userid   = $this->id;
        $signin->datetime = time();
        $signin->type     = $type;

        return $signin->save();
    }

    public function enable()
    {
        switch ($this->enable) {
            case 0:
                $enable = '<div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" value="" id="user_enable_'.$this->id.'" onclick="updateUserStatus(\'enables\', '.$this->id.')" />
                            </div>';
                break;
            case 1:
                $enable = '<div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" value="" id="user_enable_'.$this->id.'" checked="checked" onclick="updateUserStatus(\'$enables\', '.$this->id.')" />
                            </div>';
                break;
        }
        return $enable;
    }

    public function is_admin()
    {
        $is_admins = "'is_admin'";
        switch ($this->is_admin) {
            case 0:
                $is_admin = '<div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" value="" id="user_is_admin_'.$this->id.'" onclick="updateUserStatus('.$is_admins.', '.$this->id.')" />
                            </div>';
                break;
            case 1:
                $is_admin = '<div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" value="" id="user_is_admin_'.$this->id.'" checked="checked" onclick="updateUserStatus('.$is_admins.', '.$this->id.')" />
                            </div>';
                break;
        }
        return $is_admin;
    }

    public function createSubToken($length = 16)
    { 
        for ($i = 0; $i < 10; $i++) {
            $token = bin2hex(openssl_random_pseudo_bytes($length / 2));
            $is_token_used = User::where('subscription_token', $token)->first();
            if (is_null($is_token_used)) {               
                return $token;
            }
        }
    }

    public function createShadowsocksPasswd($length = 32) 
    {
        $passwd = base64_encode(openssl_random_pseudo_bytes($length));
        return $passwd;
    }

    public function createUUID($current_timestamp)
    {
        $uuid = Uuid::uuid5(Uuid::NAMESPACE_DNS, $this->email . '|' . $current_timestamp);
        return $uuid;
    }

    public function getPermission($class)
    {
        if (Setting::obtain('enable_permission_group') == true) {
            $permission_group = json_decode(Setting::obtain('permission_group_detail'), true);
        }
        $permission = isset($permission_group) ? $permission_group[$class] : 'LV-'.$class;

        return $permission;
    }
}
