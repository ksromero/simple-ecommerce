<?php
Route::get('/','PagesController@index')->name('home');

//CART
Route::get('/cart','CartController@index')->name('getCart');
Route::post('/cart','CartController@addToCart')->name('postCart');
Route::get('/cart/{id}','CartController@reduceByOne')->name('removeByOne');
Route::get('/cart/all/{id}','CartController@removeProduct')->name('removeAll');
Route::put('/cart/update/{id}','CartController@updateProduct')->name('updateProduct');

//CHECKOUT
Route::group(['middleware' => ['auth']],function(){
    Route::get('/paypal/ec-checkout', 'CheckoutController@getExpressCheckout')->name('checkout');
    Route::get('/paypal/success','CheckoutController@paypalSuccess')->name('checkoutSuccess');
    Route::get('/checkout','CheckoutController@index')->name('checkoutView');
});

Auth::routes();
Route::group(['middleware' => ['auth','admin']], function () {
    //Admin Dashboard
    Route::get('admin/dashboard','AdminController@index');
    //Admin Products
    Route::get('admin/products','AdminController@products');
    //Admin Employees
    Route::get('admin/employees','AdminController@employees');
});