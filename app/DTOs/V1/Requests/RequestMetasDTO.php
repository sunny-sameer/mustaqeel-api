<?php

namespace App\DTOs\V1\Requests;

use App\Models\RequestMetaData;
use App\Models\Requests;
use Illuminate\Http\Request;

final readonly class RequestMetasDTO
{
    public function __construct(
        public int $reqId,
        public string $key,
        public string $value,
    ) {}

    public static function fromArray(array $data, int $reqId): array
    {
        $attributes = [];
        $identificationData = ['category', 'subCategory', 'sector', 'activity', 'subActivity', 'entity', 'incubator'];

        foreach ($identificationData as $value) {
            if (isset($data['personalInfo']['identificationData'][$value]) && 
                !empty($data['personalInfo']['identificationData'][$value])) {
                $attributes[] = new self(
                    reqId: $reqId,
                    key: $value,
                    value: $data['personalInfo']['identificationData'][$value]
                );
            } else {
                // Delete if exists but not provided
                RequestMetaData::where('reqId', $reqId)
                    ->where('key', $value)
                    ->delete();
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
            'key' => $this->key,
            'value' => $this->value,
        ];
    }
}