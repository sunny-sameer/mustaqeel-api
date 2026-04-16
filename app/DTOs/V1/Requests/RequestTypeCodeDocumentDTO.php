<?php

namespace App\DTOs\V1\Requests;

final readonly class RequestTypeCodeDocumentDTO
{
    public function __construct(
        public int $reqTypeCodeId,
        public int $key,
        public string $documentName,
    ) {}


    public static function fromArray(array $data): self
    {
        return new self(
            reqTypeCodeId: $data['reqTypeCodeId'],
            key: $data['key'],
            documentName: $data['documentName'],
        );
    }

    public static function fromRequest($data): self
    {
        return self::fromArray($data);
    }

    public function toArray(): array
    {
        return [
            'reqTypeCodeId' => $this->reqTypeCodeId,
            'key' => $this->key,
            'documentName' => $this->documentName,
        ];
    }
}
