<?php

namespace App\Http\Requests\API\V1\Admin;

use Illuminate\Foundation\Http\FormRequest;


use App\Http\Requests\Api\V1\Traits\FailedValidationTrait;
use App\Http\Requests\API\V1\Traits\ArabicValidationTrait;


use Illuminate\Validation\Rule;


class SubActivityCreateRequest extends FormRequest
{
    use FailedValidationTrait, ArabicValidationTrait;

    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $activityId = $this->input('activityId');

        return [
            'activityId'   => 'required|integer|exists:activities,id',

            'name' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'regex:/^[a-zA-Z.,، ]+$/',
                Rule::unique('sub_activities', 'name')
                    ->where(fn ($q) => $q->where('activityId', $activityId)),
            ],

            'nameAr' => self::arabicNameRule(
                Rule::unique('sub_activities', 'nameAr')
                    ->where(fn ($q) => $q->where('activityId', $activityId))
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
