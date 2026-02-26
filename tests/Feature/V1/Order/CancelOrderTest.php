<?php

namespace Tests\Feature\V1\Order;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\OrderFactory;
use Tests\Factories\UserFactory;
use Tests\TestCase;

class CancelOrderTest extends TestCase
{
    use RefreshDatabase;

    private string $cancelRoute     = 'api.v1.orders.cancel';
    private string $cancelItemRoute = 'api.v1.orders.items.cancel';

    public function test_user_can_cancel_pending_order(): void
    {
        $user  = UserFactory::generateUser();
        $order = OrderFactory::generateOrderWithItems($user);

        $this->actingAs($user)
            ->patchJson(route($this->cancelRoute, $order->id))
            ->assertStatus(200)
            ->assertJsonPath('data.status', 'cancelled');

        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'cancelled']);
    }

    public function test_user_can_cancel_confirmed_order_and_inventory_is_restored(): void
    {
        $user  = UserFactory::generateUser();
        $order = OrderFactory::generateConfirmedOrderWithItems($user);
        $item  = $order->items->first();
        $quantityBeforeCancel = $item->product->fresh()->quantity;

        $this->actingAs($user)
            ->patchJson(route($this->cancelRoute, $order->id))
            ->assertStatus(200);

        $this->assertDatabaseHas('products', [
            'id'       => $item->product_id,
            'quantity' => $quantityBeforeCancel + $item->quantity,
        ]);
    }

    public function test_user_can_partially_cancel_an_order(): void
    {
        $user  = UserFactory::generateUser();
        $order = OrderFactory::generateOrderWithItems($user, 2);
        $item  = $order->items->first();

        $this->actingAs($user)
            ->patchJson(route($this->cancelItemRoute, [$order->id, $item->id]))
            ->assertStatus(200)
            ->assertJsonPath('data.status', 'pending');

        $this->assertDatabaseHas('order_items', ['id' => $item->id, 'status' => 'cancelled']);
    }

    public function test_cancelling_last_item_cancels_entire_order(): void
    {
        $user  = UserFactory::generateUser();
        $order = OrderFactory::generateOrderWithItems($user, 1);
        $item  = $order->items->first();

        $this->actingAs($user)
            ->patchJson(route($this->cancelItemRoute, [$order->id, $item->id]))
            ->assertStatus(200)
            ->assertJsonPath('data.status', 'cancelled');
    }

    public function test_another_user_cannot_cancel_order(): void
    {
        $owner = UserFactory::generateUser();
        $other = UserFactory::generateUser();
        $order = OrderFactory::generateOrderWithItems($owner);

        $this->actingAs($other)
            ->patchJson(route($this->cancelRoute, $order->id))
            ->assertStatus(403);
    }

    public function test_cannot_cancel_already_cancelled_order(): void
    {
        $user  = UserFactory::generateUser();
        $order = OrderFactory::generateOrder($user, 'cancelled');

        $this->actingAs($user)
            ->patchJson(route($this->cancelRoute, $order->id))
            ->assertStatus(403);
    }
}

