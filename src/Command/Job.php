<?php

namespace App\Command;

use App\Models\EmailQueue;
use App\Models\Ip;
use App\Models\Node;
use App\Models\User;
use App\Models\Product;
use App\Models\Token;
use App\Models\Bought;
use App\Models\Ticket;
use App\Models\SigninIp;
use App\Models\TrafficLog;
use App\Models\EmailVerify;
use App\Models\NodeInfoLog;
use App\Models\NodeOnlineLog;
use App\Models\PasswordReset;
use App\Models\TelegramTasks;
use App\Models\TelegramSession;
use App\Models\UserSubscribeLog;
use App\Models\Setting;
use App\Models\Withdraw;
use App\Models\Order;
use App\Models\Payback;
use App\Models\DetectBanLog;
use App\Models\DetectLog;
use App\Services\Mail;
use App\Services\ZeroConfig;
use App\Utils\Telegram\TelegramTools;
use App\Utils\Tools;
use App\Utils\Telegram;
use App\Utils\DatatablesHelper;
use Swap\Builder;
use ArrayObject;
use Exception;

class Job extends Command
{
    public $description = ''
    . '├─=: php xcat Job [选项]' . PHP_EOL
    . '│ ├─ DailyJob                - 每日任务' . PHP_EOL
    . '│ ├─ CheckJob                - 检查任务，每分钟' . PHP_EOL
    . '│ ├─ CheckUserClassExpire    - 检查用户会员等级过期任务，每分钟' . PHP_EOL
    . '│ ├─ CheckOrderStatus        - 检查订单状态任务，每分钟' . PHP_EOL
    . '│ ├─ CheckUserExpire         - 检查账号过期任务，每小钟' . PHP_EOL
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
        UserSubscribeLog::where('request_time', '<', date('Y-m-d H:i:s', time() - 86400 * (int)Setting::obtain('subscribe_log_keep_time')))->delete();
        Token::where('expire_time', '<', time())->delete();
        DetectLog::where('datetime', '<', time() - 86400 * 3)->delete();
        EmailVerify::where('expire_in', '<', time() - 86400 * 3)->delete();
        PasswordReset::where('expire_time', '<', time() - 86400 * 3)->delete();
        Ip::where('datetime', '<', time() - 300)->delete();
        TelegramSession::where('datetime', '<', time() - 900)->delete();
        SigninIp::where('datetime', '<', time() - 86400 * 7)->delete();
        IP::where('datetime', '<', time() - 86400 * 7)->delete();
        echo '清理数据库各表结束;' . PHP_EOL;

        // ------- 重置自增 ID
        $db = new DatatablesHelper();

        $tools = new Tools();
        $tools->reset_auto_increment($db, 'user_traffic_log');
        $tools->reset_auto_increment($db, 'node_online_log');
        $tools->reset_auto_increment($db, 'node_info');

        //auto reset
        echo '重置用户流量开始' . PHP_EOL;
        
