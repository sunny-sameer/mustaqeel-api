<?php

namespace App\Http\Requests\API\V1\Admin;


use Illuminate\Foundation\Http\FormRequest;

use App\Http\Requests\Api\V1\Traits\FailedValidationTrait;


class IncubatorCreateRequest extends FormRequest
{
    use FailedValidationTrait;

    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'categoryId' => 'required|integer|exists:categories,id',
            'incubatorEn' => 'required|string|max:255',
            'incubatorAr' => 'required|string|max:255',
        ];
    }
}
