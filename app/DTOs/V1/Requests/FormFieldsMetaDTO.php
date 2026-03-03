<?php

namespace App\DTOs\V1\Requests;

use Illuminate\Http\Request;

final readonly class FormFieldsMetaDTO
{
    public function __construct(
        public int $ffId,
        public string $key,
        public array $value,
        public string $onshoreOffShore,
        public bool $isRequired,
    ) {}

    public static function fromArray(array $data, int $ffId): array
    {
        $dtos = [];
        
        // Handle new categoryRules format
        if (isset($data['categoryRules'])) {
            foreach ($data['categoryRules'] as $rule) {
                // Build value array with hierarchy
                $value = [];
                
                if (isset($rule['sub_category_slug']) && !empty($rule['sub_category_slug'])) {
                    $value['sub_category'] = $rule['sub_category_slug'];
                }
                if (isset($rule['sector_slug']) && !empty($rule['sector_slug'])) {
                    $value['sector'] = $rule['sector_slug'];
                }
                if (isset($rule['activity_slug']) && !empty($rule['activity_slug'])) {
                    $value['activity'] = $rule['activity_slug'];
                }
                if (isset($rule['sub_activity_slug']) && !empty($rule['sub_activity_slug'])) {
                    $value['sub_activity'] = $rule['sub_activity_slug'];
                }
                if (isset($rule['entity_slug']) && !empty($rule['entity_slug'])) {
                    $value['entity'] = $rule['entity_slug'];
                }
                if (isset($rule['incubator_slug']) && !empty($rule['incubator_slug'])) {
                    $value['incubator'] = $rule['incubator_slug'];
                }

                $dtos[] = new self(
                    ffId: $ffId,
                    key: $rule['category_slug'] ?? 'all',
                    value: $value,
                    onshoreOffShore: $rule['onshore_offshore'] ?? 'both',
                    isRequired: $rule['is_required'] ?? false,
                );
            }
        }
        
        // Handle legacy identificationData format (for backward compatibility)
        if (isset($data['identificationData'])) {
            foreach ($data['identificationData'] as $item) {
                $value = [];
                foreach ($item['value'] as $k => $v) {
                    if (!empty($v)) {
                        // Convert camelCase to snake_case for consistency
                        $key = str($k)->snake()->toString();
                        $value[$key] = $v;
                    }
                }
                
                $dtos[] = new self(
                    ffId: $ffId,
                    key: $item['key'],
                    value: $value,
                    onshoreOffShore: $item['onshoreOffShore'],
                    isRequired: $item['isRequired'],
                );
            }
        }

        return $dtos;
    }

    public static function fromRequest(Request $request, int $ffId): array
    {
        return self::fromArray($request->validated(), $ffId);
    }

    public function toArray(): array
    {
        return [
            'ffId' => $this->ffId,
            'key' => $this->key,
            'value' => json_encode($this->value, JSON_UNESCAPED_UNICODE),
            'onshoreOffShore' => $this->onshoreOffShore,
            'isRequired' => $this->isRequired,
        ];
    }
}