<?php

use App\Http\Controllers\Api\ProductAttributeController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/attributes', [ProductAttributeController::class, 'attributeStoreOrUpdate']);
    Route::get('/attributes', [ProductAttributeController::class, 'getProductAttributeList']);
    Route::delete('/attributes/{id}', [ProductAttributeController::class, 'deleteProductAttribute']);

});
