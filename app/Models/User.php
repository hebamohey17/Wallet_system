<?php

namespace App\Models;

use App\Exceptions\CredentialsNotCorrectException;
use App\Exceptions\UserNotFoundException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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

    public function register($email, $password, $deviceToken, $phoneNumber, $name): User
    {
        return $this->create([
            'email' => $email,
            'phone_number' => $phoneNumber,
            'password' => Hash::make($password),
            'device_token' => $deviceToken,
            'name' => $name,
        ]);
    }

    public function logout(): bool
    {
        return $this->tokens()->delete();
    }
}
