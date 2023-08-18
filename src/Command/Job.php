<?php

namespace App\Command;

use App\Models\EmailQueue;
use App\Models\Ip;
use App\Models\Node;
use App\Models\User;
use App\Models\Token;
use App\Models\SigninIp;
use App\Models\TrafficLog;
use App\Models\NodeInfoLog;
use App\Models\NodeOnlineLog;
use App\Models\UserSubscribeLog;
use App\Models\Setting;
use App\Models\Order;
use App\Models\DetectBanLog;
use App\Models\DetectLog;
use App\Services\Mail;
use App\Utils\Tools;
use App\Utils\Telegram;
use Swap\Builder;
use Exception;

class Job extends Command
{
    public $description = ''
    . '├─=: php xcat Job [选项]' . PHP_EOL
    . '│ ├─ DailyJob                - 每日任务' . PHP_EOL
    . '│ ├─ CheckJob                - 检查任务，每分钟' . PHP_EOL
    . '│ ├─ CheckUserClassExpire    - 检查用户会员等级过期任务，每分钟' . PHP_EOL
    . '│ ├─ CheckOrderStatus        - 检查订单状态任务，每分钟' . PHP_EOL
    . '│ ├─ UserJob                 - 用户账户相关任务，每小时' . PHP_EOL
    . '│ ├─ SendMail                - 处理邮件队列' . PHP_EOL;

    public function boot()
    {
        if (count($this->argv) === 2) {
            echo $this->description;
        } else {
            $methodName = $this->argv[2];
            if (method_exists($this, $methodName)) {
                $this->$methodName();
            } else {
                echo '方法不存在.' . PHP_EOL;
            }
        }
    }

    /**
     * 每日任务
     *
     * @return void
     */
    public function DailyJob()
    {
        ini_set('memory_limit', '-1');

        // 重置节点流量
        echo '重置节点流量开始' . PHP_EOL;
        Node::where('node_traffic_limit_reset_date', date('d'))->update(['node_traffic' => 0]);
        echo '重置节点流量结束;' . PHP_EOL;

        // 清理各表记录
        echo '清理数据库各表开始' . PHP_EOL;
        UserSubscribeLog::where('created_at', '<',  time() - 86400 * (int)Setting::obtain('subscribe_log_keep_time'))->delete();
        Token::where('expired_at', '<', time())->delete();
        DetectLog::where('created_at', '<', time() - 86400 * 3)->delete();
        Ip::where('created_at', '<', time() - 300)->delete();
        SigninIp::where('created_at', '<', time() - 86400 * 7)->delete();
        TrafficLog::where('created_at', '<', time() - 86400 * 10)->delete();
        NodeOnlineLog::where('created_at', '<', time() - 86400 * 3)->delete();
        NodeInfoLog::where('created_at', '<', time() - 86400 * 3)->delete();
        echo '清理数据库各表结束;' . PHP_EOL;

        //auto reset
        echo '重置用户流量开始' . PHP_EOL;
        
        $users = User::where('class','>=', '1')->get();
        foreach ($users as $user) {
            if (is_null($user)) {
                continue;
            }
            if (!is_null($user->reset_traffic_date) && strtotime($user->class_expire)-time()>86400) {               
                if (date('d') == $user->reset_traffic_date) {
                    echo('用户ID:' . $user->id . ' 重置流量为' . $user->reset_traffic_value . 'GB' . PHP_EOL);
                    $user->transfer_enable = Tools::toGB($user->reset_traffic_value ?? 0);
                    $user->u = 0;
                    $user->d = 0;
                    $user->last_day_t = 0;
                    $user->save();
                    $user->sendMail(
                        Setting::obtain('website_name') . '-您的流量被重置了',
                        'news/warn.tpl',
                        [
                            'text' => '您好，您的流量被重置为' . $user->reset_traffic_value . 'GB' 
                        ],
                        [],
                        $_ENV['email_queue']
                    );
                }

            }
        }
    
        echo '重置用户流量结束' . PHP_EOL;

        echo '每日数据库清理成功报告发送开始' . PHP_EOL;
        //$messageText = Setting::obtain('diy_system_clean_database_report_telegram_notify_content');
        //Telegram::pushToAdmin($messageText);
        echo '每日数据库清理成功报告发送结束' . PHP_EOL;
        
        $configs = Setting::getClass('currency');
        if ($configs['enable_currency'] && !empty($configs['currency_exchange_rate_api_key'])) {
            $swap = (new Builder())
                ->add('abstract_api', ['api_key' => $configs['currency_exchange_rate_api_key']])
            ->build();
            $rate           = $swap->latest($configs['currency_unit'] . '/CNY');
            $result         = $rate->getValue();
            $setting        = Setting::where('item', '=', 'currency_exchange_rate')->first();
            $setting->value = substr($result, 0, 4);
            $setting->save();
        }
        echo 'Success ' . date('Y-m-d H:i:s', time()) . PHP_EOL;
    }

