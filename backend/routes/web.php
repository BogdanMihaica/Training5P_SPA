<?php
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\GuestMiddleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

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

    Route::controller(UserController::class)->group(function(){
        Route::post('/login', 'store');
        Route::post('/logout', 'destroy');
    });

    Route::get('/csrf-token', function () {
        return response()->json(['csrf_token' => csrf_token()]);
    });
});