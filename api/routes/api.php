<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([

    'middleware' => 'api',
    'namespace' => 'App\Http\Controllers',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login',    'AuthController@login');
    Route::post('logout',   'AuthController@logout');
    Route::post('refresh',  'AuthController@refresh');
    Route::post('signup',   'AuthController@register');
    Route::post('me',       'AuthController@me');

});

Route::resource('order',     OrderController::class);
Route::resource('cart',    CartController::class);
Route::resource('product',  ProductController::class);

Route::post('add-to-cart/{product}',          'App\Http\Controllers\CartController@add_to_cart')->name('cart.add');
Route::get('all/carts/{order}',       'App\Http\Controllers\CartController@carts_under_order')->name('order.cart.get');
Route::post('search/product',                 'App\Http\Controllers\ProductController@search')->name('product.search');
Route::post('order/history/{order}',          'App\Http\Controllers\OrderController@history')->name('order.history');
Route::get('all/products',                    'App\Http\Controllers\ProductController@get_all_product')->name('all.product');
