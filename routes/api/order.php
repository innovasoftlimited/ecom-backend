<?php
use App\Http\Controllers\Api\OrderController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/orders', [OrderController::class, 'store']); // Create Order
    Route::get('/orders', [OrderController::class, 'getOrderList']); // Get All Orders
    Route::get('/orders/{id}', [OrderController::class, 'show']); // Get Single Order
    Route::put('/orders/{id}', [OrderController::class, 'update']); // Update Order
    Route::delete('/orders/{id}', [OrderController::class, 'destroy']); // Delete Order
});
