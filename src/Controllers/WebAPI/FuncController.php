<?php

namespace App\Controllers\WebAPI;

use App\Controllers\BaseController;
use App\Models\{
    Auto,
    Node,
    BlockIp,
    UnblockIp,
    DetectRule
};
use Slim\Http\{
    Request,
    Response
};

class FuncController extends BaseController
{
    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function ping($request, $response, $args)
    {
        $res = [
            'ret' => 1,
            'data' => 'pong'
        ];
        return $response->withJson($res);
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function getDetectLogs($request, $response, $args)
    {
        $rules = DetectRule::all();

        $res = [
            'ret' => 1,
            'data' => $rules
        ];
        return $response->withJson($res);
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function get_dis_node_info($nodeid)
    {
        $node = Node::where('id', $nodeid)->first();
        if ($node == null) {
            return null;
        }

        return $node;
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function getBlockIp($request, $response, $args)
    {
        $block_ips = BlockIp::Where('datetime', '>', time() - 60)->get();

        $res = [
            'ret' => 1,
            'data' => $block_ips
        ];
        return $response->withJson($res);
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function getUnblockIp($request, $response, $args)
    {
        $unblock_ips = UnblockIp::Where('datetime', '>', time() - 60)->get();

        $res = [
            'ret' => 1,
            'data' => $unblock_ips
        ];
        return $response->withJson($res);
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function addBlockIp($request, $response, $args)
    {
        $params = $request->getQueryParams();

        $data = $request->getParam('data');
        $node_id = $params['node_id'];
        if ($node_id == '0') {
            $node = Node::where('node_ip', $_SERVER['REMOTE_ADDR'])->first();
            $node_id = $node->id;
        }
        $node = Node::find($node_id);
        if ($node == null) {
            $res = [
                'ret' => 0
            ];
            return $response->withJson($res);
        }

        if (count($data) > 0) {
            foreach ($data as $log) {
                $ip = $log['ip'];

                $exist_ip = BlockIp::where('ip', $ip)->first();
                if ($exist_ip != null) {
                    continue;
                }

                // log
                $ip_block = new BlockIp();
                $ip_block->ip = $ip;
                $ip_block->nodeid = $node_id;
                $ip_block->datetime = time();
                $ip_block->save();
            }
        }

        $res = [
            'ret' => 1,
            'data' => 'ok',
        ];
        return $response->withJson($res);
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function getAutoexec($request, $response, $args)
    {
        $params = $request->getQueryParams();

        $node_id = $params['node_id'];
        if ($node_id == '0') {
            $node = Node::where('node_ip', $_SERVER['REMOTE_ADDR'])->first();
            $node_id = $node->id;
        }
        $node = Node::find($node_id);
        if ($node == null) {
            $res = [
                'ret' => 0
            ];
            return $response->withJson($res);
        }

        $autos_raw = Auto::where('datetime', '>', time() - 60)->where('type', '1')->get();

        $autos = array();

        foreach ($autos_raw as $auto_raw) {
            $has_exec = Auto::where('sign', $node_id . '-' . $auto_raw->id)->where('type', '2')->first();
            if ($has_exec == null) {
                $autos[] = $auto_raw;
            }
        }

        $res = [
            'ret' => 1,
            'data' => $autos,
        ];
        return $response->withJson($res);
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function addAutoexec($request, $response, $args)
    {
        $params = $request->getQueryParams();

        $data = $request->getParam('data');
        $node_id = $params['node_id'];
        if ($node_id == '0') {
            $node = Node::where('node_ip', $_SERVER['REMOTE_ADDR'])->first();
            $node_id = $node->id;
        }
        $node = Node::find($node_id);
        if ($node == null) {
            $res = [
                'ret' => 0
            ];
            return $response->withJson($res);
        }

        if (count($data) > 0) {
            foreach ($data as $log) {
                // log
                $auto_log = new Auto();
                $auto_log->value = $log['value'];
                $auto_log->sign = $log['sign'];
                $auto_log->type = $log['type'];
                $auto_log->datetime = time();
                $auto_log->save();
            }
        }

        $res = [
            'ret' => 1,
            'data' => 'ok',
        ];
        return $response->withJson($res);
    }
}
