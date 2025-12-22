<?php

namespace App\DTOs\V1\Profile;

use App\Models\PassportDetails;
use Illuminate\Http\Request;


use Carbon\Carbon;


final readonly class PassportDTO
{
    public function __construct(
        public int $userId,
        public ?string $passportNumber = null,
        public ?string $passportType = null,
        public ?string $passportIssuerDate = null,
        public ?string $passportIssuingCountry = null,
        public ?string $passportIssueBy = null,
        public ?string $passportExpiryDate = null,
        public ?string $passportPlaceOfIssue = null,
        public bool $status = true,
    ) {}


    public static function fromArray(array $data): self
    {
        $pp = PassportDetails::where('userId',auth()->id())->first();
        return new self(
            userId: auth()->id(),
            passportNumber: isset($data['personalInfo']['passportDetails']['number']) ? $data['personalInfo']['passportDetails']['number'] : ($pp->passportNumber ?? NULL),
            passportType: isset($data['personalInfo']['passportDetails']['type']) ? $data['personalInfo']['passportDetails']['type'] : ($pp->passportType ?? NULL),
            passportIssuerDate: isset($data['personalInfo']['passportDetails']['issueDate']) ? Carbon::parse($data['personalInfo']['passportDetails']['issueDate']) : ($pp->passportIssuerDate ?? NULL),
            passportIssuingCountry: isset($data['personalInfo']['passportDetails']['issueCountry']) ? $data['personalInfo']['passportDetails']['issueCountry'] : ($pp->passportIssuingCountry ?? NULL),
            passportIssueBy: isset($data['personalInfo']['passportDetails']['issueBy']) ? $data['personalInfo']['passportDetails']['issueBy'] : ($pp->passportIssueBy ?? NULL),
            passportExpiryDate: isset($data['personalInfo']['passportDetails']['expiryDate']) ? Carbon::parse($data['personalInfo']['passportDetails']['expiryDate']) : ($pp->passportExpiryDate ?? NULL),
            passportPlaceOfIssue: isset($data['personalInfo']['passportDetails']['issuePlace']) ? $data['personalInfo']['passportDetails']['issuePlace'] : ($pp->passportPlaceOfIssue ?? NULL),
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
