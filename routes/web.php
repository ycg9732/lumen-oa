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
    return '$router->app->version()';
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
//组织架构搜索
$router->post('/de_search','DepartmentController@de_search');
//组织架构列表
$router->get('/de_list','DepartmentController@de_list');
//组织架构详情
$router->get('/de_detil','DepartmentController@de_detil');
//添加组织架构
$router->post('/de_add','DepartmentController@de_add');
//修改组织架构详情
$router->post('/de_edit','DepartmentController@de_edit');
//员工搜索
$router->post('/ee_search','EmployeeController@ee_search');
//员工列表
$router->get('/ee_list','EmployeeController@ee_list');
//员工详情
$router->get('/ee_detil','EmployeeController@ee_detil');
//添加员工
$router->post('/ee_add','EmployeeController@ee_add');
//修改员工详情
$router->post('/ee_edit','EmployeeController@ee_edit');
//tree
$router->get('/co_tree','CompanyController@tree');
//单选框接口
$router->get('/co_select','CompanyController@co_select');
//合同搜索
$router->get('/con_search','ContractController@con_search');
//合同添加
$router->post('/con_add','ContractController@con_add');
//合同修改
$router->get('/con_edit','ContractController@con_edit');
//合同详情
$router->get('/con_detil','ContractController@con_detil');
//合同列表
$router->get('/con_list','ContractController@con_list');
//断点上传
$router->post('/resumable','ResumableController@process');
//员工删除
$router->get('/ee_delete','EmployeeController@ee_delete');
//公司删除
$router->get('/co_delete','CompanyController@co_delete');
//部门删除
$router->get('/de_delete','DepartmentController@de_delete');
//单个部门下的员工
$router->get('/de_per_list','DepartmentController@de_per_list');
//部门添加员工
$router->get('/de_add_ee','DepartmentController@de_add_ee');
//公司所有部门
$router->get('/de_per_co','CompanyController@de_per_co');
//添加公告
$router->get('/add_bbs','BbsController@add_bbs');
//查看公告
$router->get('/see_bbs','BbsController@see_bbs');


