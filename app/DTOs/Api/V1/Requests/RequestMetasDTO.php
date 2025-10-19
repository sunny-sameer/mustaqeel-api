<?php

namespace App\DTOs\Api\V1\Requests;


use Illuminate\Http\Request;


final readonly class RequestMetasDTO
{
    public function __construct(
        public int $modelId,
        public ?string $catSlug = null,
        public ?string $subCatSlug = null,
        public ?string $sectorSlug = null,
        public ?string $activitySlug = null,
        public ?string $subActivitySlug = null,
        public ?string $entitySlug = null,
        public ?string $incubatorSlug = null,
        public string $modelType,
    ) {}


    public static function fromArray(array $data, int $modelId, string $modelType): self
    {
        return new self(
            modelId: $modelId,
            catSlug: $data['personalInfo']['identificationData']['category'] ?? NULL,
            subCatSlug: $data['personalInfo']['identificationData']['subCategory'] ?? NULL,
            sectorSlug: $data['personalInfo']['identificationData']['sector'] ?? NULL,
            activitySlug: ($data['personalInfo']['identificationData']['category'] == 'tal' || $data['personalInfo']['identificationData']['category'] == 'ent') ? $data['personalInfo']['identificationData']['activity'] : NULL,
            subActivitySlug: ($data['personalInfo']['identificationData']['category'] == 'tal' || $data['personalInfo']['identificationData']['category'] == 'ent') ? ($data['personalInfo']['identificationData']['subActivity'] ?? NULL) : NULL,
            entitySlug: $data['personalInfo']['identificationData']['category'] == 'tal' ? $data['personalInfo']['identificationData']['entity'] : NULL,
            incubatorSlug: $data['personalInfo']['identificationData']['category'] == 'ent' ? $data['personalInfo']['identificationData']['incubator'] : NULL,
            modelType: $modelType,
        );
    }

    public static function fromRequest($request, $modelId, $modelType): self
    {
        return self::fromArray($request, $modelId, $modelType);
    }

    public function toArray(): array
    {
        return [
            'modelId' => $this->modelId,
            'catSlug' => $this->catSlug,
            'subCatSlug' => $this->subCatSlug,
            'sectorSlug' => $this->sectorSlug,
            'activitySlug' => $this->activitySlug,
            'subActivitySlug' => $this->subActivitySlug,
            'entitySlug' => $this->entitySlug,
            'incubatorSlug' => $this->incubatorSlug,
            'modelType' => $this->modelType,
        ];
    }
}
