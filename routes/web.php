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
    return $router->app->version();
});
//搜索
$router->post('/search','CompanyController@search');
//公司列表
$router->get('/co_list','CompanyController@co_list');
//公司详情
$router->get('/co_detil','CompanyController@co_detil');
//添加公司
$router->post('/co_add','CompanyController@co_add');
//修改公司详情
$router->post('/co_edit','CompanyController@co_edit');
