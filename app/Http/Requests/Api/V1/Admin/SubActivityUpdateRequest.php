<?php

namespace App\Http\Requests\API\V1\Admin;

use Illuminate\Foundation\Http\FormRequest;


use App\Http\Requests\Api\V1\Traits\FailedValidationTrait;
use App\Http\Requests\API\V1\Traits\ArabicValidationTrait;


use Illuminate\Validation\Rule;


class SubActivityUpdateRequest extends FormRequest
{
    use FailedValidationTrait, ArabicValidationTrait;

    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $id = (int) $this->route('id');
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
                    ->ignore($id)
                    ->where(fn ($q) => $q->where('activityId', $activityId)),
            ],

            'nameAr' => self::arabicNameRule(
                Rule::unique('sub_activities', 'nameAr')
                    ->ignore($id)
                    ->where(fn ($q) => $q->where('activityId', $activityId))
            ),
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
