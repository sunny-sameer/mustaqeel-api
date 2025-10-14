<?php

namespace App\DTOs\Api\V1\Requests;


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
            catSlug: $data['personalInfo']['identificationData']['category'],
            subCatSlug: $data['personalInfo']['identificationData']['subCategory'] ?? NULL,
            sectorSlug: $data['personalInfo']['identificationData']['sector'],
            activitySlug: ($data['personalInfo']['identificationData']['category'] == 'tal' || $data['personalInfo']['identificationData']['category'] == 'ent') ? $data['personalInfo']['identificationData']['activity'] : NULL,
            subActivitySlug: ($data['personalInfo']['identificationData']['category'] == 'tal' || $data['personalInfo']['identificationData']['category'] == 'ent') ? ($data['personalInfo']['identificationData']['subActivity'] ?? NULL) : NULL,
            entitySlug: $data['personalInfo']['identificationData']['category'] == 'tal' ? $data['personalInfo']['identificationData']['entity'] : NULL,
            incubatorSlug: $data['personalInfo']['identificationData']['category'] == 'ent' ? $data['personalInfo']['identificationData']['incubator'] : NULL,
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
