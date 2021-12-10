<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
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
            $email_taken = User::where('email',$request->email)->exists();
            if(!$email_taken){
                try{
                    $data = $request->all();
                    $data['password'] = Hash::make($request->password);
                    $user = User::create($data);
                    if($user){
                        $token = \Auth::login($user);
                        if($token){
                            $tokentime = 24 * 60;
                            return $this->respondWithToken($token,$tokentime,$data);
                        }else{
                            return $this->send_JsontoClient('login attempt',400);
                        }
                    }else{
                        return $this->send_JsontoClient('Something went wrong',400);
                    }
                }catch(Exception $e){
                    return $this->send_JsontoClient($e->getMessage(),400);
                }
            }else{
                return $this->send_JsontoClient('this email is taken',400);
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
            //$time = Carbon::now('GMT+5')->format('Y-m-d H:i:s');
            $tokentime = 24 * 60;
            return $this->respondWithToken($token,$tokentime);
        }
    }
}
