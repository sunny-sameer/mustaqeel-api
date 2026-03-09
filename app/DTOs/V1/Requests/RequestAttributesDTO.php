<?php

namespace App\DTOs\V1\Requests;

use App\Models\RequestAttribute;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

final readonly class RequestAttributesDTO
{
    public function __construct(
        public int $reqId,
        public string $meta,
        public string $type,
    ) {}

    public static function fromArray(array $data, int $reqId): array
    {
        $attributes = [];
        
        // Exclude fields that are stored elsewhere
        $excludedFields = [
            'personalInfo.identificationData',
            'documents',
            'id'
        ];
        
        $map = Arr::except($data, $excludedFields);
        
        foreach ($map as $key => $value) {
            // Only create attribute if there's data
            if (!empty($value) && is_array($value)) {
                // Recursively filter out empty values
                $filteredValue = self::filterEmptyValues($value);
                
                if (!empty($filteredValue)) {
                    $attributes[] = new self(
                        reqId: $reqId,
                        meta: json_encode($filteredValue, JSON_UNESCAPED_UNICODE),
                        type: $key,
                    );
                }
            }
        }
        
        return $attributes;
    }
    
    /**
     * Recursively filter out empty values from arrays
     */
    private static function filterEmptyValues($array)
    {
        if (!is_array($array)) {
            return $array;
        }
        
        $result = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $filtered = self::filterEmptyValues($value);
                if (!empty($filtered)) {
                    $result[$key] = $filtered;
                }
            } elseif ($value !== null && $value !== '' && $value !== []) {
                $result[$key] = $value;
            }
        }
        return $result;
    }

    public static function fromRequest(Request $request, $reqId): array
    {
        return self::fromArray($request->validated(), $reqId);
    }

    public static function updateFromArray(array $data, int $reqId): array
    {
        $attributes = [];
        
        $excludedFields = [
            'personalInfo.identificationData',
            'documents',
            'id'
        ];
        
        $map = Arr::except($data, $excludedFields);
        
        foreach ($map as $key => $value) {
            if (!empty($value) && is_array($value)) {
                // Find existing attribute
                $requestAttribute = RequestAttribute::where([
                    'reqId' => $reqId,
                    'type' => $key
                ])->first();
                
                if ($requestAttribute) {
                    // Merge with existing data
                    $existingMeta = json_decode($requestAttribute->meta, true) ?? [];
                    $newMeta = self::mergeArrays($existingMeta, $value);
                    $filteredMeta = self::filterEmptyValues($newMeta);
                    
                    if (!empty($filteredMeta)) {
                        $attributes[] = new self(
                            reqId: $reqId,
                            meta: json_encode($filteredMeta, JSON_UNESCAPED_UNICODE),
                            type: $key,
                        );
                    }
                } else {
                    // Create new attribute
                    $filteredValue = self::filterEmptyValues($value);
                    if (!empty($filteredValue)) {
                        $attributes[] = new self(
                            reqId: $reqId,
                            meta: json_encode($filteredValue, JSON_UNESCAPED_UNICODE),
                            type: $key,
                        );
                    }
                }
            }
        }
        
        return $attributes;
    }
    
    /**
     * Recursively merge arrays
     */
    private static function mergeArrays($existing, $new)
    {
        foreach ($new as $key => $value) {
            if (is_array($value) && isset($existing[$key]) && is_array($existing[$key])) {
                $existing[$key] = self::mergeArrays($existing[$key], $value);
            } else {
                $existing[$key] = $value;
            }
        }
        return $existing;
    }

    public function toArray(): array
    {
        return [
            'reqId' => $this->reqId,
            'meta' => $this->meta,
            'type' => $this->type,
        ];
    }
}