<?php

namespace App\Http\Requests\API\V1\Admin;


use Illuminate\Foundation\Http\FormRequest;

use App\Http\Requests\Api\V1\Traits\FailedValidationTrait;


class EntityUpdateRequest extends FormRequest
{
    use FailedValidationTrait;

    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'entityEn'   => 'sometimes|string|max:255',
            'entityAr'   => 'sometimes|string|max:255',
            'activityId' => 'sometimes|array',
            'activityId.*' => 'integer|exists:activities,id',
        ];
    }
}
