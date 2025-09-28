<?php

namespace App\Http\Requests\API\V1\Admin;

use Illuminate\Foundation\Http\FormRequest;


use App\Http\Requests\Api\V1\Traits\FailedValidationTrait;
use App\Http\Requests\API\V1\Traits\ArabicValidationTrait;


class SectorUpdateRequest extends FormRequest
{
    use FailedValidationTrait, ArabicValidationTrait;

    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $id = (int) $this->route('id');

        return [
            'categoryIds' => 'required|array',
            'categoryIds.*' => 'required|integer|exists:categories,id',
            'name' => 'required|string|min:3|max:50|unique:sectors,name,'.$id.'|regex:/^[a-zA-Z.,، ]+$/',
            'nameAr' => self::arabicNameRule('unique:sectors,nameAr,'.$id),
        ];
    }

    public function messages(): array
    {
        return [
            'name.regex' => 'The :attribute field only contains characters, spaces, commas and dots.',
            'nameAr.regex' => 'The :attribute field only contains arabic letters, spaces, commas and dots.',
            'categoryIds.*.required' => 'The category ids field is required.',
            'categoryIds.*.integer' => 'The category ids field must be type of integer.',
            'categoryIds.*.exists' => 'The selected category ids is invalid.',
        ];
    }
}
