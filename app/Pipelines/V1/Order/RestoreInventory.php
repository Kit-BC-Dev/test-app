<?php

namespace App\Pipelines\V1\Order;

use Closure;
use App\Services\V1\Product\ProductService;

class RestoreInventory
{
    public function __construct(protected ProductService $productService) {}

    public function handle(array $data, Closure $next): array
    {
        if ($data['order']->status === 'confirmed') {
            foreach ($data['items'] as $item) {
                $this->productService->incrementQuantity($item->product_id, $item->quantity);
            }
        }

        return $next($data);
    }
}

