<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\SetLocaleMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware([SetLocaleMiddleware::class])->group(function () {
    Route::prefix('/spa')->group(function () {
        Route::controller(ProductController::class)->group(function(){
            Route::get('/products','index');
            
            Route::middleware(['auth:sanctum'])->group(function(){
                Route::get('/products/all', 'all');
                Route::get('/products/{product}','edit');
                Route::put('/products/{product}', 'update');
                Route::post('/products', 'store');
                Route::delete('/products/{product}', 'destroy');
            });
        });

        Route::controller(CartController::class)->group(function(){
            Route::get('/cart','index');
            Route::delete('/cart/{product}','destroy');
            Route::post('/cart/{product}','store');
        });

        Route::controller(OrderController::class)->group(function(){
            Route::post('/orders','store');

            Route::middleware('auth:sanctum')->group(function(){
                Route::get('/orders', 'index');
                Route::get('/orders/{order}', 'products');
            });
        });

        Route::get('/csrf-token', function () {
            return response()->json(['csrf_token' => csrf_token()]);
        });
        
        Route::controller(UserController::class)->group(function(){
            Route::post('/login', 'store');

            Route::middleware('auth:sanctum')->group(function(){
                Route::post('/logout', 'destroy');
                Route::get('/user',function(){
                    return Auth::user();
                });
            });
        });

        Route::get('/change-locale', function(){
            return response()->json();
        });
    });
});