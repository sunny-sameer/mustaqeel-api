<?php

namespace App\DTOs\Api\V1;


use Illuminate\Http\Request;


use Carbon\Carbon;


final readonly class RequestDTO
{
    public function __construct(
        public int $userId,
        public string $reqReferenceNumber,
        public string $nameEn,
        public ?string $nameAr = null,
        public string $email,
        public string $mobileNumber,
        public string $passportNumber,
        public ?int $qid = null,
        public bool $status = true,
        public Carbon $submittedAt
    ) {}


    public static function fromArray(array $data, string $reqReferenceNumber): self
    {
        return new self(
            userId: auth()->id(),
            reqReferenceNumber: $reqReferenceNumber,
            nameEn: $data['fullNameEn'],
            nameAr: $data['fullNameAr'] ?? NULL,
            email: $data['email'],
            mobileNumber: $data['mobileNumber'],
            passportNumber: $data['passportNumber'],
            qid: $data['isQatarResident'] ? $data['qid'] : NULL,
            status: true,
            submittedAt: Carbon::now(),
        );
    }

    public static function fromRequest(Request $request, $reqReferenceNumber): self
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
