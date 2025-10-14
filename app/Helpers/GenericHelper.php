<?php




if(!function_exists('camelCaseToSpace')){
    function camelCaseToSpace($field)
    {
        $label = strtolower(preg_replace('/([a-z])([A-Z])/', '$1 $2', $field));
        return $label;
    }
}
