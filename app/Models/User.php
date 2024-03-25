<?php

namespace App\Models;

use App\Exceptions\CredentialsNotCorrectException;
use App\Exceptions\UserNotFoundException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'wallet_balance'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getToken(): string
    {
        return $this->createToken('token')->plainTextToken;
    }

    public function authenticate(string $loginData, string $password, string $deviceToken): bool
    {
        $loginField = filter_var($loginData, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone_number';

        if (!auth()->attempt([
            $loginField => strtolower($loginData),
            'password' => $password,
        ])) {
            throw new CredentialsNotCorrectException();
        }

        return auth()->user()->update(['device_token' => $deviceToken]);
    }

    public function register($data): User
    {
        return $this->create($data);
    }

    public function logout(): bool
    {
        return $this->tokens()->delete();
    }

    public function createWallet($data)
    {
        return $this->wallets()->create($data);
    }

    public function wallets(): HasMany
    {
        return $this->hasMany(Wallet::class, 'user_id');
    }
}
