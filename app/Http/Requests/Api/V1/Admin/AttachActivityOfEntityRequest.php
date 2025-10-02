<?php

namespace App\Http\Requests\Api\V1\Admin;

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
            'activityIds.*.required' => 'The activity ids field is required.',
            'activityIds.*.integer' => 'The activity ids field must be type of integer.',
            'activityIds.*.exists' => 'The selected activity ids is invalid.',
        ];
    }
}
