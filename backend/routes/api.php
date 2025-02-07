<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(ProductController::class)->group(function(){
    Route::get('/products','index');
    Route::get('/products/{product}','edit');
});

Route::controller(CartController::class)->group(function(){
    Route::get('/cart','index');
    Route::delete('/cart/{product}','delete');
    Route::post('/cart/{product}','store');
});

