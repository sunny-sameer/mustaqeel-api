<?php

namespace App\Http\Requests\API\V1\Admin;

use Illuminate\Foundation\Http\FormRequest;


use App\Http\Requests\API\V1\Traits\FailedValidationTrait;
use App\Http\Requests\API\V1\Traits\ArabicValidationTrait;


use Illuminate\Validation\Rule;


class FormFieldUpdateRequest extends FormRequest
{
    use FailedValidationTrait, ArabicValidationTrait;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
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
            'identificationData' => 'required|array',
            'formFields' => 'required|array',
            'metaFields' => 'required|array',

            'identificationData.category' => 'required|exists:categories,slug',
            'identificationData.subCategory' => 'nullable|exists:sub_categories,slug',
            'identificationData.sector' => 'nullable|exists:sectors,slug',
            'identificationData.activity' => 'nullable|exists:activities,slug',
            'identificationData.subActivity' => 'nullable|exists:sub_activities,slug',
            'identificationData.entity' => 'nullable|nullable|exists:entities,slug',
            'identificationData.incubator' => 'nullable|exists:incubators,slug',

            'formFields.nameEn' => 'required|min:3|max:255|regex:/^[a-zA-Z0-9.,، ]+$/u',
            'formFields.nameAr' => 'required|min:3|max:255|regex:/^[\p{Arabic}0-9.,، ]+$/u',
            'formFields.type' => 'required|in:text,select,textarea,file,radio,checkbox',
            'formFields.onshoreOffShore' => 'required|in:offshore,onshore,both',
            'formFields.isRequired' => 'required|boolean',
            'formFields.status' => 'required|boolean',

            'metaFields.extensions' => 'required_if:formFields.type,file|nullable|array',
            'metaFields.extensions.*' => 'required_if:formFields.type,file|nullable|in:pdf,jpg,jpeg,png,doc,docx',
        ];
    }

    public function messages(): array
    {
        return [
            'identificationData.required' => 'The identification data array is required.',
            'identificationData.array' => 'The identification data must be an array.',

            'formFields.required' => 'The form fields array is required.',
            'formFields.array' => 'The form fields must be an array.',

            'metaFields.required' => 'The meta fields array is required.',
            'metaFields.array' => 'The meta fields must be an array.',

            'metaFields.extensions.required_if' => 'The extensions are required.',
            'metaFields.extensions.array' => 'The extensions must be an array.',

            'formFields.nameEn.required' => 'The english name is required.',
            'formFields.nameEn.min' => 'The english name must be at least 3 characters.',
            'formFields.nameEn.max' => 'The english name may not be greater than 255 characters.',
            'formFields.nameEn.regex' => 'The english name may only contain letters, commas, full stop, and spaces.',

            'formFields.nameEn.required' => 'The arabic name is required.',
            'formFields.nameAr.min' => 'The arabic name must be at least 3 characters.',
            'formFields.nameAr.max' => 'The arabic name may not be greater than 255 characters.',
            'formFields.nameAr.regex' => 'The arabic name may only contain Arabic letters, commas, full stop, and spaces.',

            'formFields.type.required' => 'The type is required.',
            'formFields.type.in' => 'The type must be one of the following: text, file, radio, checkbox.',

            'formFields.onshoreOffShore.required' => 'The onshore/offShore is required.',
            'formFields.onshoreOffShore.in' => 'The onshore/offShore must be one of the following: offshore, onshore, both.',

            'formFields.isRequired.required' => 'The required is required.',
            'formFields.isRequired.in' => 'The required must be either true or false.',

            'formFields.status.required' => 'The status is required.',
            'formFields.status.in' => 'The status must be either true or false.',

            'metaFields.extensions.*.required_if' => 'The extensions are required.',
            'metaFields.extensions.*.in' => 'The extensions allowed only PDF, JPG, JPEG, PNG, DOC and DOCX.',
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
