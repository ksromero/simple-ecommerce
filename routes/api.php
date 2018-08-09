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
Route::resource('products','ProductsController')->except([
    'create', 'edit'
]);
Route::resource('employees','EmployeesController')->except([
    'create', 'edit'
]);

//order
Route::get('orders','OrdersController@index');
Route::get('order/{id}','OrdersController@show');

//OrderProduct Collections
Route::get('order-products','CollectionsController@index');


