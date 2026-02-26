<?php

namespace Tests\Feature\V1\Order;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\ProductFactory;
use Tests\Factories\UserFactory;
use Tests\TestCase;

class CreateOrderTest extends TestCase
{
    use RefreshDatabase;

    private string $route = 'api.v1.orders.store';

    public function test_user_can_create_order(): void
    {
        $user    = UserFactory::generateUser();
        $product = ProductFactory::generateProduct(user_id: $user->id);

        $this->actingAs($user)
            ->postJson(route($this->route), [
                'items' => [
                    ['product_id' => $product[0]->id, 'quantity' => 2],
                ],
            ])
            ->assertStatus(201)
            ->assertJsonPath('data.status', 'pending');

        $this->assertDatabaseHas('orders', ['user_id' => $user->id, 'status' => 'pending']);
        $this->assertDatabaseHas('order_items', ['product_id' => $product[0]->id, 'quantity' => 2]);
    }

    public function test_order_creation_requires_items(): void
    {
        $user = UserFactory::generateUser();

        $this->actingAs($user)
            ->postJson(route($this->route), [])
            ->assertStatus(422)
            ->assertJsonValidationErrorFor('items');
    }

    public function test_order_item_must_have_valid_product(): void
    {
        $user = UserFactory::generateUser();

        $this->actingAs($user)
            ->postJson(route($this->route), [
                'items' => [
                    ['product_id' => 99999, 'quantity' => 1],
                ],
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrorFor('items.0.product_id');
    }

    public function test_order_item_quantity_must_be_at_least_one(): void
    {
        $user    = UserFactory::generateUser();
        $product = ProductFactory::generateProduct(user_id: $user->id);

        $this->actingAs($user)
            ->postJson(route($this->route), [
                'items' => [
                    ['product_id' => $product[0]->id, 'quantity' => 0],
                ],
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrorFor('items.0.quantity');
    }

    public function test_unauthenticated_user_cannot_create_order(): void
    {
        $this->postJson(route($this->route), [])->assertStatus(401);
    }
}

