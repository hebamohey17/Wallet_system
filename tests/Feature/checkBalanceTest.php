<?php

namespace Feature;

use App\Enums\RouteName;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class checkBalanceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function authenticate($user = null)
    {
        if (!$user) {
            $user = User::factory()->hasWallets()->create();
        }

        return $this->actingAs($user, 'sanctum');
    }
    public function test_users_can_check_balance()
    {
        $this->authenticate();
        $payment_address = auth()->user()->wallets()->first()->payment_address;
        $response = $this->post(route(RouteName::CHECK_BALANCE), [
                    'payment_address' => $payment_address,
            ],
        );

        $this->assertDatabaseHas('wallets', ['payment_address' => $payment_address]);

        $response->assertSuccessful() ->assertJson([
            'data' => [
                'Balance' =>  auth()->user()->wallets()->first()->balance,
                'PaymentAddress' => $payment_address
            ],
        ]);
    }
    public function test_user_can_check_balance_only_his_wallets()
    {

        $this->authenticate();
        $user = User::factory()->hasWallets()->create();
        $payment_address = auth()->user()->wallets()->first()->payment_address;
        $response = $this->post(route(RouteName::CHECK_BALANCE), [
            'payment_address' => $user->wallets()->first()->payment_address,
        ]);

        $response->assertJsonValidationErrorFor('payment_address');
    }
}
