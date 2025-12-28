<?php

namespace App\DTOs\V1\Requests;

use App\Models\QualityCheck;
use Carbon\Carbon;
use Illuminate\Http\Request;

final readonly class RequestQCDTO
{
    public function __construct(
        public int $reqBy,
        public ?int $subBy = null,
        public int $reqId,
        public ?string $descriptionEn = null,
        public ?string $descriptionAr = null,
        public string $meta,
        public string $summary,
        public string $requestedAt,
        public ?string $submittedAt = null,
        public ?string $verifiedAt = null,
        public string $status,
    ) {}

    public static function fromArray(array $data): self
    {
        $statusCounts = array_count_values(array_column($data['qcChecks'], 'status'));

        $totalChecks = count($data['qcChecks']);
        $correctCount = isset($statusCounts['Correct']) ? $statusCounts['Correct'] : 0;
        $wrongCount = isset($statusCounts['Wrong']) ? $statusCounts['Wrong'] : 0;
        $needsCorrectionCount = isset($statusCounts['NeedCorrection']) ? $statusCounts['NeedCorrection'] : 0;
        $remainingCount = $wrongCount + $needsCorrectionCount;
        $completionPercentage = ($correctCount / $totalChecks) * 100;

        $summary = [
            'totalChecks' => $totalChecks,
            'correctCount' => $correctCount,
            'wrongCount' => $wrongCount,
            'needsCorrectionCount' => $needsCorrectionCount,
            'remainingCount' => $remainingCount,
            'completionPercentage' => $completionPercentage
        ];

        return new self(
            reqBy: auth()->id(),
            subBy: NULL,
            reqId: $data['requestId'],
            descriptionEn: $data['descriptionEn'] ?? null,
            descriptionAr: $data['descriptionAr'] ?? null,
            meta: json_encode(array_filter($data['qcChecks'])),
            summary: json_encode(array_filter($summary)),
            requestedAt: Carbon::now(),
            submittedAt: NULL,
            verifiedAt: NULL,
            status: 'Action Required'
        );
    }

    public static function fromRequest(array $request): self
    {
        return self::fromArray($request);
    }

    public static function updateFromRequest(array $qc, array $data)
    {
        $meta = [];

        if(isset($qc['meta'])) {
            $meta = json_decode($qc['meta']);
        }

        $path = [];

        foreach ($meta as $key => $value) {
            if($value->status !== 'Correct'){
                $array = $data;

                $path = explode('.',$value->fieldPath);

                foreach ($path as $segment) {
                    if (preg_match('/(.*?)\[(\d+)\]/', $segment, $matches)) {
                        $arrayKey = $matches[1];
                        $index = $matches[2];

                        $array = $array[$arrayKey][$index] ?? null;
                    } else {
                        $array = $array[$segment] ?? null;
                    }
                }

                $value->fieldNewValue = $array;
            }
        }

        $count = collect($meta)->filter(function ($item) {
            return empty($item->fieldNewValue);
        })->count();

        $summary = [];

        if(isset($qc['summary'])) {
            $summary = json_decode($qc['summary'],true);
            $summary['remainingCount'] = $count;
        }

        return new self(
            reqBy: $qc['reqBy'],
            subBy: auth()->id(),
            reqId: $qc['reqId'],
            descriptionEn: $qc['descriptionEn'],
            descriptionAr: $qc['descriptionAr'],
            meta: json_encode(array_filter($meta)),
            summary: json_encode(array_filter($summary)),
            requestedAt: $qc['requestedAt'],
            submittedAt: Carbon::now(),
            verifiedAt: NULL,
            status: $count > 0 ? 'Action Required' : 'Resubmitted'
        );
    }

    public static function updateDocFromRequest(array $qc, array $meta)
    {
        $count = collect($meta)->filter(function ($item) {
            return empty($item->fieldNewValue);
        })->count();

        return new self(
            reqBy: $qc['reqBy'],
            subBy: auth()->id(),
            reqId: $qc['reqId'],
            descriptionEn: $qc['descriptionEn'],
            descriptionAr: $qc['descriptionAr'],
            meta: json_encode(array_filter($meta)),
            summary: $qc['summary'],
            requestedAt: $qc['requestedAt'],
            submittedAt: Carbon::now(),
            verifiedAt: NULL,
            status: $count > 0 ? 'Action Required' : 'Resubmitted'
        );
    }

    public function toArray(): array
    {
        return [
            'reqBy' => $this->reqBy,
            'subBy' => $this->subBy,
            'reqId' => $this->reqId,
            'descriptionEn' => $this->descriptionEn,
            'descriptionAr' => $this->descriptionAr,
            'meta' => $this->meta,
            'summary' => $this->summary,
            'requestedAt' => $this->requestedAt,
            'submittedAt' => $this->submittedAt,
            'verifiedAt' => $this->verifiedAt,
            'status' => $this->status,
        ];
    }
}
