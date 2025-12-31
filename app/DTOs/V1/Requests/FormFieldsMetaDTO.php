<?php

namespace App\DTOs\V1\Requests;

use App\Models\FormFieldMeta;
use Illuminate\Http\Request;


final readonly class FormFieldsMetaDTO
{
    public function __construct(
        public int $ffId,
        public string $key,
        public string $value,
        public string $onshoreOffShore,
        public bool $isRequired = true,
    ) {}


    public static function fromArray(array $data, int $ffId): array
    {
        $attributes = [];
        if(isset($data['identificationData'])){
            foreach ($data['identificationData'] as $key => $value) {
                $metas = [];
                foreach ($value['value'] as $k => $v) {
                    if(!empty($v)) {
                        $metas[$k] = $v;
                    }
                }
                $attributes[] = new self(
                    ffId: $ffId,
                    key: $value['key'],
                    value: json_encode(array_filter($metas)),
                    onshoreOffShore: $value['onshoreOffShore'],
                    isRequired: $value['isRequired']
                );
            }
        }

        return $attributes;
    }

    public static function fromRequest(Request $request, $ffId): array
    {
        return self::fromArray($request->validated(), $ffId);
    }

    public function toArray(): array
    {
        return [
            'ffId' => $this->ffId,
            'key' => $this->key,
            'value' => $this->value,
            'onshoreOffShore' => $this->onshoreOffShore,
            'isRequired' => $this->isRequired,
        ];
    }
}
