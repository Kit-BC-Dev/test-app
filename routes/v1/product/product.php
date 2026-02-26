<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\Product\ProductController;
Route::middleware('auth:sanctum')->group(function () {

    Route::apiResource('product', ProductController::class);

    Route::get('users/{user}/products',
        [ProductController::class, 'getProductByUser']
    )->name('product.user');
    
});