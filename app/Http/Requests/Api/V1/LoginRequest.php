<?php

namespace App\Http\Requests\API\V1;

use App\Http\Requests\API\V1\BaseRequest;
use App\Http\Requests\Api\V1\Traits\FailedValidationTrait;


use App\Models\User;


class LoginRequest extends BaseRequest
{

    use FailedValidationTrait;


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return (new user())->loginRules();
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 6 characters.',
        ];
    }

    public function wantsJson(): bool
    {
        return true;
    }
}
