<?php

namespace App\DTOs\Api\V1;


use Illuminate\Http\Request;


final readonly class CommunicationDTO
{
    public function __construct(
        public int $userId,
        public string $key,
        public string $value,
        public bool $status = true,
    ) {}


    public static function fromArray(array $data): self
    {
        $map = [
            'email' => $data['email'],
            'mobileNumber' => $data['mobileNumber'],
            'phoneNumber' => $data['phoneNumber'] ?? '',
            'arabicLevel' => $data['arabicLevel'],
            'englishLevel' => $data['englishLevel'],
        ];

        return new self(
            userId: auth()->id(),
            key: 'profile',
            value: json_encode(array_filter($map)),
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
            'key' => $this->key,
            'value' => $this->value,
            'status' => $this->status,
        ];
    }
}
