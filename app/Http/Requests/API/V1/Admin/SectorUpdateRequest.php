<?php

namespace App\Http\Requests\API\V1\Admin;

use Illuminate\Foundation\Http\FormRequest;


use App\Http\Requests\API\V1\Traits\FailedValidationTrait;
use App\Http\Requests\API\V1\Traits\ArabicValidationTrait;


class SectorUpdateRequest extends FormRequest
{
    use FailedValidationTrait, ArabicValidationTrait;

    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $id = (int) $this->route('secId');

        return [
            'categoryIds' => 'required|array',
            'categoryIds.*' => 'required|integer|exists:categories,id',
            'name' => 'required|min:3|max:50|unique:sectors,name,'.$id.'|regex:/^[a-zA-Z.,، ]+$/u',
            'nameAr' => self::arabicNameRule('unique:sectors,nameAr,'.$id),
            'status' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The english name is required.',
            'name.min' => 'The english name must be at least :min characters.',
            'name.max' => 'The english name may not be greater than :max characters.',
            'name.regex' => 'The english name may only contain letters, commas, full stop, and spaces.',
            'name.unique' => 'The english name has already been taken.',

            'nameAr.required' => 'The arabic name is required.',
            'nameAr.min' => 'The arabic name must be at least :min characters.',
            'nameAr.max' => 'The arabic name may not be greater than :max characters.',
            'nameAr.regex' => 'The arabic name may only contain arabic letters, commas, full stop, and spaces.',
            'nameAr.unique' => 'The arabic name has already been taken.',

            'status.required' => 'The status is required.',
            'status.boolean' => 'The status must be either true or false.',

            'categoryIds.*.required' => 'The category is required.',
            'categoryIds.*.integer' => 'The category must be type integer.',
            'categoryIds.*.exists' => 'The category is invalid.',
        ];
    }
}
