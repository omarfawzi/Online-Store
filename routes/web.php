<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

$api = app('Dingo\Api\Routing\Router');
//$token = 'ad1f44bb-1ff7-4f59-be6a-d4ffd72d45a3';

Route::get('/','WebController@index')->name('index');

Route::group(['prefix'=>'/'],function(){
    Route::group(['prefix'=>'auth'],function (){
        Route::get('{provider}', 'WebLoginController@redirectToProvider')->name('socialAuth');
        Route::get('{provider}/callback', 'WebLoginController@handleProviderCallback');
    });
    Route::post('customer_register', 'WebRegisterController@register')->name('customer_register');
    Route::post('customer_login', 'WebLoginController@login')->name('customer_login');
    Route::post('customer_logout', 'WebLoginController@logout')->name('customer_logout');
    Route::group(['prefix'=>'products'],function (){
        Route::get('{gender}/{categoryName}','WebController@categoryProducts')->name('categoryProducts');
        Route::get('{supplierName}','WebController@supplierProducts')->name('supplierProducts');
        Route::get('/','WebController@filterProducts')->name('filterProducts');
    });
    Route::get('product/{productName}/{colorID}','WebController@product')->name('singleProduct');
    Route::group(['middleware'=>'App\Http\Middleware\WebMiddleware'],function(){
        Route::post('addToCart','WebController@addToCart')->name('addToCart');
        Route::get('myCart','WebController@myCart')->name('myCart');
        Route::get('removeCartProduct','WebController@removeCartProduct')->name('removeCartProduct');
    });
});
// AdminPanel's routes
Route::group(['prefix'=>'admin'],function (){
    Auth::routes();
    Route::group(['middleware'=>'auth'],function (){

        Route::get('/', 'AdminController@index')->name('home');

        Route::group(['middleware'=>'App\Http\Middleware\CompanyMiddleware'],function (){
            Route::group(['as' => 'company.sidebar.'], function () {
                Route::get('/addProduct', ['uses' => 'AdminController@addProductView', 'as' => 'addProductView']);
                Route::get('/profile',['uses'=>'AdminController@profile','as'=>'profile']);
            });
            Route::post('/addProduct', ['uses' => 'AdminController@addProduct', 'as' => 'addProduct']);
            Route::get('/newColor/{productID}',['uses' => 'AdminController@newColor', 'as' => 'newColor']);
            Route::post('/addProductColor',['uses' => 'AdminController@addProductColor', 'as' => 'addProductColor']);
            Route::post('/updateProfile',['uses'=>'AdminController@updateProfile','as'=>'updateProfile']);
        });

        Route::group(['middleware'=>'admin'],function () {
            Route::group(['as' => 'admin.sidebar.'], function () {
                Route::get('/categories', ['uses' => 'AdminController@categoriesView', 'as' => 'categoriesView']);
            });
            Route::post('/addCategory',['uses' => 'AdminController@addCategory', 'as' => 'addCategory']);
            Route::get('/approve/{colorID}',['uses'=>'AdminController@approve','as'=>'approve']);
            Route::get('/reject/{colorID}',['uses'=>'AdminController@reject','as'=>'reject']);
            Route::post('/updateCategory',['uses' => 'AdminController@updateCategory', 'as' => 'updateCategory']);
            Route::get('/deleteCategory/{categoryID}',['uses' => 'AdminController@deleteCategory', 'as' => 'deleteCategory']);
        });
        Route::get('/ApprovedProducts',['uses' => 'AdminController@ApprovedProducts', 'as' => 'ApprovedProducts']);
        Route::get('/WaitingProducts',['uses' => 'AdminController@WaitingProducts', 'as' => 'WaitingProducts']);
        Route::get('/RejectedProducts',['uses' => 'AdminController@RejectedProducts', 'as' => 'RejectedProducts']);
        Route::get('/product/{productID}/{colorID}',['uses' => 'AdminController@product', 'as' => 'product']);
        Route::get('/removeColor/{productID}/{colorID}',['uses'=>'AdminController@removeColor','as'=>'removeProduct']);
        Route::get('/removeWholeProduct/{productID}',['uses'=>'AdminController@removeWholeProduct','as'=>'removeWholeProduct']);
        Route::get('/notifications',['uses'=>'AdminController@notifications','as'=>'notifications']);
        Route::post('/updateProduct',['uses' => 'AdminController@updateProduct','as'=>'updateProduct']);
        Route::get('/setMain/{colorID}/{imageID}',['uses' => 'AdminController@setMain','as'=>'setMain']);
        Route::get('/removeImage/{colorID}/{imageID}',['uses' => 'AdminController@removeImage','as'=>'removeImage']);

        Route::get('/tracking',function(){
            return view('tracking');
        });
    });
});

// API's routes
$api->version('v1',function ($api){
    $api->post('login','App\Http\Controllers\APIController@login');
    $api->post('fbLogin','App\Http\Controllers\APIController@fbLogin');
    $api->post('register','App\Http\Controllers\APIController@register');
    $api->post('products','App\Http\Controllers\APIController@products');
    $api->post('suppliers','App\Http\Controllers\APIController@suppliers');
    $api->post('tracking','App\Http\Controllers\APIController@track');
    $api->post('supplierProducts','App\Http\Controllers\APIController@supplierProducts');
    $api->post('categoryProducts','App\Http\Controllers\APIController@categoryProducts');
    $api->post('product','App\Http\Controllers\APIController@product');
    $api->post('addFavourites','App\Http\Controllers\APIController@addFavourites');
    $api->post('removeFavourite','App\Http\Controllers\APIController@removeFavourite');
    $api->post('categories','App\Http\Controllers\APIController@categories');
    $api->post('showFavourites','App\Http\Controllers\APIController@showFavourites');
    $api->post('addToCart','App\Http\Controllers\APIController@addToCart');
    $api->post('cartProductQuantity','App\Http\Controllers\APIController@cartProductQuantity');
    $api->post('removeCartProduct','App\Http\Controllers\APIController@removeCartProduct');
    $api->post('cartProducts','App\Http\Controllers\APIController@cartProducts');
    $api->post('placeOrder','App\Http\Controllers\APIController@placeOrder');
    $api->post('updateProfile','App\Http\Controllers\APIController@updateProfile');
});

Route::any('{catchall}', function ($page) {
    return back();
})->where('catchall', '(.*)');
