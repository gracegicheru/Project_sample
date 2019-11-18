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

Route::get('/form', 'Mpesa@form');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/file', 'FileUpload@getForm');
Route::post('/upload', 'FileUpload@StoreFile');
Route::get('/images','ImagesController@Images');
Route::post('/store','ProductController1@Store');
Route::get('/show', 'ProductController@show');