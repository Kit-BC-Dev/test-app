<?php

namespace App\Pipelines\V1\Order;

use Closure;

class CancelOrderItems
{
    public function handle(array $data, Closure $next): array
    {
        $order = $data['order'];

        foreach ($data['items'] as $item) {
            $item->update(['status' => 'cancelled']);
        }

        $hasActiveItems = $order->activeItems()->exists();

        if ($data['full'] || !$hasActiveItems) {
            $order->update(['status' => 'cancelled']);
        }

        $data['order'] = $order->fresh();

        return $next($data);
    }
}

