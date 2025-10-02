<?php

namespace App\DTOs\Api\V1;

use App\Constants\Api\V1\WorkPermitConstant;
use Illuminate\Http\Request;

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
            'isQatarResident' => $data['isQatarResident'] ? true : false
        ];

        if($data['isQatarResident']){
            if($data['category'] == 'tal' || $data['category'] == 'ent'){
                $map['nameOfSponsor'] = $data['nameOfSponsor'] ?? '';
                $map['addressOfSponsor'] = $data['addressOfSponsor'] ?? '';
            }
            $map['qid'] = $data['qid'] ?? '';
            $map['qatarAddress'] = $data['qatarAddress'] ?? '';
            $map['qidType'] = $data['qidType'] ?? '';
            if($data['qidType'] == 'Work Residency'){
                $map['workPermit'] = $data['workPermit'] == 'yes' ? WorkPermitConstant::WORK_PERMIT_YES : ($data['workPermit'] == 'no' ? WorkPermitConstant::WORK_PERMIT_NO : '');
                if($data['workPermit'] == 'yes'){
                    $map['maintainWorkPermit'] = $data['maintainWorkPermit'] == 'yes' ? WorkPermitConstant::MAINTAIN_WORK_PERMIT_YES : ($data['maintainWorkPermit'] == 'no' ? WorkPermitConstant::MAINTAIN_WORK_PERMIT_NO : '');
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
