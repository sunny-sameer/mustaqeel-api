<?php

namespace App\DTOs\V1\User;

use Illuminate\Http\Request;

final readonly class UserMetaDataDTO
{
    public function __construct(
        public string $meta,
        public int $status,
    ) {}


    public static function fromArray(array $data): self
    {
        return new self(
            meta:  json_encode(array_filter($data['identificationData'] ?? [])),
            status: 1,
        );
    }

    public static function fromRequest(Request $request): self
    {
        return self::fromArray($request->validated());
    }

    public function toArray(): array
    {
        return [
            'meta' => $this->meta,
            'status' => $this->status,
        ];
    }
}
