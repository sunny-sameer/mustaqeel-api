<?php

namespace App\DTOs\Api\V1;


use Illuminate\Http\Request;


use Carbon\Carbon;


final readonly class PassportDTO
{
    public function __construct(
        public int $userId,
        public string $passportNumber,
        public string $passportType,
        public Carbon $passportIssuerDate,
        public string $passportIssuingCountry,
        public string $passportIssueBy,
        public Carbon $passportExpiryDate,
        public string $passportPlaceOfIssue,
        public bool $status = true,
    ) {}


    public static function fromArray(array $data): self
    {
        return new self(
            userId: auth()->id(),
            passportNumber: $data['passportNumber'],
            passportType: $data['passportType'],
            passportIssuerDate: Carbon::parse($data['passportIssueDate']),
            passportIssuingCountry: $data['passportIssuingCountry'],
            passportIssueBy: $data['passportIssueBy'],
            passportExpiryDate: Carbon::parse($data['passportExpiryDate']),
            passportPlaceOfIssue: $data['passportPlaceOfIssue'],
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
