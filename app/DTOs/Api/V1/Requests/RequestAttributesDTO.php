<?php

namespace App\DTOs\Api\V1\Requests;


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

        $map = Arr::except($data,['personalInfo.identificationData']);


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
