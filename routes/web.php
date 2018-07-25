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


Route::get('/','PagesController@index')->name('home');
Route::get('/cart','CartController@getCart')->name('getCart');
Route::post('/cart','CartController@addToCart')->name('postCart');
Auth::routes();
Route::group(['middleware' => ['auth','admin']], function () {
    //Admin Dashboard
    Route::get('admin/dashboard','AdminController@index');
    //Admin Products
    Route::get('admin/products','AdminController@products');
});