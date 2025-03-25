<?php

use App\Http\Controllers\Api\BrandController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/brands', [BrandController::class, 'brandStoreOrUpdate']);
    Route::get('/brands', [BrandController::class, 'getBrandList']);
    Route::delete('/brands/{id}', [BrandController::class, 'deleteBrand']);

});
