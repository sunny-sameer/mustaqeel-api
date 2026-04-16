<?php

namespace App\DTOs\V1\Requests;

final readonly class RequestTypeCodeDTO
{
    public function __construct(
        public int $reqId,
        public int $key,
        public string $secureCode,
        public string $expiryDate,
        public int $expiry = 1,
    ) {}


    public static function fromArray(array $data): self
    {
        return new self(
            reqId: $data['reqId'],
            key: $data['key'],
            secureCode: $data['secureCode'],
            expiryDate: $data['expiryDate'],
            expiry: 1,
        );
    }

    public static function fromRequest($data): self
    {
        return self::fromArray($data);
    }

    public function toArray(): array
    {
        return [
            'reqId' => $this->reqId,
            'key' => $this->key,
            'secureCode' => $this->secureCode,
            'expiryDate' => $this->expiryDate,
            'expiry' => $this->expiry,
        ];
    }
}
