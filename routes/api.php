<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'v1',
    'as' => 'api.v1.',
], function () {
    require __DIR__ . '/v1/auth/auth.php';
    require __DIR__ . '/v1/product/product.php';
    require __DIR__ . '/v1/order/order.php';
    require __DIR__ . '/v1/report/report.php';
});