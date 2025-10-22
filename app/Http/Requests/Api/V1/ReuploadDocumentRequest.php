<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\API\V1\Traits\FailedValidationTrait;
use Illuminate\Foundation\Http\FormRequest;

class ReuploadDocumentRequest extends FormRequest
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
            'reuploadDocument' => 'required|array',
            'reuploadDocument.*.type' => 'required|exists:form_fields,slug',
            'reuploadDocument.*.commentsEn' => 'required|min:3|max:800|regex:/^[a-zA-Z0-9.,، ]+$/u',
            'reuploadDocument.*.commentsAr' => 'nullable|max:800|regex:/^[\p{Arabic}0-9.,، ]+$/u',
        ];
    }


    /**
     * Get the validation messages that apply to the request.
    */
    public function messages(): array
    {
        return [
            'reuploadDocument.required' => 'The reupload document array is required.',
            'reuploadDocument.array' => 'The reupload document must be an array.',

            'reuploadDocument.*.type.required' => 'The type is required.',
            'reuploadDocument.*.type.exists' => 'The type is invalid.',

            'reuploadDocument.*.commentsEn.required' => 'The english comments is required.',
            'reuploadDocument.*.commentsEn.min' => 'The english comments must be at least 3 characters.',
            'reuploadDocument.*.commentsEn.max' => 'The english comments may not be greater than 800 characters.',
            'reuploadDocument.*.commentsEn.regex' => 'The english comments may only contain English letters, commas, full stop, and spaces.',

            'reuploadDocument.*.commentsAr.max' => 'The arabic comments may not be greater than 800 characters.',
            'reuploadDocument.*.commentsAr.regex' => 'The arabic comments may only contain Arabic letters, commas, full stop, and spaces.',
        ];
    }

    /**
     * Always return JSON for APIs.
     */
    public function wantsJson(): bool
    {
        return true;
    }
}
