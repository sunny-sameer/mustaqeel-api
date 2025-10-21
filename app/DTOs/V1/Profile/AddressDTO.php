<?php

namespace App\DTOs\V1\Profile;


use Illuminate\Http\Request;


final readonly class AddressDTO
{
    public function __construct(
        public int $userId,
        public ?string $zip = null,
        public ?string $address = null,
        public bool $status = true,
    ) {}


    public static function fromArray(array $data): self
    {
        return new self(
            userId: auth()->id(),
            zip: $data['personalInfo']['contactInfo']['poBox'] ?? NULL,
            address: $data['personalInfo']['contactInfo']['permanentAddress'] ?? NULL,
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
