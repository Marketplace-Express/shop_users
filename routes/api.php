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

/** @var Router $router */

use Laravel\Lumen\Routing\Router;

$router->group(['prefix' => 'api', 'middleware' => 'auth'], function () use ($router) {
    // UserController
    $router->delete('/user/delete/{userId}', 'UserController@delete');
    $router->post('/user/restore/{userId}', 'UserController@restore');
    $router->post('/user/ban/{userId}', 'UserController@ban');
    $router->post('/user/unBan/{userId}', 'UserController@unBan');
    $router->get('/users/banned', 'UserController@getBanned');

    // RoleController
    $router->post('/role/create', 'RoleController@create');
    $router->delete('/role/{roleId}', 'RoleController@delete');
});

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('/user/register', 'UserController@register');
    $router->post('/user/login', 'UserController@login');
});

