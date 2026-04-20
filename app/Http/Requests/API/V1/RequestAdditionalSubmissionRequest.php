<?php

namespace App\Http\Requests\API\V1;

use App\Http\Requests\API\V1\Traits\FailedValidationTrait;
use Illuminate\Foundation\Http\FormRequest;

class RequestAdditionalSubmissionRequest extends FormRequest
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
            'quesId' => 'required|integer',
            'answer' => 'required|min:3|max:255|regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u',
            'document' => 'nullable|max:2048|mimes:png,jpeg,jpg,pdf,doc,docx'
        ];
    }

    public function messages(): array
    {
        $messages = [
            'quesId.required' => 'The additional request question id is required.',
            'quesId.integer' => 'The additional request question id must be integer.',

            'answer.required' => 'The questions is required.',
            'answer.min' => 'The questions must be at least 3 characters.',
            'answer.max' => 'The questions may not be greater than 255 characters.',
            'answer.regex' => 'The questions may only contain Arabic, English letters, numbers, commas, and full stop.',

            'document.max' => 'The document may not be greater than 2 MB.',
            'document.mimes' => 'The document must be a PNG, JPG, or JPEG, PDF, DOC, DOCX file.',
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
