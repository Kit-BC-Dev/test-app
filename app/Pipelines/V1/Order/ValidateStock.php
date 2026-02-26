<?php

namespace App\Pipelines\V1\Order;

use Closure;
use App\Models\Product;
use Illuminate\Validation\ValidationException;

class ValidateStock
{
    public function handle(array $data, Closure $next): array
    {
        foreach ($data['order']->activeItems as $item) {
            $product = Product::findOrFail($item->product_id);

            if ($product->quantity < $item->quantity) {
                throw ValidationException::withMessages([
                    'stock' => "Insufficient stock for product: {$product->name}. Available: {$product->quantity}, Requested: {$item->quantity}.",
                ]);
            }
        }
        return $next($data);
    }
}

