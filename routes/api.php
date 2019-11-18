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
Route::post('/mpesa', 'Api\MpesaController@mpesaApi');
Route::post('/callback', 'Api\MpesaController@callback');
Route::post('/iPay','IpayAfricaController@IpayStk');

Route::post('/mpesaApp', 'Api\MpesaAppController@MpesaApp');
Route::post('/store','Api\ProductController@Store');
Route::get('/show','Api\ProductController@show');


