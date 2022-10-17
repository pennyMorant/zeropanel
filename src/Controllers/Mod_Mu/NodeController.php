<?php


namespace App\Controllers\Mod_Mu;

use App\Controllers\BaseController;
use App\Models\{
    Node,
    NodeInfoLog
};
use App\Services\Config;
use Slim\Http\{
    Request,
    Response
};
use App\Utils\Tools;

class NodeController extends BaseController
{
    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function info($request, $response, $args)
    {
        $node_id = $args['id'];
        if ($node_id == '0') {
            $node = Node::where('node_ip', $_SERVER['REMOTE_ADDR'])->first();
            $node_id = $node->id;
        }
        $load = $request->getParam('load');
        $uptime = $request->getParam('uptime');
        $log = new NodeInfoLog();
        $log->node_id = $node_id;
        $log->load = $load;
        $log->uptime = $uptime;
        $log->log_time = time();
        if (!$log->save()) {
            $res = [
                'ret' => 0,
                'data' => 'update failed',
            ];
            return $response->withJson($res);
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
    public function getInfo($request, $response, $args)
    {
        $node_id = $args['id'];
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
        
        $node_server = $node->server;

        $data = [
            'node_group' => $node->node_group,
            'node_class' => $node->node_class,
            'node_speedlimit' => $node->node_speedlimit,
            'traffic_rate' => $node->traffic_rate,
            'sort' => $node->sort,
            'server' => $node_server,
            'custom_config' => json_decode($node->custom_config, true, JSON_UNESCAPED_SLASHES),
            'type' => 'SSPanel-UIM',
            'version' => '2021.11'
        ];

        $res = [
            'ret' => 1,
            'data' => $data
        ];
        return $response->withJson($res);
    }

    /**
     * @param Request   $request
     * @param Response  $response
     * @param array     $args
     */
    public function getAllInfo($request, $response, $args)
    {
        $nodes = Node::where('node_ip', '<>', null)->where(
            static function ($query) {
                $query->where('sort', '=', 0)
                    ->orWhere('sort', '=', 11)
                    ->orWhere('sort', '=', 13)
                    ->orWhere('sort', '=', 14)
                    ->orWhere('sort', '=', 15);
            }
        )->get();
        $res = [
            'ret' => 1,
            'data' => $nodes
        ];
        return $response->withJson($res);
    }

    public function getConfig($request, $response, $args)
    {
        $data = $request->getParsedBody();
        switch ($data['type']) {
            case ('database'):
                $db_config = Config::getDbConfig();
                $db_config['host'] = $this->getServerIP();
                $res = [
                    'ret' => 1,
                    'data' => $db_config,
                ];
                break;
            case ('webapi'):
                $webapiConfig = [];
                #todo
        }
        return $response->withJson($res);
    }

    private function getServerIP()
    {
        if (isset($_SERVER)) {
            if ($_SERVER['SERVER_ADDR']) {
                $serverIP = $_SERVER['SERVER_ADDR'];
            } else {
                $serverIP = $_SERVER['LOCAL_ADDR'];
            }
        } else {
            $serverIP = getenv('SERVER_ADDR');
        }
        return $serverIP;
    }
}
