<?php

namespace App\Http\Requests\API\V1\Admin;

use Illuminate\Foundation\Http\FormRequest;


use App\Http\Requests\API\V1\Traits\FailedValidationTrait;


class AttachActivityOfEntityRequest extends FormRequest
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
            'activityIds' => 'required|array',
            'activityIds.*' => 'required|integer|exists:activities,id',
        ];
    }

    public function messages(): array
    {
        return [
            'activityIds.*.required' => 'The activity is required.',
            'activityIds.*.integer' => 'The activity must be type integer.',
            'activityIds.*.exists' => 'The activity is invalid.',
        ];
    }
}
