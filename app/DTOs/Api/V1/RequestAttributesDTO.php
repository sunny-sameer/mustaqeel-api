<?php

namespace App\DTOs\Api\V1;


use Illuminate\Http\Request;


use Illuminate\Support\Arr;


final readonly class RequestAttributesDTO
{
    public function __construct(
        public int $reqId,
        public string $meta,
        public string $type,
    ) {}


    public static function fromArray(array $data, int $reqId): array
    {
        $attributes = [];

        $map['request'] = Arr::except($data,['previousJobs',
            'certificatesAndAcademicQualifications',
            'currentResidenceInOtherCountries',
            'activeOrPreviousNationalities',
            'countryVisitedInLastTenYears',
            'familyMembers',
            'category','subCategory','sector','activity','subActivity','entity','incubator'
        ]);
        $map['previousJobs'] = Arr::only($data,'previousJobs');
        $map['certificatesAndAcademicQualifications'] = Arr::only($data,'certificatesAndAcademicQualifications');
        $map['currentResidenceInOtherCountries'] = Arr::only($data,'currentResidenceInOtherCountries');
        $map['activeOrPreviousNationalities'] = Arr::only($data,'activeOrPreviousNationalities');
        $map['countryVisitedInLastTenYears'] = Arr::only($data,'countryVisitedInLastTenYears');
        $map['familyMembers'] = Arr::only($data,'familyMembers');


        foreach ($map as $key => $value) {
            if (!empty($value)) {
                $attributes[] = new self(
                    reqId: $reqId,
                    meta: isset($value[$key]) ? json_encode(array_filter($value[$key])) : json_encode(array_filter($value)),
                    type: $key,
                );
            }
        }

        return $attributes;
    }

    public static function fromRequest(Request $request, $reqId): array
    {
        return self::fromArray($request->validated(), $reqId);
    }

    public function toArray(): array
    {
        return [
            'reqId' => $this->reqId,
            'meta' => $this->meta,
            'type' => $this->type,
        ];
    }
}
