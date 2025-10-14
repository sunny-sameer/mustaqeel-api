<?php

namespace App\DTOs\Api\V1\Profile;


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
            'email' => $data['personalInfo']['contactInfo']['email'],
            'mobileNumber' => $data['personalInfo']['contactInfo']['mobile'],
            'phoneNumber' => $data['personalInfo']['contactInfo']['phone'] ?? NULL,
            'arabicLevel' => $data['personalInfo']['applicantInfo']['langProficiencyAr'],
            'englishLevel' => $data['personalInfo']['applicantInfo']['langProficiencyEn'],
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
