<?php

namespace App\DTOs\V1\Requests;


use Carbon\Carbon;


final readonly class RequestStatusDTO
{
    public function __construct(
        public int $userId,
        public int $reqStageId,
        public string $stageStatusSlug,
        public Carbon $startDate,
        public ?Carbon $endDate = null,
        public ?string $commentsEn = null,
        public ?string $commentsAr = null,
        public bool $status = true,
    ) {}


    public static function fromArray(array $data): self
    {
        return new self(
            userId: auth()->id(),
            reqStageId: $data['reqStageId'],
            stageStatusSlug: $data['stageStatusSlug'],
            startDate: Carbon::now(),
            endDate: isset($data['endDate']) ? Carbon::parse($data['endDate']) : NULL,
            commentsEn: isset($data['commentsEn']) ? $data['commentsEn'] : NULL,
            commentsAr: isset($data['commentsAr']) ? $data['commentsAr'] : NULL,
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
            'userId' => $this->userId,
            'reqStageId' => $this->reqStageId,
            'stageStatusSlug' => $this->stageStatusSlug,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'commentsEn' => $this->commentsEn,
            'commentsAr' => $this->commentsAr,
            'status' => $this->status,
        ];
    }
}
