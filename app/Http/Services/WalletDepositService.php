<?php

namespace App\Http\Services;


use App\Enums\TransactionTypeEnum;
use App\Enums\WalletTransactionOperatorEnum;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WalletDepositService
{
    public function deposit($data)
    {
        $refNumber = Str::uuid()->toString();
        $wallet = $this->getWallet($data['payment_address']);
        DB::transaction(function () use($data, $refNumber, $wallet) {
            $this->AddDepositTransaction($wallet, $data['amount'], $data['payment_address'], $refNumber);
            $wallet->deposit($data['amount']);
        });
        $wallet->refresh();

        return $wallet;
    }

    public function getWallet($payment_address)
    {
        return Wallet::wherePaymentAddress($payment_address)->first();
    }
    public function AddDepositTransaction($wallet, $amount, $PaymentAddress, $refNumber)
    {

        return $wallet->transactions()->create([
            'balance_before' => $wallet->balance,
            'balance_after' => $wallet->balance + $amount,
            'amount'  => $amount,
            'operator' => WalletTransactionOperatorEnum::PLUS,
            'type' => TransactionTypeEnum::DEPOSIT,
            'Participant' => $PaymentAddress,
            'ref_number' => $refNumber
        ]);
    }
}
