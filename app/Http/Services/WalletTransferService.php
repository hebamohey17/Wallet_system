<?php

namespace App\Http\Services;


use App\Exceptions\WalletBalanceException;
use App\Models\Wallet;
use Illuminate\Support\Str;
use App\Enums\TransactionTypeEnum;
use Illuminate\Support\Facades\DB;
use App\Enums\WalletTransactionOperatorEnum;

class WalletTransferService
{
    public function Transfer($data, $wallet)
    {
        $fees = $this->calculateFees($data['amount']);
        $receiverWallet = Wallet::wherePaymentAddress($data['payment_address'])->first();
        $refNumber = Str::uuid()->toString();

        if ($wallet->balance < $data['amount'] + $fees ) {
            throw new WalletBalanceException();
        }

        DB::transaction(function () use($data, $wallet, $fees, $receiverWallet, $refNumber) {
            $this->addSenderTransaction($wallet, $data['amount'], $receiverWallet->payment_address, $refNumber);
            $this->addResceiverTransaction($receiverWallet, $data['amount'], $wallet->payment_address, $refNumber);
            if ($fees) {
                $this->addFeesTransaction($wallet, $fees, $receiverWallet->payment_address, $refNumber);
            }

            $wallet->withdrawal($data['amount'] + $fees);
            $receiverWallet->deposit($data['amount']);
        });

        return true;
    }

    public function addSenderTransaction($wallet, $amount, $receiverPaymentAddress, $refNumber)
    {
        return $wallet->transactions()->create([
            'balance_before' => $wallet->balance,
            'balance_after' => $wallet->balance - $amount,
            'amount'  => $amount,
            'operator' => WalletTransactionOperatorEnum::MINUS,
            'type' => TransactionTypeEnum::Send,
            'Participant' => $receiverPaymentAddress,
            'ref_number' => $refNumber
        ]);
    }

    public function addResceiverTransaction($receiverWallet, $amount, $senderPaymentAddress, $refNumber)
    {
        return $receiverWallet->transactions()->create([
            'balance_before' => $receiverWallet->balance,
            'balance_after' => $receiverWallet->balance + $amount,
            'amount'  => $amount,
            'operator' => WalletTransactionOperatorEnum::PLUS,
            'type' => TransactionTypeEnum::RESCEIVE,
            'Participant' => $senderPaymentAddress,
            'ref_number' => $refNumber
        ]);
    }

    public function addFeesTransaction($Wallet, $amount, $senderPaymentAddress, $refNumber)
    {
        return $Wallet->transactions()->create([
            'balance_before' => $Wallet->balance,
            'balance_after' => $Wallet->balance - $amount,
            'amount'  => $amount,
            'operator' => WalletTransactionOperatorEnum::MINUS,
            'type' => TransactionTypeEnum::FEES,
            'Participant' => $senderPaymentAddress,
            'ref_number' => $refNumber
        ]);
    }

    public function calculateFees($amount): float
    {
        $fees = 0;
        if ($amount > config('fees.transfer_fees_limit')) {
            $fees = (config('fees.transfer_fees') + ($amount * config('fees.transfer_fees_percentage')/100));
        }

        return $fees;
    }
}
