<?php

namespace App\Http\Requests;

use App\Common\Traits\HasFailedValidationResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class WalletWithdrawalRequest extends FormRequest
{
    use HasFailedValidationResponse;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'amount' => [
                'required',
                function ($attribute, $value, $fail) {
                    $user = Auth::user();
                    $wallet = $user->wallets()->where('payment_address', $this->payment_address)->first();
                    if (!$wallet) {
                        $fail('The selected payment address is not your wallet or doesnot exists.');
                    }

                    if ($value <= 0 || $value > $wallet?->balance) {
                        $fail('The amount must be greater than 0 and less than or equal to the wallet balance.');
                    }
                },
            ],
            'payment_address' => [
                'required',
                function ($attribute, $value, $fail) {
                    $user = Auth::user();

                    if (!$user->wallets()->where('payment_address', $value)->exists()) {
                        $fail('The selected payment address is not your wallet.');
                    }
                },
            ],
        ];
    }
}
