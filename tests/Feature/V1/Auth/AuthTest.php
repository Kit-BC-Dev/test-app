<?php

namespace Tests\Feature\V1\Auth;

use Tests\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    private $endpoint;
    private $user;
    public function setUp(): void
    {
        parent::setUp();
        $this->user = UserFactory::generateUser(fake()->password());
        $this->endpoint = 'api.v1.user.login';
    }
    public function test_user_can_login(): void
    {
        $password = fake()->password();
        $user = UserFactory::generateUser($password);

        $response = $this->postRequest($this->endpoint, 'POST', [
            'email' => $user->email,
            'password' => $password,
        ])->assertStatus(200);

        $response->assertJsonStructure([
            'token',
            'user',
            'message'
        ]);
    }

    public function test_user_cannot_login_with_incorrect_password(): void
    {
        $this->postRequest($this->endpoint, 'POST', [
            'email' => $this->user->email,
            'password' => fake()->password(),
        ])->assertStatus(422);
    }

    public function test_login_validation(): void
    {
        $requiredFields = ['email', 'password'];

        foreach ($requiredFields as $field) {
            $response = $this->postRequest($this->endpoint, 'POST', [
                $field => '',
            ])->assertUnprocessable();

            $response->assertJsonValidationErrorFor($field);
        }

        $response = $this->postRequest($this->endpoint, 'POST', [
            'email' => 'testtest',
            'password' => fake()->password(),
        ])->assertUnprocessable();

        $response->assertJsonValidationErrorFor('email');

        $response = $this->postRequest($this->endpoint, 'POST', [
            'email' => $this->user->email,
            'password' => '123456',
        ])->assertUnprocessable();

        $response->assertJsonValidationErrorFor('password');
    }
}
