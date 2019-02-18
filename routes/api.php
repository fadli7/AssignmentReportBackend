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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('auth/register', 'AuthController@register');
Route::post('auth/login', 'AuthController@login');
Route::get('users', 'UserController@users')->middleware('auth:api');
Route::get('user', 'UserController@user')->middleware('auth:api');
Route::get('profile', 'UserController@profile')->middleware('auth:api');
Route::post('auth/logout', 'AuthController@logout')->middleware('auth:api');
Route::post('assignment/create', 'AssignmentController@create')->middleware('auth:api');
Route::get('assignment/list', 'AssignmentController@list')->middleware('auth:api');
