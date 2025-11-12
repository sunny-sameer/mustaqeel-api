<?php

namespace App\Http\Requests\API\V1\Admin;

use Illuminate\Foundation\Http\FormRequest;


use App\Http\Requests\API\V1\Traits\FailedValidationTrait;
use App\Http\Requests\API\V1\Traits\ArabicValidationTrait;


class CategoryUpdateRequest extends FormRequest
{
    use FailedValidationTrait, ArabicValidationTrait;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = (int) $this->route('id');

        return [
            'name' => 'required|string|min:3|max:50|unique:categories,name,'.$id.'|regex:/^[a-zA-Z.,، ]+$/u',
            'nameAr' => self::arabicNameRule('unique:categories,nameAr,'.$id),
            'status' => 'required|boolean',
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