        $users = User::where('class','>=', '1')->get();
        foreach ($users as $user) {
            if ($user == null) {
                continue;
            }
            if (!is_null($user->reset_traffic_date)) {               
                if (date('d') === $user->reset_traffic_date) {
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
         // ------- 发送每日系统运行报告
        
            echo '每日数据库清理成功报告发送开始' . PHP_EOL;
            $messagetext = Setting::obtain('diy_system_clean_database_report_telegram_notify_content');
            Telegram::PushToAdmin($messagetext);
            echo '每日数据库清理成功报告发送结束' . PHP_EOL;
        

        $this->ZeroTask();


        $configs = Setting::getClass('currency');
        if ($configs['enable_currency'] == true) {
            $swap = (new Builder())
                ->add('abstract_api', ['api_key' => $configs['currency_exchange_rate_api_key']])
            ->build();
            $rate = $swap->latest($configs['currency_unit'] . '/CNY');
            $result = $rate->getValue();
            $setting = Setting::where('item', '=', 'currency_exchange_rate')->first();
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
        if ($_ENV['enable_detect_offline'] == true) {
            echo '节点掉线检测开始' . PHP_EOL;
            $adminUser = User::where('is_admin', '=', '1')->get();
            $nodes = Node::all();
            foreach ($nodes as $node) {
                if ($node->isNodeOnline() === false && $node->online == true) {
                    
					foreach ($adminUser as $user) {
					    if ($_ENV['sendemail'] === true) {
							echo 'Send offline mail to user: ' . $user->id . PHP_EOL;
							$user->sendMail(
								Setting::obtain('website_name') . '-系统警告',
								'news/warn.tpl',
								[
									'text' => '管理员您好，系统发现节点 ' . $node->name . ' 掉线了，请您及时处理。'
								],
								[],
								$_ENV['email_queue']
							);
					    }
						$notice_text = str_replace(
							'%node_name%',
							$node->name,
							Setting::obtain('diy_system_node_offline_report_telegram_notify_content')
						);
					}
                    
                    $messagetext = $notice_text;
                    Telegram::PushToAdmin($messagetext);
                    

                    $node->online = false;
                    $node->save();
                } elseif ($node->isNodeOnline() === true && $node->online == false) {
                    foreach ($adminUser as $user) {
                        if ($_ENV['sendemail'] === true) {
                            echo 'Send offline mail to user: ' . $user->id . PHP_EOL;
                            $user->sendMail(
                                Setting::obtain('website_name') . '-系统提示',
                                'news/warn.tpl',
                                [
                                    'text' => '管理员您好，系统发现节点 ' . $node->name . ' 恢复上线了。'
                                ],
                                [],
                                $_ENV['email_queue']
                            );
                        }
                        $notice_text = str_replace(
                            '%node_name%',
                            $node->name,
                            Setting::obtain('diy_system_node_online_report_telegram_notify_content')
                        );
                    }

                    $messagetext = $notice_text;
                    Telegram::PushToAdmin($messagetext);          

                    $node->online = true;
                    $node->save();
                }
            }
            echo '节点掉线检测结束' . PHP_EOL;
        }

        if (Setting::obtain('enable_telegram_bot') == true) {
            $this->Telegram();
        }

        //更新节点 IP，每分钟
        echo '更新节点IP开始' . PHP_EOL;
        $nodes = Node::get();
        foreach ($nodes as $node) {
            /** @var Node $node */
            $server = $node->server;
            if (!Tools::isIPv4($server) && $node->changeNodeIp($server)) {
                $node->save();
            }
        }
        echo '更新节点IP结束' . PHP_EOL;

        echo 'Success ' . date('Y-m-d H:i:s', time()) . PHP_EOL;
    }

    /**
     * Telegram 任务
     */
    public function Telegram(): void
    {
        # 删除 tg 消息
        echo '删除telegram无用消息开始' . PHP_EOL;
        $TelegramTasks = TelegramTasks::where('type', 1)->where('executetime', '<', time())->get();
        foreach ($TelegramTasks as $Task) {
            TelegramTools::SendPost(
                'deleteMessage',
                ['chat_id' => $Task->chatid, 'message_id' => $Task->messageid]
            );
            TelegramTasks::where('chatid', $Task->chatid)->where('type', '<>', 1)->where(
                'messageid',
                $Task->messageid
            )->delete();
            $Task->delete();
        }
        echo '删除telegram无用消息结束' . PHP_EOL;
    }

    public function ZeroTask()
    {
        echo '关闭工单任务开始' . PHP_EOL;
        if (ZeroConfig::get('auto_close_ticket') === true) {
            $tickets = Ticket::where('status', '=', 1)->where('rootid', '=', 0)->get();

            foreach ($tickets as $ticket) {
                $tk = Ticket::where('rootid', '=', $ticket->id)->orderBy('datetime', 'desc')->first();
                $tk_userid = $tk ? $tk->userid : $ticket->userid;

                $user = User::find($tk_userid);
                if ($user === null) {
                    continue;
                }
                if ($user->is_admin != 1) {
                    continue;
                }

                $time = ZeroConfig::get('close_ticket_time') * 86400;
                if (time() - $tk->datetime < $time) {
                    continue;
                }

                $ticket->status = 0;
                $ticket->save();
                echo('关闭工单ID:' . $ticket->id . PHP_EOL);
            }
        }

        if (ZeroConfig::get('del_user_ticket') === true) {
            $del_tickets = Ticket::all();
            foreach ($del_tickets as $del_ticket) {
                $del_user = User::find($del_ticket->userid);
                if ($del_user === null) {
                    $del_ticket->delete();
                }
            }
        }
        echo '关闭工单任务结束' . PHP_EOL;
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
           
                echo '用户订阅余量检测开始' . PHP_EOL;
                $user_traffic_left = $user->transfer_enable - $user->u - $user->d;
                $under_limit = false;

                if ($user->transfer_enable != 0 && $user->class != 0) {
                    if (
                        Tools::flowToMB($user_traffic_left) < 1000
                    ) {
                        $under_limit = true;
                        $unit_text = 'MB';
                    }
                }

                if ($under_limit == true && $user->traffic_notified == false) {
                    $result = $user->sendMail(
                        Setting::obtain('website_name') . '-您的剩余流量过低',
                        'news/warn.tpl',
                        [
                            'text' => '您好，系统发现您剩余流量已经低于 ' . 1000 . $unit_text . ' 。'
                        ],
                        [],
                        $_ENV['email_queue']
                    );
                    if ($result) {
                        $user->traffic_notified = true;
                        $user->save();
                    }
                } elseif ($under_limit == false && $user->traffic_notified == true) {
                    $user->traffic_notified = false;
                    $user->save();
                }
                echo '用户订阅余量检测结束' . PHP_EOL;
            

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
        $users = User::query()
            ->where('class_expire', '<', date('Y-m-d H:i:s', time()))
            ->where('class', '!=', 0)
            //->where('is_admin', '!=', 1)
            ->get();

        foreach ($users as $user) {
            $text = '您好，系统发现您的账号等级已经过期了。';
            $reset_traffic = 0;
            if ($reset_traffic >= 0) {
                $user->transfer_enable = Tools::toGB($reset_traffic);
                $user->u = 0;
                $user->d = 0;
                $user->last_day_t = 0;
                $text .= '流量已经被重置为' . $reset_traffic . 'GB';
            }
            $user->sendMail(
                Setting::obtain('website_name') . '-您的账户等级已经过期了',
                'news/warn.tpl',
                [
                    'text' => $text
                ],
                [],
                $_ENV['email_queue']
            );
            $user->class = 0;
            $user->node_iplimit = $configs['signup_default_ip_limit'];
            $user->node_speedlimit = $configs['signup_default_speed_limit'];
            $user->reset_traffic_value = NULL;
            $user->reset_traffic_date = NULL;
            $user->product_id = NULL;
            $user->save();
        }
        echo '用户等级过期检测结束' . PHP_EOL;
        echo 'Success ' . date('Y-m-d H:i:s', time()) . PHP_EOL;
    }

    public function CheckOrderStatus()
    {
        echo '订单状态检测开始' . PHP_EOL;
        $orders = Order::where('order_status', 1)->where('expired_time', '<', time())->get();
        foreach ($orders as $order) {
            $order->order_status = 0;
            $order->save();
        }
        echo '订单状态检测结束' . PHP_EOL;
    }
}

