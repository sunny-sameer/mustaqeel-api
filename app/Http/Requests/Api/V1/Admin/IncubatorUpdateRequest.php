<?php

namespace App\Http\Requests\API\V1\Admin;

use Illuminate\Foundation\Http\FormRequest;


use App\Http\Requests\Api\V1\Traits\FailedValidationTrait;
use App\Http\Requests\API\V1\Traits\ArabicValidationTrait;


use Illuminate\Validation\Rule;


class IncubatorUpdateRequest extends FormRequest
{
    use FailedValidationTrait, ArabicValidationTrait;

    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $id = (int) $this->route('id');
        $categoryId = $this->input('categoryId');

        return [
            'categoryId' => 'required|integer|exists:categories,id',
            'name' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'regex:/^[a-zA-Z.,، ]+$/',
                Rule::unique('incubators', 'name')
                    ->ignore($id)
                    ->where(fn ($q) => $q->where('categoryId', $categoryId)),
            ],

            'nameAr' => self::arabicNameRule(
                Rule::unique('incubators', 'nameAr')
                    ->ignore($id)
                    ->where(fn ($q) => $q->where('categoryId', $categoryId))
            ),
        ];
    }

    public function messages(): array
    {
        return [
            'name.regex' => 'The :attribute field only contains characters, spaces, commas and dots.',
            'nameAr.regex' => 'The :attribute field only contains arabic letters, spaces, commas and dots.',
        ];
    }
}
