<?php

namespace App\DTOs\Api\V1;

use Illuminate\Http\Request;

final readonly class AddressDTO
{
    public function __construct(
        public int $userId,
        public ?string $zip = null,
        public string $address,
        public bool $status = true,
    ) {}


    public static function fromArray(array $data): self
    {
        return new self(
            userId: auth()->id(),
            zip: $data['poBox'] ?? NULL,
            address: $data['permanentAddress'],
            status: true,
        );
    }

    public static function fromRequest(Request $request): self
    {
        return self::fromArray($request->validated());
    }

    public function toArray(): array
    {
        return [
            'userId' => $this->userId,
            'zip' => $this->zip,
            'address' => $this->address,
            'status' => $this->status,
        ];
    }
}
