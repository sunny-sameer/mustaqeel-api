<?php

namespace App\Http\Requests\API\V1;

use App\Http\Requests\API\V1\Traits\FailedValidationTrait;
use Illuminate\Foundation\Http\FormRequest;

class RequestsQualityCheck extends FormRequest
{
    use FailedValidationTrait;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'requestId' => 'required|exists:requests,id',

            'qcChecks' => 'required|array|min:1',
            'qcChecks.*.fieldName' => 'required|string|max:255',
            'qcChecks.*.fieldOldValue' => 'required|string|max:400|regex:/^[\p{Arabic}a-zA-Z0-9.,، ]+$/u',
            'qcChecks.*.fieldPath' => 'required|string|max:400',
            'qcChecks.*.status' => 'required|in:Correct,Wrong,NeedCorrection',
            'qcChecks.*.commentsEn' => 'nullable|string|max:400|regex:/^[a-zA-Z0-9.,، ]+$/u',
            'qcChecks.*.commentsAr' => 'nullable|string|max:400|regex:/^[\p{Arabic}0-9.,، ]+$/u',
            'qcChecks.*.corrections' => 'nullable|array',
            'qcChecks.*.corrections.*' => 'nullable|string|max:400',

            'descriptionEn' => 'nullable|string|max:400|regex:/^[a-zA-Z0-9.,، ]+$/u',
            'descriptionAr' => 'nullable|string|max:400|regex:/^[\p{Arabic}0-9.,، ]+$/u',
        ];
    }

    public function messages(): array
    {
        return [
            'requestId.required' => 'The request id is required.',
            'requestId.exists' => 'The request id is invalid.',

            'qcChecks.required' => 'The qc checks array is required.',
            'qcChecks.array' => 'The qc checks must be an array.',
            'qcChecks.min' => 'The qc checks must be at least 1 value.',

            'qcChecks.*.fieldName.required' => 'The field name is required.',
            'qcChecks.*.fieldName.string' => 'The field name must be a string.',
            'qcChecks.*.fieldName.max' => 'The field name may not be greater than 255 characters.',

            'qcChecks.*.fieldOldValue.required' => 'The field old value is required.',
            'qcChecks.*.fieldOldValue.string' => 'The field old value must be a string.',
            'qcChecks.*.fieldOldValue.max' => 'The field old value may not be greater than 400 characters.',
            'qcChecks.*.fieldOldValue.regex' => 'The field old value may only contain letters, Arabic letters, numbers, commas, full stop, and spaces.',

            'qcChecks.*.fieldPath.required' => 'The field path is required.',
            'qcChecks.*.fieldPath.string' => 'The field path must be a string.',
            'qcChecks.*.fieldPath.max' => 'The field path may not be greater than 400 characters.',

            'qcChecks.*.status.required' => 'The status is required.',
            'qcChecks.*.status.in' => 'The status must be one the following: Correct, Wrong, NeedCorrection.',

            'qcChecks.*.commentsEn.string' => 'The comments english must be a string.',
            'qcChecks.*.commentsEn.max' => 'The comments english may not be greater than 400 characters.',
            'qcChecks.*.commentsEn.regex' => 'The comments english may only contain letters, numbers, commas, full stop, and spaces.',

            'qcChecks.*.commentsAr.string' => 'The comments arabic must be a string.',
            'qcChecks.*.commentsAr.max' => 'The comments arabic may not be greater than 400 characters.',
            'qcChecks.*.commentsAr.regex' => 'The comments arabic may only contain letters, numbers, commas, full stop, and spaces.',

            'qcChecks.*.corrections.array' => 'The corrections must be an array.',

            'qcChecks.*.corrections.*.string' => 'The corrections must be a string.',
            'qcChecks.*.corrections.max' => 'The corrections may not be greater than 400 characters.',

            'descriptionEn.string' => 'The description english must be a string.',
            'descriptionEn.max' => 'The description english may not be greater than 400 characters.',
            'descriptionEn.regex' => 'The description english may only contain letters, numbers, commas, full stop, and spaces.',

            'descriptionAr.string' => 'The description arabic must be a string.',
            'descriptionAr.max' => 'The description arabic may not be greater than 400 characters.',
            'descriptionAr.regex' => 'The description arabic may only contain letters, numbers, commas, full stop, and spaces.',
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
