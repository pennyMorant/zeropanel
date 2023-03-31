<?php

namespace App\Controllers\WebAPI;

use App\Controllers\BaseController;
use App\Models\{
    Node,
    DetectRule
};
use Slim\Http\Response;
use Slim\Http\ServerRequest;

class FuncController extends BaseController
{
    public function getDetectLogs(ServerRequest $request, Response $response, array $args)
    {
        $rules = DetectRule::all();

        return $response->withJson([
            'ret' => 1,
            'data' => $rules,
        ]);
    }

    public function ping(ServerRequest $request, Response $response, array $args)
    {
        return $response->withJson([
            'ret' => 1,
            'data' => 'pong',
        ]);
    }
}
