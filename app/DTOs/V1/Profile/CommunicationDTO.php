<?php

namespace App\DTOs\V1\Profile;

use App\Models\Communications;
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
        $comm = Communications::where('userId',auth()->id())
        ->where('key','profile')->first();

        $commData = isset($comm->value) ? json_decode($comm->value,true) : [];

        $map = [
            'email' => isset($data['personalInfo']['contactInfo']['email']) ? $data['personalInfo']['contactInfo']['email'] : ($commData['email'] ?? NULL),
            'mobileNumber' => isset($data['personalInfo']['contactInfo']['mobile']) ? $data['personalInfo']['contactInfo']['mobile'] : ($commData['mobileNumber'] ?? NULL),
            'phoneNumber' => isset($data['personalInfo']['contactInfo']['phone']) ? $data['personalInfo']['contactInfo']['phone'] : ($commData['phoneNumber'] ?? NULL),
            'arabicLevel' => isset($data['personalInfo']['applicantInfo']['langProficiencyAr']) ? $data['personalInfo']['applicantInfo']['langProficiencyAr'] : ($commData['arabicLevel'] ?? NULL),
            'englishLevel' => isset($data['personalInfo']['applicantInfo']['langProficiencyEn']) ? $data['personalInfo']['applicantInfo']['langProficiencyEn'] : ($commData['englishLevel'] ?? NULL),
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
