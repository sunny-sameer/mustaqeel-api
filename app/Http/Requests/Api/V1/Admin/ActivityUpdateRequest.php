<?php

namespace App\Http\Requests\API\V1\Admin;


use Illuminate\Foundation\Http\FormRequest;

use App\Http\Requests\Api\V1\Traits\FailedValidationTrait;


class ActivityUpdateRequest extends FormRequest
{
    use FailedValidationTrait;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sectorId'   => 'sometimes|integer|exists:sectors,id',
            'activityEn'  => 'sometimes|string|max:255',
            'activityAr'  => 'sometimes|string|max:255',
            'entityIds'   => 'sometimes|array',
            'entityIds.*' => 'integer|exists:entities,id',
        ];
    }
}
