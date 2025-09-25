<?php

namespace App\Http\Requests\API\V1\Traits;

trait ArabicValidationTrait
{
    /**
     * Returns the validation rule array for Arabic names.
     *
     * @return array
     */
    public static function arabicNameRule(): array
    {
        return [
            'required',
            'min:3',
            'max:255',
            'regex:/^[\p{Arabic}.,، ]+$/u'
        ];
    }
}
