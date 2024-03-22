<?php

namespace App\Http\Requests;

use App\Enums\Message;
use App\Common\Traits\HasFailedValidationResponse;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'email' => 'required|string|email|unique:users,email',
            'phone_number' => 'required|string|regex:/^(?:[+0][1-9])?[0-9]{10,11}$/|unique:users,phone_number',
            'password' => 'required|min:6',
            'device_token' => 'required',
            'name' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'phone_number.regex' => trans(Message::PHONE_SHOULD_NOT_CONTAIN_LETTERS),
        ];
    }
}