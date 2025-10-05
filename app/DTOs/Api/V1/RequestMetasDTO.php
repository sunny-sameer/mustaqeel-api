<?php

namespace App\DTOs\Api\V1;


use Illuminate\Http\Request;


final readonly class RequestMetasDTO
{
    public function __construct(
        public int $reqId,
        public string $catSlug,
        public ?string $subCatSlug = null,
        public string $sectorSlug,
        public ?string $activitySlug = null,
        public ?string $subActivitySlug = null,
        public ?string $entitySlug = null,
        public ?string $incubatorSlug = null,
    ) {}


    public static function fromArray(array $data, int $reqId): self
    {
        return new self(
            reqId: $reqId,
            catSlug: $data['category'],
            subCatSlug: $data['subCategory'] ?? null,
            sectorSlug: $data['sector'],
            activitySlug: ($data['category'] == 'tal' || $data['category'] == 'ent') ? $data['activity'] : NULL,
            subActivitySlug: ($data['category'] == 'tal' || $data['category'] == 'ent') ? ($data['subActivity'] ?? NULL) : NULL,
            entitySlug: $data['category'] == 'tal' ? $data['entity'] : NULL,
            incubatorSlug: $data['category'] == 'ent' ? $data['incubator'] : NULL,
        );
    }

    public static function fromRequest(Request $request, $reqId): self
    {
        return self::fromArray($request->validated(), $reqId);
    }

    public function toArray(): array
    {
        return [
            'reqId' => $this->reqId,
            'catSlug' => $this->catSlug,
            'subCatSlug' => $this->subCatSlug,
            'sectorSlug' => $this->sectorSlug,
            'activitySlug' => $this->activitySlug,
            'subActivitySlug' => $this->subActivitySlug,
            'entitySlug' => $this->entitySlug,
            'incubatorSlug' => $this->incubatorSlug,
        ];
    }
}
