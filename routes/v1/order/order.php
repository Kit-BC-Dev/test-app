<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\Order\OrderController;

Route::middleware('auth:sanctum')->group(function () {

    Route::apiResource('orders', OrderController::class)->only(['index', 'store', 'show']);

    Route::patch('orders/{order}/confirm', [OrderController::class, 'confirm'])->name('orders.confirm');
    Route::patch('orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::patch('orders/{order}/items/{item}/cancel', [OrderController::class, 'cancelItem'])->name('orders.items.cancel');

});

