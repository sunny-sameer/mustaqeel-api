<?php

namespace App\DTOs\V1\Requests;


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
        public Carbon $submittedAt
    ) {}


    public static function fromArray(array $data, ?string $reqReferenceNumber = null): self
    {
        return new self(
            userId: auth()->id(),
            reqReferenceNumber: $reqReferenceNumber ?? NULL,
            nameEn: isset($data['personalInfo']['applicantInfo']['nameEn']) ? $data['personalInfo']['applicantInfo']['nameEn'] : NULL,
            nameAr: isset($data['personalInfo']['applicantInfo']['nameAr']) ? $data['personalInfo']['applicantInfo']['nameAr'] : NULL,
            email: isset($data['personalInfo']['contactInfo']['email']) ? $data['personalInfo']['contactInfo']['email'] : NULL,
            mobileNumber: isset($data['personalInfo']['contactInfo']['mobile']) ? $data['personalInfo']['contactInfo']['mobile'] : NULL,
            passportNumber: isset($data['personalInfo']['passportDetails']['number']) ? $data['personalInfo']['passportDetails']['number'] : NULL,
            qid: isset($data['personalInfo']['applicantInfo']['areYouQatarResident']) && isset($data['personalInfo']['applicantInfo']['qidNumber']) ? $data['personalInfo']['applicantInfo']['qidNumber'] : NULL,
            status: true,
            submittedAt: Carbon::now(),
        );
    }

    public static function fromRequest(Request $request, $reqReferenceNumber = null): self
    {
        return self::fromArray($request->validated(), $reqReferenceNumber);
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
