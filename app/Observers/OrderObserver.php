<?php

namespace App\Observers;

use App\Models\Order;
use App\Services\V1\InventoryLog\InventoryLogService;

class OrderObserver
{
    public function __construct(protected InventoryLogService $inventoryLogService) {}

    public function created(Order $order): void
    {
        $this->inventoryLogService->create([
            'event'      => 'order.created',
            'model_type' => Order::class,
            'model_id'   => $order->id,
            'user_id'    => $order->user_id,
            'before'     => null,
            'after'      => $order->toArray(),
        ]);
    }

    public function updated(Order $order): void
    {
        if (!$order->wasChanged('status')) {
            return;
        }

        $event = match ($order->status) {
            'confirmed' => 'order.confirmed',
            'cancelled' => 'order.cancelled',
            default     => 'order.updated',
        };

        $this->inventoryLogService->create([
            'event'      => $event,
            'model_type' => Order::class,
            'model_id'   => $order->id,
            'user_id'    => $order->user_id,
            'before'     => ['status' => $order->getOriginal('status')],
            'after'      => ['status' => $order->status],
        ]);
    }
}

