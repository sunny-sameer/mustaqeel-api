<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Foundation\Http\FormRequest;

use App\Http\Requests\API\V1\Traits\FailedValidationTrait;


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

        $validation = [
            'otp'   => 'required|digits:6',
            'pendingToken'   => 'required|min:10'
        ];

        if (!$data->ok) {
            $validation['email'] = 'required|min:5|max:255|email';
            return $validation;
        }

        if ($data->flow == 'signup') {
            $validation['email'] = 'required|min:5|max:255|email|unique:users,email';
            return $validation;
        }

        $validation['email'] = 'required|min:5|max:255|email|exists:users,email';
        return $validation;
    }

    public function wantsJson(): bool
    {
        return true;
    }
}
