<?php

namespace App\Http\Requests\API\V1\Admin;


use Illuminate\Foundation\Http\FormRequest;

use App\Http\Requests\Api\V1\Traits\FailedValidationTrait;


class SubCategoryCreateRequest extends FormRequest
{
    use FailedValidationTrait;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'categoryId' => 'required|integer|exists:categories,id',
            'name'        => 'required|string|max:255',
            'nameAr'      => 'required|string|max:255',
        ];
    }
}
