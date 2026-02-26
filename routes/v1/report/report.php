<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\Report\ReportController;

Route::middleware('auth:sanctum')->group(function () {

    Route::get('reports/summary', [ReportController::class, 'summary'])->name('reports.summary');

});

