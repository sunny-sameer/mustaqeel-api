<?php

namespace App\DTOs\V1\Requests;


use Illuminate\Http\Request;

final readonly class FormFieldsDTO
{
    public function __construct(
        public string $nameEn,
        public string $nameAr,
        public string $slug,
        public string $type,
        public ?array $meta,
        public string $section,
        public string $group,
        public int $field_order,
        public int $grid_columns,
        public bool $repeatable,
        public ?string $repeatable_label,
        public ?int $repeatable_max,
        public ?array $conditions,
        public bool $status,
    ) {}


    public static function fromArray(array $data): self
    {
        $formFields = $data['formFields'];
        $metaFields = $data['metaFields'] ?? [];

        // Generate slug from English name
        $slug = str($formFields['nameEn'])->slug();

        // Merge all meta data
        $meta = array_merge($metaFields, [
            'categoryRules' => $data['categoryRules'] ?? []
        ]);

        return new self(
            nameEn: $formFields['nameEn'],
            nameAr: $formFields['nameAr'],
            slug: $slug,
            type: $formFields['type'],
            meta: $meta,
            section: $formFields['section'] ?? 'general',
            group: $formFields['group'] ?? 'general',
            field_order: $formFields['field_order'] ?? 0,
            grid_columns: $formFields['grid_columns'] ?? 4,
            repeatable: $formFields['repeatable'] ?? false,
            repeatable_label: $formFields['repeatable_label'] ?? null,
            repeatable_max: $formFields['repeatable_max'] ?? null,
            conditions: $formFields['conditions'] ?? null,
            status: $formFields['status'] ?? true,
        );
    }

    public static function fromRequest(Request $request): self
    {
        return self::fromArray($request->validated());
    }

    public function toArray(): array
    {
        return [
            'nameEn' => $this->nameEn,
            'nameAr' => $this->nameAr,
            'slug' => $this->slug,
            'type' => $this->type,
            'meta' => $this->meta ? json_encode($this->meta, JSON_UNESCAPED_UNICODE) : null,
            'section' => $this->section,
            'group' => $this->group,
            'field_order' => $this->field_order,
            'grid_columns' => $this->grid_columns,
            'repeatable' => $this->repeatable,
            'repeatable_label' => $this->repeatable_label,
            'repeatable_max' => $this->repeatable_max,
            'conditions' => $this->conditions ? json_encode($this->conditions, JSON_UNESCAPED_UNICODE) : null,
            'status' => $this->status,
        ];
    }
}
