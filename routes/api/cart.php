<?php

use App\Http\Controllers\Api\CartController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/cart', [CartController::class, 'addToCartOrUpdate']);
    Route::get('/cart', [CartController::class, 'getCartList']);
    Route::delete('/cart-item/{cartId}/{itemId}', [CartController::class, 'deleteCartItemWithCart']);

});
