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

if(!function_exists('getEntityRole')){
    function getEntityRoles(){
        $roles = [];

        $userRoles = auth()->user()->roles;

        if ($userRoles->pluck('type')->contains('entity')) {
            $roles = $userRoles->pluck('name')->toArray();
        }

        return $roles;
    }
}

if(!function_exists('getUserMetas')){
    function getUserMetas(){
        $metaData = [];
        $metas = json_decode(auth()->user()->metaData->meta,true);

        $metaData['incubators'] = collect($metas['incubators'] ?? [])
        ->pluck('slug')
        ->unique()
        ->values()
        ->toArray();

        $metaData['entities'] = collect($metas['entities'] ?? [])
        ->pluck('slug')
        ->unique()
        ->values()
        ->toArray();

        $metaData['activities'] = collect($metas['entities'] ?? [])
        ->pluck('activities')
        ->flatten(1)
        ->unique('slug')
        ->pluck('slug')
        ->values()
        ->toArray();

        $metaData['subActivities'] = collect($metas['entities'] ?? [])
        ->flatMap(fn ($entity) => $entity['activities'] ?? [])
        ->flatMap(fn ($activity) => $activity['subActivities'] ?? [])
        ->values()
        ->toArray();

        return $metaData;
    }
}
