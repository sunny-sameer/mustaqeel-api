<?php




if(!function_exists('camelCaseToSpace')){
    function camelCaseToSpace($field)
    {
        $label = strtolower(preg_replace('/([a-z])([A-Z])/', '$1 $2', $field));
        return $label;
    }
}

if(!function_exists('lowerFirstWord')){
    function lowerFirstWord($sentence)
    {
        $words = explode(' ', $sentence);

        $words[0] = strtolower($words[0]);

        return implode(' ', $words);
    }
}
