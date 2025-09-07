<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OtpVerifyRequest extends FormRequest
{
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
        return [
            'email' => ['required', 'email', 'exists:users,email'],
            'otp'   => ['required', 'digits:6'], // exactly 6 digits
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

    /**
     * Always return JSON for APIs.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'errors' => $validator->errors()
        ], 422));
    }
}
