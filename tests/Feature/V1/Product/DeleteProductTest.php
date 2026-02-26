<?php

namespace Tests\Feature\V1\Product;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Factories\ProductFactory;
use Tests\Factories\UserFactory;
use Tests\TestCase;

class DeleteProductTest extends TestCase
{
    use RefreshDatabase;

    private $endPoint = 'api.v1.product.destroy';
    private $user;
    private $product;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = UserFactory::generateUser();
        $this->product = ProductFactory::generateProduct();

        $this->actingAs($this->user);
    }

    public function test_user_can_delete_product()
    {
        $this->postRequest(
            $this->endPoint,
            'DELETE',
            [],
            [],
            $this->product[0]->id
        )->assertStatus(204);

        $this->assertSoftDeleted('products', [
            'id' => $this->product[0]->id
        ]);
    }
}
