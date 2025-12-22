<?php

namespace App\DTOs\V1\Requests;

use App\Models\RequestAttribute;
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

        $map = Arr::except($data,['personalInfo.identificationData','documents','id']);


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

    public static function updateFromArray(array $data, int $reqId): array
    {
        $attributes = [];

        foreach ($data as $key => $value) {
            if (!empty($value)) {
                $request = RequestAttribute::where(['reqId'=>$reqId,'type'=>$key])->first();
                $requestMeta = json_decode($request->meta,true);
                foreach ($value as $metaKey => $metaValue) {
                    foreach ($metaValue as $k => $v) {
                        if(is_string($v)){
                            $requestMeta[$metaKey][$k] = $v;
                        }else if(is_array($v)){
                            $requestMeta[$metaKey] = $metaValue;
                        }
                    }
                }
                $attributes[] = new self(
                    reqId: $reqId,
                    meta: json_encode(array_filter($requestMeta)),
                    type: $key,
                );
            }
        }

        return $attributes;
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
