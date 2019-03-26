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


Auth::routes();
Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Route::get('/get-user-info', function() {
    return auth()->user()->id;
});

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/', function() {
    return view('welcome');
});

Route::get('/invoice/{orderid}', 'PDFController@invoice')->middleware('auth:web');

Route::get('{path}', 'HomeController@index')->where( 'path', '([A-z\d-\/_.]+)?');


