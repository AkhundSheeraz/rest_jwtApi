<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function send_JsontoClient($msg,$statusCode,$data = null)
    {
        return response()->json(['msg' => $msg,'data' => $data],$statusCode);
    }

    protected function respondWithToken($token, $time, $data = null)
    {

        if($data){
            return response()->json([
                'data' => $data,
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => Auth::factory()->getTTL() * $time
            ], 200);
        }

        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * $time
        ], 200);
    }
}
