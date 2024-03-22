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
Route::post('password/forget', [PasswordController::class, 'forget'])->name(RouteName::FORGET_PASSWORD);
Route::post('password/reset', [PasswordController::class, 'reset'])->name(RouteName::RESET_PASSWORD);


Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('logout', [LoginController::class, 'logout'])->name(RouteName::LOGOUT);
});

