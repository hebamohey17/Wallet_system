<?php

namespace Feature;

use App\Enums\RouteName;
use App\Enums\TransactionTypeEnum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WithdrawalTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function authenticate($user = null)
    {
        if (!$user) {
            $user = User::factory()->hasWallets()->create();
        }

        return $this->actingAs($user, 'sanctum');
    }
    public function test_users_can_withdrawal_balance_to_his_wallet()
    {
        $this->authenticate();
        $balance = auth()->user()->wallets()->first()->balance;
        $response = $this->post(route(RouteName::WITHDRAWAL), [
                    'payment_address' => auth()->user()->wallets()->first()->payment_address,
                    'amount' => $balance - 5
            ],
        );

        $this->assertDatabaseHas('wallet_transactions', [
            'amount' => $balance - 5,
            'type' => TransactionTypeEnum::WITHDRAWAL,
            'balance_before' => $balance,
            'balance_after' => 5
        ]);

        $response->assertSuccessful()
            ->assertJson([
            'data' => [
                'Balance' =>  5,
                'PaymentAddress' => auth()->user()->wallets()->first()->payment_address
            ],
        ]);
    }
    public function test_user_can_withdrawal_balance_only_his_wallets()
    {
        $this->authenticate();
        $balance = auth()->user()->wallets()->first()->balance;
        $user = User::factory()->hasWallets()->create();
        $response = $this->post(route(RouteName::WITHDRAWAL), [
            'payment_address' => $user->wallets()->first()->payment_address,
            'amount' => $balance - 5
        ]);

        $response->assertJsonValidationErrorFor('payment_address');
    }

    public function test_user_can_not_withdrawal_balance_bigger_than_his_wallet_balance()
    {
        $this->authenticate();
        $balance = auth()->user()->wallets()->first()->balance;
        $response = $this->post(route(RouteName::WITHDRAWAL), [
            'payment_address' => auth()->user()->wallets()->first()->payment_address,
            'amount' => $balance + 100,
        ]);

        $response->assertJsonValidationErrorFor('amount');
    }
}
