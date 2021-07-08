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

$router->group(['prefix' => 'user'], function () use ($router) {
    $router->get('/', 'UserController@getUser');
    $router->get('/{id}', 'UserController@getUserId');
    $router->post('/', 'UserController@create');
    $router->put('/{id}', 'UserController@update');
    $router->delete('/{id}', 'UserController@destroy');
});

$router->group(['prefix' => 'wallet'], function () use ($router) {
    $router->get('/{idUser}', 'WalletsController@getWallet');
    $router->post('/', 'WalletsController@add');
});

$router->group(['prefix' => 'transactions'], function () use ($router) {
   // $router->get('/{idUser}', 'TransactionsController@transactions');
    $router->post('/', 'TransactionController@create');
});


 


