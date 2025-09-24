<?php

namespace App\Http\Requests\API\V1\Admin;

use Illuminate\Foundation\Http\FormRequest;


use App\Http\Requests\Api\V1\Traits\FailedValidationTrait;

class ActivityCreateRequest extends FormRequest
{
    use FailedValidationTrait;
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sectorId'   => 'required|integer|exists:sectors,id',
            'name'  => 'required|string|max:255',
            'nameAr'  => 'required|string|max:255'
        ];
    }
}
