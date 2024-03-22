<?php

namespace App\Http\Requests;

use App\Common\Traits\HasFailedValidationResponse;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'login_data' => 'required',
            'password' => 'required|min:6',
            'device_token' => 'required',
        ];
    }
}
