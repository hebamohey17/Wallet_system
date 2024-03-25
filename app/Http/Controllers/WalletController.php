<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Wallet;
use App\Common\Response;
use App\Http\Resources\WalletResource;
use App\Http\Requests\TransferRequest;
use App\Http\Requests\CheckBalanceRequest;
use App\Http\Services\WalletDepositService;
use App\Http\Requests\WalletDepositRequest;
use App\Http\Services\WalletTransferService;
use App\Http\Requests\WalletWithdrawalRequest;
use App\Http\Services\WalletWithdrawalService;

class WalletController extends Controller
{
    public function checkBalance(CheckBalanceRequest $request)
    {
        return (new Response())->success(
            new WalletResource(auth()->user()->wallets()->wherePaymentAddress($request->payment_address)->first())
        );
    }

    public function deposit(WalletDepositRequest $request, WalletDepositService $walletDepositService)
    {
        try {
            $wallet = $walletDepositService->deposit($request->validated());

            return (new Response())->success(new WalletResource($wallet));
        }  catch (Exception $exception) {
            return (new Response())->error('The transaction has not been accepted', $exception->getMessage());
        }
    }

    public function withdrawal(WalletWithdrawalRequest $request, WalletWithdrawalService $walletWithdrawalService)
    {
        try {
        $wallet = $walletWithdrawalService->withdrawal($request->validated());

        return (new Response())->success(new WalletResource($wallet));
        }  catch (Exception $exception) {
            return (new Response())->error('The transaction has not been accepted', $exception->getMessage());
        }
    }

    public function transfer(TransferRequest $request, Wallet $wallet, WalletTransferService $walletTransferService)
    {
        $walletTransferService->Transfer($request->validated(), $wallet);
        return (new Response())->success();
    }
}
