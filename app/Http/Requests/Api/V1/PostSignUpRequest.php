<?php

namespace App\Http\Requests\API\V1;

use App\Http\Requests\API\V1\BaseRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PostSignupRequest
{
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

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'errors' => $validator->errors()
        ], 422));
    }
}
