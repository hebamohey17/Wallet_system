<?php

namespace App\Http\Services;


use App\Enums\TransactionTypeEnum;
use App\Enums\WalletTransactionOperatorEnum;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WalletWithdrawalService
{
    public function withdrawal($data)
    {
        $refNumber = Str::uuid()->toString();
        $wallet = $this->getWallet($data['payment_address']);
        DB::transaction(function () use($data, $refNumber, $wallet) {
            $this->AddWithdrawalTransaction($wallet, $data['amount'], $data['payment_address'], $refNumber);
            $wallet->withdrawal($data['amount']);
        });
        $wallet->refresh();

        return $wallet;
    }

    public function getWallet($payment_address)
    {
        return Wallet::wherePaymentAddress($payment_address)->first();
    }
    public function AddWithdrawalTransaction($wallet, $amount, $PaymentAddress, $refNumber)
    {

        return $wallet->transactions()->create([
            'balance_before' => $wallet->balance,
            'balance_after' => $wallet->balance - $amount,
            'amount'  => $amount,
            'operator' => WalletTransactionOperatorEnum::MINUS,
            'type' => TransactionTypeEnum::WITHDRAWAL,
            'Participant' => $PaymentAddress,
            'ref_number' => $refNumber
        ]);
    }
}
