<?php

namespace Tests\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;

class OrderFactory
{
    public static function generateOrder(User $user, string $status = 'pending'): Order
    {
        return Order::factory()->create([
            'user_id' => $user->id,
            'status'  => $status,
        ]);
    }

    public static function generateOrderWithItems(User $user, int $itemCount = 2): Order
    {
        $order = static::generateOrder($user);

        for ($i = 0; $i < $itemCount; $i++) {
            $product = Product::factory()->create(['user_id' => $user->id, 'quantity' => 50]);
            OrderItem::factory()->create([
                'order_id'   => $order->id,
                'product_id' => $product->id,
                'quantity'   => 2,
                'price'      => $product->price,
            ]);
        }

        return $order->fresh(['items.product']);
    }

    public static function generateConfirmedOrderWithItems(User $user): Order
    {
        $order = static::generateOrderWithItems($user);
        $order->update(['status' => 'confirmed', 'total' => $order->items->sum(fn ($i) => $i->price * $i->quantity)]);

        return $order->fresh(['items.product']);
    }
}