    /**
     * 检查任务，每分钟
     *
     * @return void
     */
    public function CheckJob()
    {
        //节点掉线检测
        echo '节点掉线检测开始' . PHP_EOL;
        $nodes = Node::all();
        foreach ($nodes as $node) {
            if ($node->isNodeOnline() === false && $node->online == true) {              
                $node->online = false;
                $node->save();
                $notice_text = str_replace(
                    '%node_name%',
                    $node->name,
                    Setting::obtain('diy_system_node_offline_report_telegram_notify_content')
                );
                //Telegram::pushToAdmin($notice_text);
            } elseif ($node->isNodeOnline() === true && $node->online == false) {
                $node->online = true;
                $node->save();
                $notice_text = str_replace(
                    '%node_name%',
                    $node->name,
                    Setting::obtain('diy_system_node_online_report_telegram_notify_content')
                );
                //Telegram::pushToAdmin($notice_text);          
            }
        }
        echo '节点掉线检测结束' . PHP_EOL;
        echo 'Success ' . date('Y-m-d H:i:s', time()) . PHP_EOL;
    }

    

    /**
     * 用户账户相关任务，每小时
     *
     * @return void
     */
    public function UserJob()
    {
        $users = User::all();
        foreach ($users as $user) {          
            // 审计封禁解封
            if ($user->enable == 0) {
                $logs = DetectBanLog::where('user_id', $user->id)->orderBy('id', 'desc')->first();
                if (!is_null($logs)) {
                    if (($logs->end_time + $logs->ban_time * 60) <= time()) {
                        $user->enable = 1;
                    }
                }
            }
            $user->save();
        }
    }

    /**
     * 发邮件
     *
     * @return void
     */
    public function SendMail()
    {
        if (file_exists(BASE_PATH . '/storage/email_queue')) {
            echo "程序正在运行中" . PHP_EOL;
            return false;
        }
        $myfile = fopen(BASE_PATH . '/storage/email_queue', 'wb+') or die('Unable to open file!');
        $txt = '1';
        fwrite($myfile, $txt);
        fclose($myfile);
        // 分块处理，节省内存
        EmailQueue::chunkById(1000, function ($email_queues) {
            foreach ($email_queues as $email_queue) {
                try {
                    Mail::send($email_queue->to_email, $email_queue->subject, $email_queue->template, json_decode($email_queue->array), []);
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
                echo '发送邮件至 ' . $email_queue->to_email . PHP_EOL;
                $email_queue->delete();
            }
        });
        unlink(BASE_PATH . '/storage/email_queue');
        echo 'Success ' . date('Y-m-d H:i:s', time()) . PHP_EOL;
    }
    
    /**
     * 检查用户等级过期时间
     */
    public function CheckUserClassExpire()
    {
        $configs = Setting::getClass('register');
        echo '用户等级过期检测开始' . PHP_EOL;
        $users = User::where('class_expire', '<', date('Y-m-d H:i:s', time()))
            ->where('class', '!=', 0)
            //->where('is_admin', '!=', 1)
            ->get();

        foreach ($users as $user) {
            $text                  = '您好，您的订阅产品已到期。';
            $user->transfer_enable = 0;
            $user->u               = 0;
            $user->d               = 0;
            $user->last_day_t      = 0;
            $user->sendMail(
                Setting::obtain('website_name'),
                'news/warn.tpl',
                [
                    'text' => $text
                ],
                [],
                $_ENV['email_queue']
            );
            $user->class               = 0;
            $user->node_iplimit        = $configs['signup_default_ip_limit'];
            $user->node_speedlimit     = $configs['signup_default_speed_limit'];
            $user->reset_traffic_value = NULL;
            $user->reset_traffic_date  = NULL;
            $user->product_id          = NULL;
            $user->save();
        }
        echo '用户等级过期检测结束' . PHP_EOL;
        echo 'Success ' . date('Y-m-d H:i:s', time()) . PHP_EOL;
    }

    public function CheckOrderStatus()
    {
        echo '订单状态检测开始' . PHP_EOL;
        $orders = Order::where('order_status', 1)->where('expired_at', '<', time())->get();
        foreach ($orders as $order) {
            $order->order_status = 0;
            $order->save();
        }
        echo '订单状态检测结束' . PHP_EOL;
    }
}

