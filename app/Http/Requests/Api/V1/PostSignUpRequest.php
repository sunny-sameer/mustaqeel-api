<?php

namespace App\Http\Requests\API\V1;

use App\Http\Requests\API\V1\BaseRequest;

use App\Http\Requests\Api\V1\Traits\FailedValidationTrait;
use Illuminate\Validation\Rules\Password;

class PostSignupRequest
{
    use FailedValidationTrait;


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|min:3|max:50|regex:/^[a-zA-Z.,، ]+$/',
            'nameArabic' => 'nullable|min:3|max:255|regex:/^[\p{Arabic}.,، ]+$/u',
            'email' => 'required|min:5|max:255|email|unique:users,email',
            'password' => [
                'required',
                Password::min(8)
                ->max(64)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
            ],
            'confirmPassword' => 'required|same:password',
            'termsAccepted' => 'required|accepted'
        ];
    }

    public function messages(): array
    {
        return [
            'name.regex' => 'The name field only contains characters, spaces, commas and dots.',
            'nameArabic.regex' => 'The name arabic field only contains arabic letters, spaces, commas and dots.'
        ];
    }

    public function wantsJson(): bool
    {
        return true;
    }
}
