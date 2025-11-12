<?php

namespace App\Http\Requests\API\V1\Admin;

use Illuminate\Foundation\Http\FormRequest;


use App\Http\Requests\API\V1\Traits\FailedValidationTrait;
use App\Http\Requests\API\V1\Traits\ArabicValidationTrait;


class EntityCreateRequest extends FormRequest
{
    use FailedValidationTrait, ArabicValidationTrait;

    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'activityIds' => 'required|array',
            'activityIds.*' => 'required|integer|exists:activities,id',

            'name' => 'required|string|min:3|max:50|unique:entities,name|regex:/^[a-zA-Z.,، ]+$/u',
            'nameAr' => self::arabicNameRule('unique:entities,nameAr'),
            'status' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.regex' => 'The :attribute field only contains characters, spaces, commas and dots.',
            'nameAr.regex' => 'The :attribute field only contains arabic letters, spaces, commas and dots.',
            'activityIds.*.required' => 'The activity ids field is required.',
            'activityIds.*.integer' => 'The activity ids field must be type of integer.',
            'activityIds.*.exists' => 'The selected activity ids is invalid.',
        ];
    }
}
