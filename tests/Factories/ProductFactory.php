<?php

namespace Tests\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductFactory
{
    public static function generateProduct(int $count = 1, int $user_id = 0): Product|Collection
    {
        
        return Product::factory()->count($count)->create(
            [
                'user_id' => $user_id ?: UserFactory::generateUser()->id,
            ]
        );
    }

    public static function generateDummyDataForProduct(): array
    {
        return [
            'name' => fake()->name(),
            'quantity' => fake()->numberBetween(1, 100),
            'price' => fake()->randomFloat(2, 1, 1000),
            'description' => fake()->text(),
        ];
    }
}