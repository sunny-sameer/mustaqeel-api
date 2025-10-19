<?php

namespace App\DTOs\Api\V1\Requests;


use Illuminate\Http\Request;
use Illuminate\Support\Arr;

final readonly class FormFieldsDTO
{
    public function __construct(
        public string $nameEn,
        public string $nameAr,
        public string $type,
        public string $onshoreOffShore,
        public bool $isRequired = false,
        public ?string $meta = null,
        public bool $status = true,
    ) {}


    public static function fromArray(array $data): self
    {
        $map = Arr::only($data, ['metaFields']);

        return new self(
            nameEn: $data['formFields']['nameEn'],
            nameAr: $data['formFields']['nameAr'],
            type: $data['formFields']['type'],
            onshoreOffShore: $data['formFields']['onshoreOffShore'],
            isRequired: $data['formFields']['isRequired'],
            meta: json_encode(array_filter($map['metaFields'])),
            status: true,
        );
    }

    public static function fromRequest($request): self
    {
        return self::fromArray($request);
    }

    public function toArray(): array
    {
        return [
            'nameEn' => $this->nameEn,
            'nameAr' => $this->nameAr,
            'type' => $this->type,
            'onshoreOffShore' => $this->onshoreOffShore,
            'isRequired' => $this->isRequired,
            'meta' => $this->meta,
            'status' => $this->status,
        ];
    }
}
