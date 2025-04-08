<?php

use App\Http\Controllers\Api\BrandController;
use Illuminate\Support\Facades\Route;

Route::get('/brands', [BrandController::class, 'getBrandList']);
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/brands', [BrandController::class, 'brandStoreOrUpdate']);
    Route::delete('/brands/{id}', [BrandController::class, 'deleteBrand']);

});
