<?php

namespace App\Common\Traits;

use App\Common\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

trait HasFailedValidationResponse
{
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException((new Response())->validationError('inputErrors', $validator));
    }
}
