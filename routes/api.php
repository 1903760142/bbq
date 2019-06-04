<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('/tasks', 'TaskController', ['except' => ['create', 'edit']]);
Route::resource('/user', 'UserController');
Route::get('/aricle', 'ArticleController@index')->middleware("jwt");

//Route::post('login', 'PassportController@login');
//Route::post('register', 'PassportController@register');

Route::group(['middleware' => 'auth:api'], function(){
	Route::post('get-details', 'PassportController@getDetails');
});
//用户登录
Route::post('login', 'exam\LoginController@LoginAdd');
//商品
Route::post('goodsadd', 'exam\GoodController@GoodsAdd')->middleware("jwt");
Route::post('goodsquery', 'exam\GoodController@GoodsQuery')->middleware("jwt");
Route::any('goodsdel', 'exam\GoodController@GoodsDel')->middleware("jwt");
Route::any('goodsedit', 'exam\GoodController@GoodsEdit')->middleware("jwt");