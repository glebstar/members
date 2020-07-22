<?php

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
    return $router->app->version();
});

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('register', 'AuthController@apiregister');
    $router->post('login', 'AuthController@apilogin');
});

$router->group(['middleware' => 'auth', 'prefix' => 'api'], function () use ($router) {
    $router->post('member', 'MemberController@create');
    $router->get('member/{id}', 'MemberController@show');
    $router->put('member/{id}', 'MemberController@update');
    $router->get('members/{event_id}', 'MemberController@getMembers');
    $router->delete('member/{id}', 'MemberController@delete');
});
