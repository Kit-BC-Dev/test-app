<?php

namespace App\Pipelines\V1\Order;

use Closure;

class RecalculateTotal
{
    public function handle(array $data, Closure $next): array
    {
        $order = $data['order'];

        if ($order->status !== 'cancelled') {
            $total = $order->activeItems->sum(fn ($item) => $item->price * $item->quantity);
            $order->update(['total' => $total]);
            $data['order'] = $order->fresh();
        }

        return $next($data);
    }
}

