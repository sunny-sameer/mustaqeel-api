<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Foundation\Http\FormRequest;

use App\Http\Requests\Api\V1\Traits\FailedValidationTrait;


use App\Services\V1\Auth\TwoFactorService;


class OtpVerifyRequest extends FormRequest
{
    use FailedValidationTrait;


    public function __construct(private TwoFactorService $twoFactor) {}

    /**
     * Authorize the request.
     */
    public function authorize(): bool
    {
        // You may add role/user logic here if needed
        return true;
    }

    /**
     * Validation rules.
     */
    public function rules(): array
    {
        $data = $this->twoFactor->verifyEmailData($this->input('pendingToken'));


        if (!$data->ok) {
            return [
                'email' => ['required', 'email'],
                'otp'   => ['required', 'digits:6'],
                'pendingToken'   => ['required']
            ];
        }

        if ($data->flow == 'signup') {
            return [
                'email' => ['required', 'email'],
                'otp'   => ['required', 'digits:6'],
                'pendingToken'   => ['required']
            ];
        }

        return [
            'email' => ['required', 'email', 'exists:users,email'],
            'otp'   => ['required', 'digits:6'],
            'pendingToken'   => ['required']
        ];
    }

    /**
     * Custom error messages.
     */
    public function messages(): array
    {
        return [
            'email.required' => 'Email is required.',
            'email.email'    => 'Please provide a valid email.',
            'email.exists'   => 'No user found with this email.',
            'otp.required'   => 'OTP is required.',
            'otp.digits'     => 'OTP must be exactly 6 digits.',
            'pendingToken.required'   => 'Pending Token is required.',

        ];
    }

}
