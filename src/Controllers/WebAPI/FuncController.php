<?php

namespace App\Controllers\WebAPI;

use App\Controllers\BaseController;
use App\Models\{
    Node,
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
    public function ping($request, $response, $args)
    {
        $res = [
            'ret' => 1,
            'data' => 'pong'
        ];
        return $response->withJson($res);
    }
}
