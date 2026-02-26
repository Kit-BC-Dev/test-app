<?php

namespace App\Services\V1\Report;

use App\Services\V1\Order\OrderService;
use App\Services\V1\Product\ProductService;
use App\Services\V1\InventoryLog\InventoryLogService;

class ReportService
{
    public function __construct(
        protected OrderService $orderService,
        protected ProductService $productService,
        protected InventoryLogService $inventoryLogService,
    ) {}

    public function getSummary(): array
    {
        return [
            'orders'          => $this->getOrdersSummary(),
            'revenue'         => $this->getRevenueSummary(),
            'inventory'       => $this->getInventorySummary(),
            'recent_activity' => $this->inventoryLogService->getRecent(),
        ];
    }

    private function getOrdersSummary(): array
    {
        return [
            'total'     => $this->orderService->count(),
            'pending'   => $this->orderService->countByStatus('pending'),
            'confirmed' => $this->orderService->countByStatus('confirmed'),
            'cancelled' => $this->orderService->countByStatus('cancelled'),
        ];
    }

    private function getRevenueSummary(): array
    {
        return [
            'total' => $this->orderService->getTotalRevenue(),
        ];
    }

    private function getInventorySummary(): array
    {
        return [
            'total_products' => $this->productService->count(),
            'low_stock'      => $this->productService->countLowStock(),
            'out_of_stock'   => $this->productService->countOutOfStock(),
        ];
    }
}

