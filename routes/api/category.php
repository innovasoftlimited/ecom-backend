<?php

use App\Http\Controllers\Api\CategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/categories', [CategoryController::class, 'getCategoryList']);
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/categories', [CategoryController::class, 'categoryStoreOrUpdate']);
    Route::delete('/categories/{id}', [CategoryController::class, 'deleteCategory']);

});
