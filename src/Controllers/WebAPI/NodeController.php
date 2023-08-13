<?php


namespace App\Controllers\WebAPI;

use App\Controllers\BaseController;
use App\Models\{
    Node,
    NodeInfoLog
};
use App\Services\Config;
use Slim\Http\Response;
use Slim\Http\ServerRequest;

class NodeController extends BaseController
{
    public function info(ServerRequest $request, Response $response, array $args)
    {
        $node_id = $args['id'];
        if ($node_id == '0') {
            $node = Node::where('node_ip', $_SERVER['REMOTE_ADDR'])->first();
            $node_id = $node->id;
        }
        $load            = $request->getParsedBodyParam('load');
        $uptime          = $request->getParsedBodyParam('uptime');
        $log             = new NodeInfoLog();
        $log->node_id    = $node_id;
        $log->load       = $load;
        $log->uptime     = $uptime;
        $log->created_at = time();
        if (!$log->save()) {
            return $response->withJson([
                'ret' => 0,
                'data' => 'update failed',
            ]);
        }
        
        return $response->withJson([
            'ret' => 1,
            'data' => 'ok',
        ]);
    }
    
    public function getInfo(ServerRequest $request, Response $response, array $args)
    {
        $node_id = $args['id'];
        if ($node_id == '0') {
            $node = Node::where('node_ip', $_SERVER['REMOTE_ADDR'])->first();
            $node_id = $node->id;
        }
        $node = Node::find($node_id);
        if (is_null($node)) {
            return $response->withJson([
                'ret' => 0
            ]);           
        }
        
        $node_server = $node->server;

        $data = [
            'node_group'      => $node->node_group,
            'node_class'      => $node->node_class,
            'node_speedlimit' => $node->node_speedlimit,
            'traffic_rate'    => $node->traffic_rate,
            'node_type'       => $node->nodeType(),
            'server'          => $node_server,
            'custom_config'   => json_decode($node->custom_config, true, JSON_UNESCAPED_SLASHES),
        ];

        return $response->withJson([
            'ret' => 1,
            'data' => $data
        ]);
        
    }

    public function getAllInfo(ServerRequest $request, Response $response, array $args)
    {
        $nodes = Node::whereNotNull('node_ip')->whereIn('node_type', [1, 2, 3, 4, 5])->get();
        return $response->withJson([
            'ret' => 1,
            'data' => $nodes
        ]);
    }

    public function getConfig(ServerRequest $request, Response $response, array $args)
    {
        $data = $request->getParsedBody();
        switch ($data['type']) {
            case ('database'):
                $db_config = Config::getDbConfig();
                $db_config['host'] = $this->getServerIP();
                return $response->withJson([
                    'ret' => 1,
                    'data' => $db_config,
                ]);
                break;
        }
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
