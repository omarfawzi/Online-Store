<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


// Website Routes
Route::get('/','WebController@index')->name('index');
Route::group(['prefix'=>'/'],function(){
    Route::group(['prefix'=>'auth','middleware'=>'App\Http\Middleware\WebRedirect'],function (){
        Route::get('{provider}', 'WebLoginController@redirectToProvider')->name('socialAuth');
        Route::get('{provider}/callback', 'WebLoginController@handleProviderCallback');
        Route::post('customer_register', 'WebRegisterController@register')->name('customer_register');
        Route::post('customer_login', 'WebLoginController@login')->name('customer_login');
    });
    Route::group(['prefix'=>'products'],function (){
        Route::get('{gender}/{categoryName}','WebController@categoryProducts')->name('categoryProducts');
        Route::get('{supplierName}','WebController@supplierProducts')->name('supplierProducts');
    });
    Route::get('product/{productName}/{colorID}','WebController@product')->name('singleProduct');
    Route::group(['middleware'=>'App\Http\Middleware\WebMiddleware'],function(){
        Route::post('addToCart','WebController@addToCart')->name('addToCart');
        Route::get('myCart','WebController@myCart')->name('myCart');
        Route::get('removeCartProduct','WebController@removeCartProduct')->name('removeCartProduct');
        Route::get('cartQuantity','WebController@cartQuantity')->name('cartQuantity');
        Route::get('emptyCart','WebController@emptyCart')->name('emptyCart');
        Route::post('updateProfile','WebController@updateProfile')->name('updateCustomerProfile');
        Route::post('placeOrder','WebController@placeOrder')->name('placeOrder');
        Route::post('customer_logout', 'WebLoginController@logout')->name('customer_logout');
        Route::get('myOrders/{orderID}','WebController@myOrders')->name('myOrders');
        Route::get('cancelOrderProduct','WebController@cancelOrderProduct')->name('cancelOrderProduct');
        Route::get('cancelOrder','WebController@cancelOrder')->name('cancelOrder');
    });
    Route::get('sortList','WebController@sortList')->name('sortList');
    Route::get('filterProducts','WebController@filterProducts')->name('filterProducts');
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
            Route::get('/cancelOrder/{orderID}',['uses' => 'AdminController@cancelOrder', 'as' => 'cancelOrder']);
//            Route::get('/cancelOrderProduct/{$orderDetailID}',['uses' => 'AdminController@cancelOrderProduct', 'as' => 'cancelOrderProduct']);
        });
        Route::get('/ApprovedProducts',['uses' => 'AdminController@ApprovedProducts', 'as' => 'ApprovedProducts']);
        Route::get('/WaitingProducts',['uses' => 'AdminController@WaitingProducts', 'as' => 'WaitingProducts']);
        Route::get('/RejectedProducts',['uses' => 'AdminController@RejectedProducts', 'as' => 'RejectedProducts']);
        Route::get('product/{productName}/{colorID}','AdminController@product')->name('product');
        Route::get('/removeColor/{productID}/{colorID}',['uses'=>'AdminController@removeColor','as'=>'removeProduct']);
        Route::get('/removeWholeProduct/{productID}',['uses'=>'AdminController@removeWholeProduct','as'=>'removeWholeProduct']);
        Route::get('/notifications',['uses'=>'AdminController@notifications','as'=>'notifications']);
        Route::post('/updateProduct',['uses' => 'AdminController@updateProduct','as'=>'updateProduct']);
        Route::get('/setMain/{colorID}/{imageID}',['uses' => 'AdminController@setMain','as'=>'setMain']);
        Route::get('/removeImage/{colorID}/{imageID}',['uses' => 'AdminController@removeImage','as'=>'removeImage']);
        Route::get('/orders',['uses'=>'AdminController@orders','as'=>'orders']);
        Route::get('/orderDetails/{orderID}',['uses'=>'AdminController@orderDetails','as'=>'orderDetails']);
        Route::get('/tracking/{orderID}',['uses'=>'AdminController@tracking','as'=>'tracking']);

    });
});

// API's routes
Route::group(['prefix'=>'api'],function (){
    Route::post('login','APIController@login');
    Route::post('fbLogin','APIController@fbLogin');
    Route::post('register','APIController@register');
    Route::post('products','APIController@products');
    Route::post('suppliers','APIController@suppliers');
    Route::post('supplierProducts','APIController@supplierProducts');
    Route::post('categoryProducts','APIController@categoryProducts');
    Route::post('product','APIController@product');
    Route::post('addFavourites','APIController@addFavourites');
    Route::post('removeFavourite','APIController@removeFavourite');
    Route::post('categories','APIController@categories');
    Route::post('showFavourites','APIController@showFavourites');
    Route::post('addToCart','APIController@addToCart');
    Route::post('cartProductQuantity','APIController@cartProductQuantity');
    Route::post('removeCartProduct','APIController@removeCartProduct');
    Route::post('cartProducts','APIController@cartProducts');
    Route::post('placeOrder','APIController@placeOrder');
    Route::post('getOrders','APIController@getOrders');
    Route::post('updateProfile','APIController@updateProfile');
    Route::post('showProfile','APIController@showProfile');
    Route::post('filterComponents','APIController@filterComponents');
    Route::post('filterBy','APIController@filterBy');
    Route::post('sendLocation','APIController@sendLocation');
});

Route::any('{catchall}', function ($page) {
    return back();
})->where('catchall', '(.*)');
