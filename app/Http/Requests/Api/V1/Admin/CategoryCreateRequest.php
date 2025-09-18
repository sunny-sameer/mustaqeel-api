<?php

namespace App\Http\Requests\API\V1\Admin;

use Illuminate\Foundation\Http\FormRequest;


use App\Http\Requests\Api\V1\Traits\FailedValidationTrait;
use App\Http\Requests\Api\V1\Traits\ArabicValidationTrait;


use Illuminate\Contracts\Validation\Rule;


class CategoryCreateRequest extends FormRequest
{
    use FailedValidationTrait, ArabicValidationTrait;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'   => 'required|string|max:255',
            'nameAr' => self::arabicNameRule(),
            'status' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'nameAr.regex' => 'The :attribute must contain only Arabic characters, parentheses, hyphens, and dots.',
        ];
    }
}
