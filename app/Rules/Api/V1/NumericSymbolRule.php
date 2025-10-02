<?php

namespace App\Rules\Api\V1;

use Illuminate\Contracts\Validation\Rule;

class NumericSymbolRule implements Rule
{
    public function passes($attribute, $value)
    {
        if(str_contains($value,"'") ||
            str_contains($value,'"') ||
            str_contains($value,'<') ||
            str_contains($value,'>') ||
            str_contains($value,';') ||
            str_contains($value,'--') ||
            str_contains($value,'/') ||
            str_contains($value,"\\") ||
            str_contains($value,'&') ||
            str_contains($value,'`') ||
            str_contains($value,'(') ||
            str_contains($value,')') ||
            preg_match('/\p{Arabic}a-zA-Z/u', $value)
        ) {
            return false;
        }
        return true;
    }

    public function message()
    {
        return 'The :attribute field cannot contain \' " < > ; -- / \ & ` ( ) these symbols.';
    }
}
