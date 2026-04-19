<?php

namespace App\Http\Requests\API\V1;

use App\Http\Requests\API\V1\Traits\FailedValidationTrait;
use Illuminate\Foundation\Http\FormRequest;

class RequestAdditionalRequest extends FormRequest
{
    use FailedValidationTrait;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'additional' => 'required|array',
            'additional.questions' => 'required|array',
            'additional.questions.*' => 'required|min:3|max:255|regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u'
        ];
    }

    public function messages(): array
    {
        $messages = [
            'additional.required' => 'The additional array is required.',
            'additional.array' => 'The additional must be an array.',

            'additional.questions.required' => 'The questions array is required.',
            'additional.questions.array' => 'The questions must be an array.',

            'additional.questions.*.required' => 'The questions is required.',
            'additional.questions.*.min' => 'The questions must be at least 3 characters.',
            'additional.questions.*.max' => 'The questions may not be greater than 255 characters.',
            'additional.questions.*.regex' => 'The questions may only contain Arabic, English letters, numbers, commas, and full stop.'
        ];

        return $messages;
    }

    /**
     * Always return JSON for APIs.
     */
    public function wantsJson(): bool
    {
        return true;
    }
}
