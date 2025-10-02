<?php

namespace App\Http\Requests\API\V1\Admin;

use Illuminate\Foundation\Http\FormRequest;


use App\Http\Requests\Api\V1\Traits\FailedValidationTrait;
use App\Http\Requests\API\V1\Traits\ArabicValidationTrait;


use Illuminate\Validation\Rule;


class ActivityUpdateRequest extends FormRequest
{
    use FailedValidationTrait, ArabicValidationTrait;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = (int) $this->route('id');
        $sectorId = $this->input('sectorId');

        return [
            'sectorId'   => 'required|integer|exists:sectors,id',
            // 'entityIds'  => 'required|array',
            // 'entityIds.*'=> 'required|integer|exists:entities,id',

            'name' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'regex:/^[a-zA-Z.,، ]+$/',
                Rule::unique('activities', 'name')
                    ->ignore($id)
                    ->where(fn ($q) => $q->where('sectorId', $sectorId)),
            ],

            'nameAr' => self::arabicNameRule(
                Rule::unique('activities', 'nameAr')
                    ->ignore($id)
                    ->where(fn ($q) => $q->where('sectorId', $sectorId))
            ),
            'status' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.regex' => 'The :attribute field only contains characters, spaces, commas and dots.',
            'nameAr.regex' => 'The :attribute field only contains arabic letters, spaces, commas and dots.',
            // 'entityIds.*.required' => 'The entity ids field is required.',
            // 'entityIds.*.integer' => 'The entity ids field must be type of integer.',
            // 'entityIds.*.exists' => 'The selected entity ids is invalid.',
        ];
    }
}
