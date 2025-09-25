<?php

namespace App\Http\Requests\API\V1\Admin;


use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Api\V1\Traits\FailedValidationTrait;


class SubActivityCreateRequest extends FormRequest
{
    use FailedValidationTrait;

    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'activityId'   => 'required|integer|exists:activities,id',
            'subActivityEn' => 'required|string|max:255',
            'subActivityAr' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'subActivityEn.regex' => 'The :attribute field only contains characters, spaces, commas and dots.',
            'subActivityAr.regex' => 'The :attribute field only contains arabic letters, spaces, commas and dots.',
        ];
    }
}
