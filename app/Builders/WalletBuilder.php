<?php
namespace App\Builders;

use Illuminate\Database\Eloquent\Builder;

class WalletBuilder extends Builder
{
    public function wherePaymentAddress($paymentAddress): self
    {
        return $this->where('payment_address', $paymentAddress);
    }
}
