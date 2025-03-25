<?php

use App\Http\Controllers\Api\CategoryController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/categories', [CategoryController::class, 'categoryStoreOrUpdate']);
    Route::get('/categories', [CategoryController::class, 'getCategoryList']);
    Route::delete('/categories/{id}', [CategoryController::class, 'deleteCategory']);

});
