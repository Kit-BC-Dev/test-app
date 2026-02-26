<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function view(User $user, Order $order): bool
    {
        return $user->id === $order->user_id;
    }

    public function confirm(User $user, Order $order): bool
    {
        return $user->id === $order->user_id && $order->status === 'pending';
    }

    public function cancel(User $user, Order $order): bool
    {
        return $user->id === $order->user_id
            && in_array($order->status, ['pending', 'confirmed']);
    }

    public function cancelItem(User $user, Order $order): bool
    {
        return $user->id === $order->user_id
            && in_array($order->status, ['pending', 'confirmed']);
    }
}

