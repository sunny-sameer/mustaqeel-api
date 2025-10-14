<?php

namespace App\DTOs\Api\V1\Requests;


use Carbon\Carbon;


final readonly class RequestStageDTO
{
    public function __construct(
        public int $reqId,
        public string $stageSlug,
        public Carbon $startDate,
        public ?Carbon $endDate = null,
        public bool $status = true,
    ) {}


    public static function fromArray(array $data): self
    {
        return new self(
            reqId: $data['reqId'],
            stageSlug: $data['stageSlug'],
            startDate: Carbon::now(),
            endDate: isset($data['endDate']) ? Carbon::parse($data['endDate']) : NULL,
            status: true,
        );
    }

    public static function fromRequest($data): self
    {
        return self::fromArray($data);
    }

    public function toArray(): array
    {
        return [
            'reqId' => $this->reqId,
            'stageSlug' => $this->stageSlug,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'status' => $this->status,
        ];
    }
}
