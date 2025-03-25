<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::any('service-status', [BaseController::class, 'serviceStatus']);

    // ✅ Explicitly define the /user route before controller-based routes
    Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'user']);

    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

Route::prefix('v1')->group(function () {
    require __DIR__ . "/api/category.php";
    require __DIR__ . "/api/brand.php";
    require __DIR__ . "/api/product_attribute.php";
    require __DIR__ . "/api/product.php";
    require __DIR__ . "/api/cart.php";
    require __DIR__ . "/api/order.php";
});
