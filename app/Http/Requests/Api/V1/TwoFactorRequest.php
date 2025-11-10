<?php

namespace App\Http\Requests\API\V1;

use App\Http\Requests\API\V1\BaseRequest;

use App\Models\User;

use App\Http\Requests\API\V1\Traits\FailedValidationTrait;


class TwoFactorRequest extends BaseRequest
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
            'otp.required' => 'Email is required.',
            'otp.min' => 'OTP must be at least 6 characters.',
        ];
    }

    public function wantsJson(): bool
    {
        return true;
    }
}
