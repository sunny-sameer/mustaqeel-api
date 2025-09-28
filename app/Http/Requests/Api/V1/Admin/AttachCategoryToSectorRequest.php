<?php

namespace App\Http\Requests\API\V1\Admin;

use Illuminate\Foundation\Http\FormRequest;


use App\Http\Requests\API\V1\Traits\FailedValidationTrait;


class AttachCategoryToSectorRequest extends FormRequest
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
            'categoryIds' => 'required|array',
            'categoryIds.*' => 'required|integer|exists:categories,id',
        ];
    }

    public function messages(): array
    {
        return [
            'categoryIds.*.required' => 'The category ids field is required.',
            'categoryIds.*.integer' => 'The category ids field must be type of integer.',
            'categoryIds.*.exists' => 'The selected category ids is invalid.',
        ];
    }
}
