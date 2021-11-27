<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function registerUser(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required'
        ]);
        if($validation->fails()){
            return response()->json($validation->errors(),400);
        }else{
            try{
                $data = $request->all();
                $data['password'] = Hash::make($request->password);
                $user = User::create($data);
                return $this->send_JsontoClient('Registration successful',200,$user);
            }catch(Exception $e){
                return $this->send_JsontoClient($e->getMessage(),400);
            }
        }
    }

    public function loginUser(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'email' => 'required',
            'password' => 'required',
        ]);
        if($validation->fails()){
            return response()->json($validation->errors(),400);
        }else{
            if(! $token = \Auth::attempt(['email' => $request->email, 'password' => $request->password])){
                //return response()->json(['message' => 'Unauthorized'], 401);
                return $this->send_JsontoClient('Unauthorized',401);
            }
            return $this->respondWithToken($token);
        }
    }
}
