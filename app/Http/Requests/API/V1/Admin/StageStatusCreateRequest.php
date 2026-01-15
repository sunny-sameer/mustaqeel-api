<?php

namespace App\Http\Requests\API\V1\Admin;

use Illuminate\Foundation\Http\FormRequest;


use App\Http\Requests\API\V1\Traits\FailedValidationTrait;
use App\Http\Requests\API\V1\Traits\ArabicValidationTrait;


use Illuminate\Validation\Rule;


class StageStatusCreateRequest extends FormRequest
{
    use FailedValidationTrait, ArabicValidationTrait;

    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $stageId = $this->input('stageId');
        return [
            'stageId'   => 'required|integer|exists:stages,id',
            'name' => [
                'required',
                'min:3',
                'max:50',
                'regex:/^[a-zA-Z.,، ]+$/u',
                Rule::unique('stages_statuses', 'name')
                    ->where(fn ($q) => $q->where('stageId', $stageId)),
            ],

            'nameAr' => self::arabicNameRule(
                Rule::unique('stages_statuses', 'nameAr')
                    ->where(fn ($q) => $q->where('stageId', $stageId))
            ),
            'status' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'stageId.required' => 'The stage is required.',
            'stageId.integer' => 'The stage must be type integer.',
            'stageId.exists' => 'The stage is invalid.',

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
        ];
    }
}
