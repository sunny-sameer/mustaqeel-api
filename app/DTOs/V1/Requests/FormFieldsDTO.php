<?php
// app/DTOs/V1/Requests/FormFieldsDTO.php

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
        public int $fieldOrder,
        public int $gridColumns,
        public bool $repeatable,
        public ?string $repeatableLabel,
        public ?int $repeatableMax,
        public ?array $conditions,
        public bool $status,
        public ?array $categoryRules, // ADD THIS - keep separate from meta
    ) {}

    public static function fromArray(array $data): self
    {
        $formFields = $data['formFields'];
        $metaFields = $data['metaFields'] ?? [];

        // Generate slug from English name
        $slug = str($formFields['nameEn'])->slug();

        // IMPORTANT FIX: DO NOT merge categoryRules into meta
        // Meta should ONLY contain field configuration
        $meta = array_merge($metaFields, []); // Removed categoryRules from here

        return new self(
            nameEn: $formFields['nameEn'],
            nameAr: $formFields['nameAr'],
            slug: $slug,
            type: $formFields['type'],
            meta: $meta,
            section: $formFields['section'] ?? 'general',
            group: $formFields['group'] ?? 'general',
            fieldOrder: $formFields['fieldOrder'] ?? 0,
            gridColumns: $formFields['gridColumns'] ?? 4,
            repeatable: $formFields['repeatable'] ?? false,
            repeatableLabel: $formFields['repeatableLabel'] ?? null,
            repeatableMax: $formFields['repeatableMax'] ?? null,
            conditions: $formFields['conditions'] ?? null,
            status: $formFields['status'] ?? true,
            categoryRules: $data['categoryRules'] ?? [], // Keep separate
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
            'fieldOrder' => $this->fieldOrder,
            'gridColumns' => $this->gridColumns,
            'repeatable' => $this->repeatable,
            'repeatableLabel' => $this->repeatableLabel,
            'repeatableMax' => $this->repeatableMax,
            'conditions' => $this->conditions ? json_encode($this->conditions, JSON_UNESCAPED_UNICODE) : null,
            'status' => $this->status,
        ];
    }
}