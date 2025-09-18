<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Foundation\Http\FormRequest;

use App\Http\Requests\Api\V1\Traits\FailedValidationTrait;


class ApiRequest extends FormRequest
{
    use FailedValidationTrait;


    public function wantsJson(): bool
    {
        return true;
    }
}
