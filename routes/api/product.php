<?php

use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/products', [ProductController::class, 'getProductList']);
Route::get('/search-product', [ProductController::class, 'searchProduct']);
Route::get('/products/{id}', [ProductController::class, 'getProductById']);
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/products', [ProductController::class, 'productStoreOrUpdate']);
    Route::delete('/products/{id}', [ProductController::class, 'deleteProduct']);

});
