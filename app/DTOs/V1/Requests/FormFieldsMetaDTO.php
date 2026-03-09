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
                
                if (isset($rule['subCategorySlug']) && !empty($rule['subCategorySlug'])) {
                    $value['subCategory'] = $rule['subCategorySlug'];
                }
                if (isset($rule['sectorSlug']) && !empty($rule['sectorSlug'])) {
                    $value['sector'] = $rule['sectorSlug'];
                }
                if (isset($rule['activitySlug']) && !empty($rule['activitySlug'])) {
                    $value['activity'] = $rule['activitySlug'];
                }
                if (isset($rule['subActivitySlug']) && !empty($rule['subActivitySlug'])) {
                    $value['subActivity'] = $rule['subActivitySlug'];
                }
                if (isset($rule['entitySlug']) && !empty($rule['entitySlug'])) {
                    $value['entity'] = $rule['entitySlug'];
                }
                if (isset($rule['incubatorSlug']) && !empty($rule['incubatorSlug'])) {
                    $value['incubator'] = $rule['incubatorSlug'];
                }

                $dtos[] = new self(
                    ffId: $ffId,
                    key: $rule['categorySlug'] ?? 'all',
                    value: $value,
                    onshoreOffShore: $rule['onshoreOffShore'] ?? 'both',
                    isRequired: $rule['isRequired'] ?? false,
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