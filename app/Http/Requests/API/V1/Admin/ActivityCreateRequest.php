<?php

namespace App\Http\Requests\API\V1\Admin;

use Illuminate\Foundation\Http\FormRequest;


use App\Http\Requests\API\V1\Traits\FailedValidationTrait;
use App\Http\Requests\API\V1\Traits\ArabicValidationTrait;


use Illuminate\Validation\Rule;


class ActivityCreateRequest extends FormRequest
{
    use FailedValidationTrait, ArabicValidationTrait;
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $sectorId = $this->input('sectorId');

        return [
            'sectorId'   => 'required|integer|exists:sectors,id',
            'entityIds' => 'nullable|array',
            'entityIds.*' => 'nullable|integer|exists:entities,id',

            'name' => [
                'required',
                'min:3',
                'max:50',
                'regex:/^[a-zA-Z.,، ]+$/u',
                Rule::unique('activities', 'name')
                    ->where(fn ($q) => $q->where('sectorId', $sectorId)),
            ],

            'nameAr' => self::arabicNameRule(
                Rule::unique('activities', 'nameAr')
                    ->where(fn ($q) => $q->where('sectorId', $sectorId))
            ),
            'status' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'sectorId.required' => 'The sector is required.',
            'sectorId.integer' => 'The sector must be type integer.',
            'sectorId.exists' => 'The sector is invalid.',

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

            // 'entityIds.*.required' => 'The entity is required.',
            'entityIds.*.integer' => 'The entity must be type integer.',
            'entityIds.*.exists' => 'The entity is invalid.',
        ];
    }
}
