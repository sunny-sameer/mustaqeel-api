<?php

namespace App\DTOs\Api\V1\Profile;


use Illuminate\Http\Request;


use App\Constants\Api\V1\WorkPermitConstant;


final readonly class QatarInfoDTO
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
            'isQatarResident' => $data['personalInfo']['applicantInfo']['areYouQatarResident'] ? true : false
        ];

        if($data['personalInfo']['applicantInfo']['areYouQatarResident']){
            if($data['personalInfo']['identificationData']['category'] == 'tal' || $data['personalInfo']['identificationData']['category'] == 'ent'){
                $map['nameOfSponsor'] = $data['personalInfo']['employmentDetails']['nameOfSponsor'] ?? NULL;
                $map['addressOfSponsor'] = $data['personalInfo']['employmentDetails']['addressOfSponsor'] ?? NULL;
            }
            $map['qid'] = $data['personalInfo']['applicantInfo']['qidNumber'] ?? NULL;
            $map['qatarAddress'] = $data['personalInfo']['contactInfo']['qatarAddress'] ?? NULL;
            $map['qidType'] = $data['personalInfo']['applicantInfo']['qidType'] ?? NULL;
            if($data['personalInfo']['applicantInfo']['qidType'] == 'Work Residency'){
                $map['workPermit'] = $data['personalInfo']['applicantInfo']['workPermit'] == 'yes' ? WorkPermitConstant::WORK_PERMIT_YES : ($data['personalInfo']['applicantInfo']['workPermit'] == 'no' ? WorkPermitConstant::WORK_PERMIT_NO : NULL);
                if($data['personalInfo']['applicantInfo']['workPermit'] == 'yes'){
                    $map['maintainWorkPermit'] = $data['personalInfo']['applicantInfo']['maintainWorkPermit'] == 'yes' ? WorkPermitConstant::MAINTAIN_WORK_PERMIT_YES : ($data['personalInfo']['applicantInfo']['maintainWorkPermit'] == 'no' ? WorkPermitConstant::MAINTAIN_WORK_PERMIT_NO : NULL);
                }
            }
        }

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
