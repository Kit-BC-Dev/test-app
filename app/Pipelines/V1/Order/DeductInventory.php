<?php

namespace App\Pipelines\V1\Order;

use Closure;
use App\Services\V1\Product\ProductService;

class DeductInventory
{
    public function __construct(protected ProductService $productService) {}

    public function handle(array $data, Closure $next): array
    {
        foreach ($data['order']->activeItems as $item) {
            $this->productService->decrementQuantity($item->product_id, $item->quantity);
        }

        return $next($data);
    }
}

