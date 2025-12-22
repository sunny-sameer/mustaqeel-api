<?php

namespace App\DTOs\V1\Profile;


use Illuminate\Http\Request;


use App\Constants\Api\V1\WorkPermitConstant;
use App\Models\QatarInfo;

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
        $qI = QatarInfo::where('userId',auth()->id())
        ->where('key','profile')->first();

        $qIData = isset($qI->value) ? json_decode($qI->value,true) : [];

        $map = [
            'isQatarResident' => isset($data['personalInfo']['applicantInfo']['areYouQatarResident']) && $data['personalInfo']['applicantInfo']['areYouQatarResident'] ? true : ($qIData['isQatarResident'] ?? false)
        ];

        if($map['isQatarResident']){
            // if($data['personalInfo']['identificationData']['category'] == 'tal' || $data['personalInfo']['identificationData']['category'] == 'ent'){
            //     $map['nameOfSponsor'] = $data['employmentAndEducation']['employmentDetails']['nameOfSponsor'] ?? NULL;
            //     $map['addressOfSponsor'] = $data['employmentAndEducation']['employmentDetails']['addressOfSponsor'] ?? NULL;
            // }
            $map['qid'] = isset($data['personalInfo']['applicantInfo']['qidNumber']) ? $data['personalInfo']['applicantInfo']['qidNumber'] : ($qIData['qid'] ?? NULL);
            $map['qatarAddress'] = isset($data['personalInfo']['contactInfo']['qatarAddress']) ? $data['personalInfo']['contactInfo']['qatarAddress'] : ($qIData['qatarAddress'] ?? NULL);
            $map['qidType'] = isset($data['personalInfo']['applicantInfo']['qidType']) ? $data['personalInfo']['applicantInfo']['qidType'] : ($qIData['qidType'] ?? NULL);
            // if($data['personalInfo']['applicantInfo']['qidType'] == 'Work Residency'){
            //     $map['workPermit'] = $data['personalInfo']['applicantInfo']['workPermit'] == 'yes' ? WorkPermitConstant::WORK_PERMIT_YES : ($data['personalInfo']['applicantInfo']['workPermit'] == 'no' ? WorkPermitConstant::WORK_PERMIT_NO : NULL);
            //     if($data['personalInfo']['applicantInfo']['workPermit'] == 'yes'){
            //         $map['maintainWorkPermit'] = $data['personalInfo']['applicantInfo']['maintainWorkPermit'] == 'yes' ? WorkPermitConstant::MAINTAIN_WORK_PERMIT_YES : ($data['personalInfo']['applicantInfo']['maintainWorkPermit'] == 'no' ? WorkPermitConstant::MAINTAIN_WORK_PERMIT_NO : NULL);
            //     }
            // }
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
