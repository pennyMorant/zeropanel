<?php

namespace App\Controllers\WebAPI;

use App\Controllers\BaseController;
use App\Models\{
    Ip,
    Node,
    User,
    TrafficLog,
    NodeOnlineLog,
    DetectLog,
};
use App\Utils\Tools;
use Slim\Http\Response;
use Slim\Http\ServerRequest;

class UserController extends BaseController
{
    public function index(ServerRequest $request, Response $response, array $args)
    {
        $getData = $request->getQueryParams();
        $node_id = $getData['node_id'] ?? '';
        $node = Node::find($node_id);
        if (is_null($node)) {
            return $response->withJson([
                'ret' => 0,
            ]);
        }
        $node->node_heartbeat = time();
        $node->save();

        // 节点流量耗尽则返回 null
        if ($node->node_traffic_limit != 0 && $node->node_traffic_limit <= $node->node_traffic) {
            $users = null;

            return $response->withJson([
                'ret'  => 1,
                'data' => $users
            ]);
        }
        // 判断用户
        $users_raw = User::where(
            fn($query) => $query->where(
                fn($query1) => $query1->where('class', '>=', $node->node_class)
                    ->when($node->node_group !== 0, fn($query1) => $query1->where('node_group', '=', $node->node_group))
            )->orWhere('is_admin', 1)
            )->where('enable', 1)
        ->get();

        // 下发的数据 
        $common_key_list = ['node_speedlimit', 'id', 'node_iplimit', 'alive_ip'];
        if (in_array($node->node_type, [2, 3, 4])) {
            $extra_key_list = ['uuid'];
        } else {
            $extra_key_list = ['passwd'];
        }      
        $key_list = array_merge($common_key_list, $extra_key_list);       
        
        //判断在线IP
        $alive_ip = (new \App\Models\Ip)->getUserAliveIpCount();
        
        $users = collect($users_raw)
                ->filter(
                    static function ($user_raw) use ($alive_ip, $key_list): bool {
                        if (isset($alive_ip[strval($user_raw->id)]) && $user_raw->node_iplimit !== 0) {
                            $user_raw->alive_ip = $alive_ip[strval($user_raw->id)];
                        }
                        if ($user_raw->transfer_enable <= $user_raw->u + $user_raw->d) {
                            return false;
                        }
                        $user_raw = Tools::keyFilter($user_raw, $key_list);
                        return true;
                    }
                )
                ->values()
                ->all();


        return $response->withJson([
            'ret'  => 1,
            'data' => $users
        ]);
    }

    public function addTraffic(ServerRequest $request, Response $response, array $args)
    {

        $data = $request->getParsedBodyParam('data');
        $this_time_total_bandwidth = 0;
        $node_id = $request->getQueryParams()['node_id'] ?? null;
        if (is_null($node_id)) {
            $node = Node::where('node_ip', $_SERVER['REMOTE_ADDR'])->first();
            $node_id = $node->id;
        }
        $node = Node::find($node_id);

        if (is_null($node)) {
            $res = [
                'ret' => 0
            ];
            return $response->withJson($res);
        }

        if (count($data) > 0) {
            foreach ($data as $log) {
                $u = $log['u'];
                $d = $log['d'];
                $user_id = $log['user_id'];

                $user = User::find($user_id);

                if (is_null($user)) {
                    continue;
                }

                $user->t = time();
                $user->u += $u * $node->traffic_rate;
                $user->d += $d * $node->traffic_rate;
                $this_time_total_bandwidth += $u + $d;
                if (!$user->save()) {
                    $res = [
                        'ret' => 0,
                        'data' => 'update failed',
                    ];
                    return $response->withJson($res);
                }

                // log
                $traffic           = new TrafficLog();
                $traffic->user_id  = $user_id;
                $traffic->u        = $u;
                $traffic->d        = $d;
                $traffic->node_id  = $node_id;
                $traffic->rate     = $node->traffic_rate;
                $traffic->traffic  = Tools::flowAutoShow(($u + $d) * $node->traffic_rate);
                $traffic->created_at = time();
                $traffic->save();
            }
        }

        $node->node_traffic += $this_time_total_bandwidth;
        $node->save();

        $online_log              = new NodeOnlineLog();
        $online_log->node_id     = $node_id;
        $online_log->online_user = count($data);
        $online_log->created_at  = time();
        $online_log->save();

        return $response->withJson([
            'ret' => 1,
            'data' => 'ok',
        ]);   
    }

    public function addAliveIp(ServerRequest $request, Response $response, array $args)
    {
        $data = $request->getParsedBodyParam('data');
        $node_id = $request->getQueryParams()['node_id'] ?? null;
        if (is_null($node_id)) {
            $node = Node::where('node_ip', $_SERVER['REMOTE_ADDR'])->first();
            $node_id = $node->id;
        }
        $node = Node::find($node_id);

        if (is_null($node)) {
            return $response->withJson([
                'ret' => 0
            ]);
        }
        if (count($data) > 0) {
            foreach ($data as $log) {
                $ip = $log['ip'];
                $userid = $log['user_id'];

                // log
                $ip_log = new Ip();
                $ip_log->userid = $userid;
                $ip_log->nodeid = $node_id;
                $ip_log->ip = $ip;
                $ip_log->created_at = time();
                $ip_log->save();
            }
        }

        return $response->withJson([
            'ret' => 1,
            'data' => 'ok',
        ]);
    }

    public function addDetectLog(ServerRequest $request, Response $response, array $args)
    {
        $data = $request->getParsedBodyParam('data');
        $node_id = $request->getQueryParams()['node_id'] ?? null;
        if (is_null($node_id)) {
            $node = Node::where('node_ip', $_SERVER['REMOTE_ADDR'])->first();
            $node_id = $node->id;
        }
        $node = Node::find($node_id);

        if (is_null($node)) {
            return $response->withJson([
                'ret' => 0
            ]);
        }

        if (count($data) > 0) {
            foreach ($data as $log) {
                $list_id = $log['list_id'];
                $user_id = $log['user_id'];

                // log
                $detect_log             = new DetectLog();
                $detect_log->user_id    = $user_id;
                $detect_log->list_id    = $list_id;
                $detect_log->node_id    = $node_id;
                $detect_log->created_at = time();
                $detect_log->save();
            }
        }

        return $response->withJson([
            'ret' => 1,
            'data' => 'ok',
        ]);
    }
}
