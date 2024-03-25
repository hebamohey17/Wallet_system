<?php

namespace Feature;

use App\Enums\RouteName;
use App\Enums\TransactionTypeEnum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DepositTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function authenticate($user = null)
    {
        if (!$user) {
            $user = User::factory()->hasWallets()->create();
        }

        return $this->actingAs($user, 'sanctum');
    }
    public function test_users_can_deposit_balance_to_his_wallet()
    {
        $this->authenticate();
        $balance = auth()->user()->wallets()->first()->balance;
        $response = $this->post(route(RouteName::DEPOSIT), [
                    'payment_address' => auth()->user()->wallets()->first()->payment_address,
                    'amount' => 5000
            ],
        );

        $this->assertDatabaseHas('wallet_transactions', [
            'amount' => 5000,
            'type' => TransactionTypeEnum::DEPOSIT,
            'balance_before' => $balance,
            'balance_after' => $balance + 5000
        ]);

        $response->assertSuccessful()
            ->assertJson([
            'data' => [
                'Balance' =>  $balance + 5000,
                'PaymentAddress' => auth()->user()->wallets()->first()->payment_address
            ],
        ]);
    }
    public function test_user_can_deposit_balance_only_his_wallets()
    {
        $this->authenticate();
        $user = User::factory()->hasWallets()->create();
        $balance = auth()->user()->wallets()->first()->balance;
        $response = $this->post(route(RouteName::DEPOSIT), [
            'payment_address' => $user->wallets()->first()->payment_address,
            'amount' => 5000
        ]);

        $response->assertJsonValidationErrorFor('payment_address');
    }
}
