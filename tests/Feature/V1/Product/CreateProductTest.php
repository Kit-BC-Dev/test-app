<?php

namespace Tests\Feature\V1\Product;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Factories\ProductFactory;
use Tests\Factories\UserFactory;
use Tests\TestCase;

class CreateProductTest extends TestCase
{
    use RefreshDatabase;
    private $endPoint = 'api.v1.product.store';
    private $product;
    private $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->product = ProductFactory::generateDummyDataForProduct();
        $this->user = UserFactory::generateUser();

        $this->actingAs($this->user);
    }

    public function test_user_can_add_product(): void
    {
        $this->postRequest(
            $this->endPoint,
            'POST',
            $this->product,
            ['user_id' => $this->user->id]
        )->assertStatus(201);

        $this->assertDatabaseHas('products', [
            'name' => $this->product['name'],
            'description' => $this->product['description'],
        ]);

       
    }

    public function test_user_cannot_add_product_with_invalid_data(): void
    {
        $requiredFields = [
            'name',
            'description',
            'price',
            'quantity',
            'user_id',
        ];

        foreach ($requiredFields as $field) {
            $response = $this->postRequest(
                $this->endPoint,
                'POST',
                $this->product,
                [$field => '']
            )->assertStatus(422);

            $response->assertJsonValidationErrorFor($field);

        }
    }

    public function test_user_cannot_add_if_user_not_exist(): void
    {
        $response = $this->postRequest(
            $this->endPoint,
            'POST',
            $this->product,
            ['user_id' => 0]
        )->assertStatus(422);

        $response->assertJsonValidationErrorFor('user_id');
        
    }

    

}
