<?php

namespace App\Http\Requests\API\V1\Admin;


use Illuminate\Foundation\Http\FormRequest;

use App\Http\Requests\Api\V1\Traits\FailedValidationTrait;


class EntityCreateRequest extends FormRequest
{
    use FailedValidationTrait;

    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'entityEn'   => 'required|string|max:255',
            'entityAr'   => 'required|string|max:255',
            'activityId' => 'nullable|array',
            'activityId.*' => 'integer|exists:activities,id',
        ];
    }
}
