<?php

namespace App\Http\Requests\API\V1;

use App\Http\Requests\API\V1\BaseRequest;

use App\Http\Requests\Api\V1\Traits\FailedValidationTrait;


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
            'name' => 'required|string|max:255',
            'nameArabic' => ['nullable', 'regex:/^[ا-يإأءئلأؤۂآلآ()\-\.]+$/u'],
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,64}$/',
            'termsAccepted' => 'required|accepted',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required.',
            'nameArabic.regex' => 'The Arabic name must only contain Arabic characters.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'termsAccepted.required' => 'You must accept the terms.',
            'termsAccepted.accepted' => 'You must accept the terms.',
        ];
    }

    public function wantsJson(): bool
    {
        return true;
    }
}
