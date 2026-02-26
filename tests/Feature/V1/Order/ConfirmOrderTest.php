<?php

namespace Tests\Feature\V1\Order;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\OrderFactory;
use Tests\Factories\UserFactory;
use Tests\TestCase;

class ConfirmOrderTest extends TestCase
{
    use RefreshDatabase;

    private string $route = 'api.v1.orders.confirm';

    public function test_user_can_confirm_pending_order(): void
    {
        $user  = UserFactory::generateUser();
        $order = OrderFactory::generateOrderWithItems($user);

        $this->actingAs($user)
            ->patchJson(route($this->route, $order->id))
            ->assertStatus(200)
            ->assertJsonPath('data.status', 'confirmed');

        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'confirmed']);
    }

    public function test_confirming_order_deducts_inventory(): void
    {
        $user  = UserFactory::generateUser();
        $order = OrderFactory::generateOrderWithItems($user, 1);
        $item  = $order->items->first();
        $initialQuantity = $item->product->quantity;

        $this->actingAs($user)
            ->patchJson(route($this->route, $order->id))
            ->assertStatus(200);

        $this->assertDatabaseHas('products', [
            'id'       => $item->product_id,
            'quantity' => $initialQuantity - $item->quantity,
        ]);
    }

    public function test_confirming_order_calculates_total(): void
    {
        $user  = UserFactory::generateUser();
        $order = OrderFactory::generateOrderWithItems($user);

        $expectedTotal = $order->items->sum(fn ($i) => $i->price * $i->quantity);

        $this->actingAs($user)
            ->patchJson(route($this->route, $order->id))
            ->assertStatus(200)
            ->assertJsonPath('data.total', (string) number_format($expectedTotal, 2, '.', ''));
    }

    public function test_cannot_confirm_already_confirmed_order(): void
    {
        $user  = UserFactory::generateUser();
        $order = OrderFactory::generateConfirmedOrderWithItems($user);

        $this->actingAs($user)
            ->patchJson(route($this->route, $order->id))
            ->assertStatus(403);
    }

    public function test_fails_when_insufficient_stock(): void
    {
        $user  = UserFactory::generateUser();
        $order = OrderFactory::generateOrderWithItems($user, 1);
        $item  = $order->items->first();

        // Force quantity to 0 to simulate out-of-stock
        $item->product->update(['quantity' => 0]);

        $this->actingAs($user)
            ->patchJson(route($this->route, $order->id))
            ->assertStatus(422);
    }

    public function test_another_user_cannot_confirm_order(): void
    {
        $owner = UserFactory::generateUser();
        $other = UserFactory::generateUser();
        $order = OrderFactory::generateOrderWithItems($owner);

        $this->actingAs($other)
            ->patchJson(route($this->route, $order->id))
            ->assertStatus(403);
    }
}

