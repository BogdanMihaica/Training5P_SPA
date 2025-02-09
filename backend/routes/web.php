<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('/spa')->group(function () {
    Route::controller(ProductController::class)->group(function(){
        Route::get('/products','index');
        Route::get('/products/{product}','edit');
    });

    Route::controller(CartController::class)->group(function(){
        Route::get('/cart','index');
        Route::delete('/cart/{product}','destroy');
        Route::post('/cart/{product}','store');
    });

    Route::get('/csrf-token', function () {
        return response()->json(['csrf_token' => csrf_token()]);
    });
    
    Route::controller(UserController::class)->group(function(){
        Route::post('/login', 'store');
        Route::middleware('auth:sanctum')->group(function(){
            Route::post('/logout', 'destroy');
        });
    });
});