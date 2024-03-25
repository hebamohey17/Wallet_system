<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Enums\RouteName;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\PasswordController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
// Auth
Route::post('register', [RegisterController::class, 'register'])->name(RouteName::REGISTER);
Route::post('login', [LoginController::class, 'login'])->name(RouteName::LOGIN);


Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('logout', [LoginController::class, 'logout'])->name(RouteName::LOGOUT);

    Route::post('wallets/{wallet}/transaction-history', [\App\Http\Controllers\WalletTransactionsController::class, 'transactionHistory'])->name(RouteName::TRANSFER);
    Route::post('wallets/{wallet}/transfer', [\App\Http\Controllers\WalletController::class, 'transfer'])->name(RouteName::TRANSFER);
    Route::post('wallet/check-balance', [\App\Http\Controllers\WalletController::class, 'checkBalance'])->name(RouteName::CHECK_BALANCE);
    Route::post('wallet/deposit', [\App\Http\Controllers\WalletController::class, 'deposit'])->name(RouteName::DEPOSIT);
    Route::post('wallet/withdrawal', [\App\Http\Controllers\WalletController::class, 'withdrawal'])->name(RouteName::WITHDRAWAL);
});

