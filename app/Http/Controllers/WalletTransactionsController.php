<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Common\Response;
use App\Http\Resources\WalletTransactionCollection;

class WalletTransactionsController extends Controller
{
    public function transactionHistory(Wallet $wallet)
    {
        return (new Response())->success((new WalletTransactionCollection($wallet->transactions()->latest()->paginate(5))));
    }
}
