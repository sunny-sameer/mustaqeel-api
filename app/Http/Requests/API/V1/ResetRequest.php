<?php

namespace App\Http\Requests\API\V1;

use App\Http\Requests\API\V1\BaseRequest;
use App\Http\Requests\API\V1\Traits\FailedValidationTrait;


class ResetRequest extends BaseRequest
{
    use FailedValidationTrait;


    /**
     * Authorize the request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules.
     */
    public function rules(): array
    {
        return [
            'email' => 'required|min:5|max:255|email|exists:users,email',
        ];
    }

    /**
     * Always return JSON for APIs.
     */
    public function wantsJson(): bool
    {
        return true;
    }
}
