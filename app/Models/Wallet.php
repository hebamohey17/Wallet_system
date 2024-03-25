<?php

namespace App\Models;

use App\Builders\WalletBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'payment_address';
    }

    public function newEloquentBuilder($query): Builder
    {
        return new WalletBuilder($query);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class, 'wallet_id');
    }

    public function deposit(float $amount): bool
    {
        return $this->increment('balance', $amount);
    }

    public function withdrawal(float $amount): bool
    {
        return $this->decrement('balance', $amount);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
