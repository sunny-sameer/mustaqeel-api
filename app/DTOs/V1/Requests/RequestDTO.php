<?php

namespace App\DTOs\V1\Requests;

use App\Models\Requests;
use Illuminate\Http\Request;
use Carbon\Carbon;

final readonly class RequestDTO
{
    public function __construct(
        public int $userId,
        public ?string $reqReferenceNumber = null,
        public ?string $nameEn = null,
        public ?string $nameAr = null,
        public ?string $email = null,
        public ?string $mobileNumber = null,
        public ?string $passportNumber = null,
        public ?string $qid = null,
        public bool $status = true,
        public string $submittedAt
    ) {}

    public static function fromArray(array $data, ?string $reqReferenceNumber = null): self
    {
        return new self(
            userId: auth()->id(),
            reqReferenceNumber: $reqReferenceNumber ?? null,
            nameEn: $data['personalInfo']['applicantInfo']['nameEn'] ?? null,
            nameAr: $data['personalInfo']['applicantInfo']['nameAr'] ?? null,
            email: $data['personalInfo']['contactInfo']['email'] ?? null,
            mobileNumber: $data['personalInfo']['contactInfo']['mobile'] ?? null,
            passportNumber: $data['personalInfo']['passportDetails']['number'] ?? null,
            qid: self::extractQid($data), // Use helper method to extract QID
            status: true,
            submittedAt: Carbon::now(),
        );
    }
    
    /**
     * Extract QID from residencyDetails (new location) or applicantInfo (old location for backward compatibility)
     */
    private static function extractQid(array $data): ?string
    {
        // Check if user is Qatar resident
        $isQatarResident = $data['ResidencyAndTravelAndFamily']['residencyDetails']['areYouQatarResident?'] ?? 
                          $data['personalInfo']['applicantInfo']['areYouQatarResident'] ?? false;
        
        if (!$isQatarResident) {
            return null;
        }
        
        // Try new location first (residencyDetails)
        if (isset($data['ResidencyAndTravelAndFamily']['residencyDetails']['qIDNumber'])) {
            return $data['ResidencyAndTravelAndFamily']['residencyDetails']['qIDNumber'];
        }
        
        // Fallback to old location (applicantInfo)
        return $data['personalInfo']['applicantInfo']['qidNumber'] ?? null;
    }

    public static function fromRequest(Request $request, $reqReferenceNumber = null): self
    {
        return self::fromArray($request->validated(), $reqReferenceNumber);
    }

    public static function updateFromArray(array $data, ?string $reqReferenceNumber = null): self
    {
        $request = Requests::where('reqReferenceNumber', $reqReferenceNumber)->first();
        
        return new self(
            userId: $request->userId,
            reqReferenceNumber: $request->reqReferenceNumber,
            nameEn: $data['personalInfo']['applicantInfo']['nameEn'] ?? $request->nameEn,
            nameAr: $data['personalInfo']['applicantInfo']['nameAr'] ?? $request->nameAr,
            email: $data['personalInfo']['contactInfo']['email'] ?? $request->email,
            mobileNumber: $data['personalInfo']['contactInfo']['mobile'] ?? $request->mobileNumber,
            passportNumber: $data['personalInfo']['passportDetails']['number'] ?? $request->passportNumber,
            qid: self::extractQidForUpdate($data, $request), // Use update helper
            status: $request->status,
            submittedAt: $request->submittedAt,
        );
    }
    
    /**
     * Extract QID for update operations
     */
    private static function extractQidForUpdate(array $data, Requests $request): ?string
    {
        // Check if new QID is provided in residencyDetails
        if (isset($data['ResidencyAndTravelAndFamily']['residencyDetails']['qIDNumber'])) {
            return $data['ResidencyAndTravelAndFamily']['residencyDetails']['qIDNumber'];
        }
        
        // Check if new QID is provided in old location
        if (isset($data['personalInfo']['applicantInfo']['qidNumber'])) {
            return $data['personalInfo']['applicantInfo']['qidNumber'];
        }
        
        // Otherwise keep existing
        return $request->qid;
    }

    public function toArray(): array
    {
        return [
            'userId' => $this->userId,
            'reqReferenceNumber' => $this->reqReferenceNumber,
            'nameEn' => $this->nameEn,
            'nameAr' => $this->nameAr,
            'email' => $this->email,
            'mobileNumber' => $this->mobileNumber,
            'passportNumber' => $this->passportNumber,
            'qid' => $this->qid,
            'status' => $this->status,
            'submittedAt' => $this->submittedAt,
        ];
    }
}