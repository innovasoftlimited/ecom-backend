<?php

use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/products', [ProductController::class, 'productStoreOrUpdate']);
    Route::get('/products', [ProductController::class, 'getProductList']);
    Route::delete('/products/{id}', [ProductController::class, 'deleteProduct']);
    Route::get('/search-product', [ProductController::class, 'searchProduct']);

});
