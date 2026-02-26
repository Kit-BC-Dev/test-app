<?php

namespace Tests\Feature\V1\Order;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Factories\OrderFactory;
use Tests\Factories\UserFactory;
use Tests\TestCase;

class GetOrderTest extends TestCase
{
    use RefreshDatabase;

    private string $indexRoute = 'api.v1.orders.index';
    private string $showRoute  = 'api.v1.orders.show';

    public function test_user_can_list_their_orders(): void
    {
        $user = UserFactory::generateUser();
        OrderFactory::generateOrderWithItems($user);
        OrderFactory::generateOrderWithItems($user);

        $this->actingAs($user)
            ->getJson(route($this->indexRoute))
            ->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }

    public function test_user_cannot_see_other_users_orders(): void
    {
        $owner = UserFactory::generateUser();
        $other = UserFactory::generateUser();
        OrderFactory::generateOrderWithItems($owner);

        $this->actingAs($other)
            ->getJson(route($this->indexRoute))
            ->assertStatus(200)
            ->assertJsonCount(0, 'data');
    }

    public function test_user_can_view_own_order(): void
    {
        $user  = UserFactory::generateUser();
        $order = OrderFactory::generateOrderWithItems($user);

        $this->actingAs($user)
            ->getJson(route($this->showRoute, $order->id))
            ->assertStatus(200)
            ->assertJsonPath('data.id', $order->id);
    }

    public function test_user_cannot_view_another_users_order(): void
    {
        $owner = UserFactory::generateUser();
        $other = UserFactory::generateUser();
        $order = OrderFactory::generateOrderWithItems($owner);

        $this->actingAs($other)
            ->getJson(route($this->showRoute, $order->id))
            ->assertStatus(403);
    }

    public function test_unauthenticated_user_cannot_list_orders(): void
    {
        $this->getJson(route($this->indexRoute))->assertStatus(401);
    }
}
