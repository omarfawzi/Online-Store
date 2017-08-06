<?php

namespace App\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

$api = app('Dingo\Api\Routing\Router');
//$token = 'ad1f44bb-1ff7-4f59-be6a-d4ffd72d45a3';
Route::get('/',function(){
   return redirect('/admin');
});
Route::group(['prefix'=>'admin'],function (){
    Auth::routes();
    Route::group(['middleware'=>'auth'],function (){

        Route::get('/', 'HomeController@index')->name('home');

        Route::group(['middleware'=>'App\Http\Middleware\CompanyMiddleware'],function (){
            Route::group(['as' => 'company.sidebar.'], function () {
                Route::get('/addProduct', ['uses' => 'HomeController@addProductView', 'as' => 'addProductView']);
                Route::get('/profile',['uses'=>'HomeController@profile','as'=>'profile']);
            });
            Route::post('/addProduct', ['uses' => 'HomeController@addProduct', 'as' => 'addProduct']);
            Route::get('/newColor/{productID}',['uses' => 'HomeController@newColor', 'as' => 'newColor']);
            Route::post('/addProductColor',['uses' => 'HomeController@addProductColor', 'as' => 'addProductColor']);
            Route::get('/setMain/{colorID}/{imageID}',['uses' => 'HomeController@setMain','as'=>'setMain']);
            Route::get('/removeImage/{colorID}/{imageID}',['uses' => 'HomeController@removeImage','as'=>'removeImage']);
            Route::post('/updateProfile',['uses'=>'HomeController@updateProfile','as'=>'updateProfile']);
        });

        Route::group(['middleware'=>'admin'],function () {
            Route::group(['as' => 'admin.sidebar.'], function () {
                Route::get('/categories', ['uses' => 'HomeController@categoriesView', 'as' => 'categoriesView']);
            });
            Route::post('/addCategory',['uses' => 'HomeController@addCategory', 'as' => 'addCategory']);
            Route::get('/approve/{colorID}',['uses'=>'HomeController@approve','as'=>'approve']);
            Route::get('/reject/{colorID}',['uses'=>'HomeController@reject','as'=>'reject']);
            Route::post('/updateCategory',['uses' => 'HomeController@updateCategory', 'as' => 'updateCategory']);
            Route::get('/deleteCategory/{categoryID}',['uses' => 'HomeController@deleteCategory', 'as' => 'deleteCategory']);
        });
        Route::get('/ApprovedProducts',['uses' => 'HomeController@ApprovedProducts', 'as' => 'ApprovedProducts']);
        Route::get('/WaitingProducts',['uses' => 'HomeController@WaitingProducts', 'as' => 'WaitingProducts']);
        Route::get('/RejectedProducts',['uses' => 'HomeController@RejectedProducts', 'as' => 'RejectedProducts']);
        Route::get('/product/{productID}/{colorID}',['uses' => 'HomeController@product', 'as' => 'product']);
        Route::get('/removeColor/{productID}/{colorID}',['uses'=>'HomeController@removeColor','as'=>'removeProduct']);
        Route::get('/removeWholeProduct/{productID}',['uses'=>'HomeController@removeWholeProduct','as'=>'removeWholeProduct']);
        Route::get('/notifications',['uses'=>'HomeController@notifications','as'=>'notifications']);
        Route::post('/updateProduct',['uses' => 'HomeController@updateProduct','as'=>'updateProduct']);
        Route::get('/tracking',function(){
            return view('tracking');
        });
    });
});

$api->version('v1',function ($api){
    $api->post('login','App\Http\Controllers\AndroidController@login');
    $api->post('register','App\Http\Controllers\AndroidController@register');
    $api->post('products','App\Http\Controllers\AndroidController@products');
    $api->post('suppliers','App\Http\Controllers\AndroidController@suppliers');
    $api->post('tracking','App\Http\Controllers\AndroidController@track');
    $api->post('supplierProducts','App\Http\Controllers\AndroidController@supplierProducts');
    $api->post('categoryProducts','App\Http\Controllers\AndroidController@categoryProducts');
    $api->post('product','App\Http\Controllers\AndroidController@product');
    $api->post('addFavourites','App\Http\Controllers\AndroidController@addFavourites');
    $api->post('categories','App\Http\Controllers\AndroidController@categories');
    $api->post('showFavourites','App\Http\Controllers\AndroidController@showFavourites');
    $api->post('addToCart','App\Http\Controllers\AndroidController@addToCart');
    $api->post('cartProductQuantity','App\Http\Controllers\AndroidController@cartProductQuantity');
    $api->post('removeCartProduct','App\Http\Controllers\AndroidController@removeCartProduct');
    $api->post('cartProducts','App\Http\Controllers\AndroidController@cartProducts');
});

Route::any('{catchall}', function ($page) {
    return redirect('/');
})->where('catchall', '(.*)');
