<?php

namespace App\Pipelines\V1\Order;

use Closure;
use App\Services\V1\Order\OrderItemService;

class AttachOrderItems
{
    public function __construct(protected OrderItemService $orderItemService) {}

    public function handle(array $data, Closure $next): array
    {
        foreach ($data['items'] as $item) {
            $product = \App\Models\Product::findOrFail($item['product_id']);

            $this->orderItemService->create([
                'order_id'   => $data['order']->id,
                'product_id' => $product->id,
                'quantity'   => $item['quantity'],
                'price'      => $product->price,
                'status'     => 'active',
            ]);
        }

        return $next($data);
    }
}

