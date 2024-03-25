<?php

namespace App\Http\Requests;

use App\Enums\Message;
use App\Common\Traits\HasFailedValidationResponse;
use Illuminate\Foundation\Http\FormRequest;

class TransferRequest extends FormRequest
{
    use HasFailedValidationResponse;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = auth()->user();
        $wallet = $this->route('wallet');

        if ($wallet->user->is($user)) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'payment_address' => 'required|exists:wallets,payment_address',
            'amount' => 'required',
        ];
    }
}
