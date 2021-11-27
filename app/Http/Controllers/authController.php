<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class authController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function authorizedUser()
    {
        return $this->send_JsontoClient('current auth user',200,\Auth::user());
    }

    public function logout()
    {
        \Auth::logout();

        return $this->send_JsontoClient('user logged out',200);
    }
}
