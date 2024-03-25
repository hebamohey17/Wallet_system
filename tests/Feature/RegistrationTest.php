<?php

namespace Feature;

use App\Enums\RouteName;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_new_users_can_register()
    {
        $name = $this->faker->name;
        $email = $this->faker->email;
        $payment_address = $this->faker->unique()->userName;
        $response = $this->post(route(RouteName::REGISTER), [
                'user' => [
                    'name' => $name,
                    'email' => $email,
                    'password' => $this->faker->password,
                    'device_token' => Str::random(40),
                    'phone_number' => '011111111111',
                ],
                'wallet' => [
                    'payment_address' => $payment_address,
                ]
            ],
        );

        $this->assertDatabaseHas('users', ['email' => $email]);
        $this->assertDatabaseHas('wallets', ['payment_address' => $payment_address]);
        $response->assertSuccessful();
    }

    public function test_register_validation_of_payment_address()
    {
        $name = $this->faker->name;
        $email = $this->faker->email;
        $response = $this->post(route(RouteName::REGISTER), [
            'user' => [
                'name' => $name,
                'email' => $email,
                'password' => $this->faker->password,
                'device_token' => Str::random(40),
                'phone_number' => '011111111111',
            ],
        ],
        );

        $response->assertJsonValidationErrorFor('wallet.payment_address');
    }

    public function test_register_email_validation()
    {
        $name = $this->faker->name;
        $email = $this->faker->email;
        $payment_address = $this->faker->unique()->userName;
        $response = $this->post(route(RouteName::REGISTER), [
            'user' => [
                'name' => $name,
                'password' => $this->faker->password,
                'device_token' => Str::random(40),
                'phone_number' => '011111111111',
            ],
            'wallet' => [
                'payment_address' => $payment_address,
            ]
        ],
        );

        $response->assertJsonValidationErrorFor('user.email');
    }
}
