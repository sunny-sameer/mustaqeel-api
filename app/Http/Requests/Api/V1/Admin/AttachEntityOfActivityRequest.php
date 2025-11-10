<?php

namespace App\Http\Requests\API\V1\Admin;

use Illuminate\Foundation\Http\FormRequest;


use App\Http\Requests\API\V1\Traits\FailedValidationTrait;


class AttachEntityOfActivityRequest extends FormRequest
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
            'entityIds' => 'required|array',
            'entityIds.*' => 'required|integer|exists:entities,id',
        ];
    }

    public function messages(): array
    {
        return [
            'entityIds.*.required' => 'The entity ids field is required.',
            'entityIds.*.integer' => 'The entity ids field must be type of integer.',
            'entityIds.*.exists' => 'The selected entity ids is invalid.',
        ];
    }
}
