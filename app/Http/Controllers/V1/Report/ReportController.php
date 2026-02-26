<?php

namespace App\Http\Controllers\V1\Report;

use App\Http\Controllers\Controller;
use App\Services\V1\Report\ReportService;
use Illuminate\Http\JsonResponse;

class ReportController extends Controller
{
    public function __construct(protected ReportService $reportService) {}

    public function summary(): JsonResponse
    {
        return response()->json([
            'data' => $this->reportService->getSummary(),
        ], 200);
    }
}

