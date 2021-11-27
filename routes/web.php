<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return response()->json(['message'=>"hellow"],200);
});

$router->post('register', 'UserController@registerUser');
$router->post('login', 'UserController@loginUser');

$router->group(['middleware' => 'auth'],function() use($router){

    $router->get('authuser','authController@authorizedUser');
    $router->get('logout','authController@logout');

});

