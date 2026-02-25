<?php

namespace Tests\Factories;
use App\Models\User;
class UserFactory
{
    public static function generateUser(string $password = ""): User
    {
        return User::factory()->create([
            'password' => $password ?: fake()->password(),
        ]);
    }

    public static function generateDummyDataForUser(): array
    {
        $password = fake()->password();
        return [
            'email' => fake()->email(),
            'password' => $password,
            'password_confirmation' => $password,
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'phone_number' => fake()->phoneNumber(),
            'birth_day' => fake()->date(),
            'street' => fake()->streetAddress(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'zip_code' => fake()->postcode(),
            'country' => fake()->country(),
        ];
    }
}