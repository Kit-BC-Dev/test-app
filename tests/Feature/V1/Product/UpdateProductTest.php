<?php

namespace Tests\Feature\V1\Product;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Factories\ProductFactory;
use Tests\Factories\UserFactory;

class UpdateProductTest extends TestCase
{
     use RefreshDatabase;
    private $endPoint = 'api.v1.product.update';
    private $product;
    private $user;
    private $existingProduct;

    public function setUp(): void
    {
        parent::setUp();
        $this->product = ProductFactory::generateDummyDataForProduct();
        $this->user = UserFactory::generateUser();
        $this->actingAs($this->user);
        $this->existingProduct = ProductFactory::generateProduct(1, $this->user->id);
        
    }

    public function test_user_can_update_product(): void
    {
        $this->postRequest(
            $this->endPoint,
            'PATCH',
            $this->product,
            [],
            $this->existingProduct[0]->id
        )->assertStatus(200);

        $this->assertDatabaseHas('products', [
            'name' => $this->product['name'],
            'description' => $this->product['description'],
        ]);

       
    }

    public function test_user_cannot_update_product_with_invalid_data(): void
    {
        $requiredFields = [
            'name',
            'description',
            'price',
            'quantity',
        ];

        foreach ($requiredFields as $field) {
            $response = $this->postRequest(
                $this->endPoint,
                'PATCH',
                $this->product,
                [$field => ''],
                $this->existingProduct[0]->id
            )->assertStatus(422);

            $response->assertJsonValidationErrorFor($field);

        }
    }

    public function test_user_cannot_update_other_user_products(): void
    {
        $newUser = UserFactory::generateUser();
        $this->actingAs($newUser);

        $response = $this->postRequest(
            $this->endPoint,
            'PATCH',
            $this->product,
            [],
            $this->existingProduct[0]->id
        )->assertStatus(403);

        
    }

}
