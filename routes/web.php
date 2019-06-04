<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::any('/demo',"UserController@demo");
Route::any('/demo1',"UserController@demo1");
route::get ('demoindex',"DemoController@demoIndex");
route::post ('domeadd',"DemoController@domeadd");
route::get ('loginindex',"exam\LoginController@LoginIndex");