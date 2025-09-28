<?php

namespace App\Http\Requests\API\V1\Admin;

use Illuminate\Foundation\Http\FormRequest;


use App\Http\Requests\Api\V1\Traits\FailedValidationTrait;
use App\Http\Requests\API\V1\Traits\ArabicValidationTrait;


use Illuminate\Validation\Rule;


class SubCategoryUpdateRequest extends FormRequest
{
    use FailedValidationTrait, ArabicValidationTrait;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $categoryId = $this->input('categoryId');
        $id = (int) $this->route('id');
        return [
            'categoryId' => 'required|integer|exists:categories,id',
            'name' => ['required','string','min:3','max:50','regex:/^[a-zA-Z.,، ]+$/',
                Rule::unique('sub_categories', 'name')->ignore($id)->where(function ($query) use ($categoryId) {
                    return $query->where('categoryId', $categoryId);
                })
            ],
            'nameAr' => self::arabicNameRule(Rule::unique('sub_categories', 'nameAr')->ignore($id)->where(function ($query) use ($categoryId) {
                    return $query->where('categoryId', $categoryId);
                })
            ),
            'status' => 'nullable|boolean',
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
