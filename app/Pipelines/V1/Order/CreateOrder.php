<?php

namespace App\Pipelines\V1\Order;

use Closure;
use App\Services\V1\Order\OrderItemService;
use Illuminate\Support\Facades\Auth;

class CreateOrder
{
    public function handle(array $data, Closure $next): array
    {
        $order = \App\Models\Order::create([
            'user_id' => Auth::id(),
            'status'  => 'pending',
            'total'   => 0,
        ]);

        $data['order'] = $order;

        return $next($data);
    }
}

