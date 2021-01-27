<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->group(function(){

    Route::middleware('admin')->prefix("seller")->group(function(){
        Route::resources([
            'category' => 'CategoryController',
            'product' => 'ProductController',
            'order' => 'OrderController',
        ]);

        Route::post("/update-status/{id}", "OrderController@updateStatus");


    });

    Route::resources([
        'user-address' => 'UserAddressController',
    ]);

    Route::get("/my-order","OrderController@myOrder");
    Route::get("/detail-order/{id}","OrderController@show");
    Route::get("/order","OrderController@index");
    Route::post("/order","OrderController@store");
    Route::get("/my-address", "UserAddressController@index");


});



// for buyer
Route::get("/category","CategoryController@index");

Route::get("/category/{id}","CategoryController@show");

Route::get("/product/new","ProductController@new");
Route::get("/product/popular","ProductController@popular");
Route::get("/product/search/{search}","ProductController@search");
Route::get("/product/category/{category}","ProductController@category");

Route::get("/product/{id}","ProductController@show");




// Auth
Route::post("/login", 'AuthController@login');
Route::post("/register", 'AuthController@register');