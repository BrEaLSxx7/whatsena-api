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

Route::post('auth', 'AuthenticationController@auth');
Route::get('excel', function () {
    return response()->download('./../files/Formato_Excel.xlsx');
});
Route::apiResource('groups', 'GroupController');
Route::apiResource('messages', 'MessageController');
Route::post('img', 'UserController@img');
Route::put('forgout', 'AuthenticationController@forgout');
