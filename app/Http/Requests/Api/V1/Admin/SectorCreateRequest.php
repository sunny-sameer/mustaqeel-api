<?php

namespace App\Http\Requests\API\V1\Admin;


use Illuminate\Foundation\Http\FormRequest;

use App\Http\Requests\Api\V1\Traits\FailedValidationTrait;


class SectorCreateRequest extends FormRequest
{
    use FailedValidationTrait;

    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:255',
            'nameAr'      => 'required|string|max:255',
            'categoryId' => 'nullable|array',
            'categoryId.*' => 'integer|exists:categories,id',
        ];
    }
}
