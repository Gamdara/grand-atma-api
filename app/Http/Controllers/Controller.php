<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function baseResponse($stt, $msg, $data, $code){
        return response()->json([
            'status' => $stt,
            'message' => $msg,
            'data' => $data
        ], $code);
    }
}
