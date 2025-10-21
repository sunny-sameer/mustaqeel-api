<?php

namespace App\DTOs\V1\Profile;


use Illuminate\Http\Request;


use Carbon\Carbon;


final readonly class PassportDTO
{
    public function __construct(
        public int $userId,
        public ?string $passportNumber = null,
        public ?string $passportType = null,
        public ?Carbon $passportIssuerDate = null,
        public ?string $passportIssuingCountry = null,
        public ?string $passportIssueBy = null,
        public ?Carbon $passportExpiryDate = null,
        public ?string $passportPlaceOfIssue = null,
        public bool $status = true,
    ) {}


    public static function fromArray(array $data): self
    {
        return new self(
            userId: auth()->id(),
            passportNumber: $data['personalInfo']['passportDetails']['number'] ?? NULL,
            passportType: $data['personalInfo']['passportDetails']['type'] ?? NULL,
            passportIssuerDate: $data['personalInfo']['passportDetails']['issueDate'] ? Carbon::parse($data['personalInfo']['passportDetails']['issueDate']) : NULL,
            passportIssuingCountry: $data['personalInfo']['passportDetails']['issueCountry'] ?? NULL,
            passportIssueBy: $data['personalInfo']['passportDetails']['issueBy'] ?? NULL,
            passportExpiryDate: $data['personalInfo']['passportDetails']['expiryDate'] ? Carbon::parse($data['personalInfo']['passportDetails']['expiryDate']) : NULL,
            passportPlaceOfIssue: $data['personalInfo']['passportDetails']['issuePlace'] ?? NULL,
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
            'passportNumber' => $this->passportNumber,
            'passportType' => $this->passportType,
            'passportIssuerDate' => $this->passportIssuerDate,
            'passportIssuingCountry' => $this->passportIssuingCountry,
            'passportIssueBy' => $this->passportIssueBy,
            'passportExpiryDate' => $this->passportExpiryDate,
            'passportPlaceOfIssue' => $this->passportPlaceOfIssue,
            'status' => $this->status,
        ];
    }
}
