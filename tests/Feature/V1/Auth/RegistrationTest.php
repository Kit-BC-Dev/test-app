<?php

namespace Tests\Feature\V1\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Factories\UserFactory;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;
    private $userData;
    private $endpoint;
    private $user;
    public function setUp(): void
    {
        parent::setUp();
        $this->userData = UserFactory::generateDummyDataForUser();
        $this->endpoint = 'api.v1.user.registration';
        $this->user = UserFactory::generateUser();
    }

    public function test_user_can_register(): void
    {
        $this->postRequest(
            $this->endpoint,
            'POST',
            $this->userData   
        )->assertStatus(201);

        $this->assertDatabaseHas('users', [
            'email' => $this->userData['email']
        ]);
        

        $this->assertDatabaseHas('user_information', [
            'first_name' => $this->userData['first_name'],
            'last_name' => $this->userData['last_name']
        ]);
    }

    public function test_user_cannot_register_with_existing_email(): void
    {
        $response = $this->postRequest(
            $this->endpoint,
            'POST',
            $this->userData,
            ['email' => $this->user->email] 
        )->assertStatus(422);

        $response->assertJsonValidationErrorFor('email');

    }

    public function test_user_cannot_register_with_invalid_email_format(): void
    {
        $response = $this->postRequest(
            $this->endpoint,
            'POST',
            $this->userData,
            ['email' => 'invalid_email'] 
        )->assertStatus(422);
        $response->assertJsonValidationErrorFor('email');
    }

    public function test_user_cannot_register_with_short_password(): void
    {
        $response = $this->postRequest(
            $this->endpoint,
            'POST',
            $this->userData,
            ['password' => '123456'] 
        )->assertStatus(422);
        $response->assertJsonValidationErrorFor('password');
    }

    public function test_user_cannot_register_with_unmatching_password_confirmation(): void
    {
        $response = $this->postRequest(
            $this->endpoint,
            'POST',
            $this->userData,
            ['password_confirmation' => '123456'] 
        )->assertStatus(422);
        $response->assertJsonValidationErrorFor('password');
    }


    public function test_user_cannot_register_with_incomplete_data(): void
    {
        $requiredFields = [
            'email',
            'password',
            'first_name',
            'last_name',
            'phone_number',
            'birth_day',
            'street',
            'city',
            'state',
            'country',
            'zip_code',
        ];
        
        foreach ($requiredFields as $field) {
            $response = $this->postRequest(
                $this->endpoint,
                'POST',
                $this->userData,
                [$field => ''] 
            )->assertStatus(422);

            $response->assertJsonValidationErrorFor($field);
        }

    }

}
