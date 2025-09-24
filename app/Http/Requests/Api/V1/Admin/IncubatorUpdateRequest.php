<?php

namespace App\Http\Requests\API\V1\Admin;


use Illuminate\Foundation\Http\FormRequest;

use App\Http\Requests\Api\V1\Traits\FailedValidationTrait;


class IncubatorUpdateRequest extends FormRequest
{
    use FailedValidationTrait;

    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'categoryId' => 'sometimes|integer|exists:categories,id',
            'name' => 'sometimes|string|max:255',
            'nameAr' => 'sometimes|string|max:255',
        ];
    }
}
