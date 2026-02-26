<?php

namespace Tests\Feature\V1\Product;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Factories\ProductFactory;
use Tests\Factories\UserFactory;
use Tests\TestCase;


class FetchProductTest extends TestCase
{
    use RefreshDatabase;
    private $endPoint = 'api.v1.product.index';
    private $products;
    private $user;
    public function setUp(): void
    {
        parent::setUp();
        $this->user = UserFactory::generateUser();
        $this->products = ProductFactory::generateProduct(2);
        $this->actingAs($this->user);
    }

    public function test_get_products_with_user(): void
    {
        $response = $this->getJson(route($this->endPoint))->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'description',
                    'price',
                    'quantity',
                    'user'
                ]
            ]
        ]);

        $response->assertJsonFragment([
            'id' => $this->products[0]->id,
            'name' => $this->products[0]->name, 
        ]);
    }

    public function test_get_user_products(): void
    {
        $this->withoutExceptionHandling();
        ProductFactory::generateProduct(2, $this->user->id);

        $response = $this->getJson(route('api.v1.product.user', $this->user->id))->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'user'
                ]
            ]
        ]);

       $response->assertJsonFragment([
            'id' => $this->user->id,
        ]);
    }

    public function test_get_product_details(): void
    {
        $response = $this->getJson(route('api.v1.product.show', $this->products[0]->id))->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'description',
                'price',
                'quantity',
                'user'
            ]
        ]);

        $response->assertJsonFragment([
            'id' => $this->products[0]->id,
            'name' => $this->products[0]->name, 
        ]);
    }
}