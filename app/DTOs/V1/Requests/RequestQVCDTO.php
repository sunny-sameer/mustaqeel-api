<?php

namespace App\DTOs\V1\Requests;

use Illuminate\Http\Request;

final readonly class RequestQVCDTO
{
    public function __construct(
        public string $fieldName,
        public string $fieldPath,
        public string $status, // 'correct', 'wrong', 'needs_correction'
        public ?string $commentsEn = null,
        public ?string $commentsAr = null,
        public ?array $corrections = null,
        public ?string $verifiedBy = null,
        public ?string $verifiedAt = null
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            fieldName: $data['fieldName'],
            fieldPath: $data['fieldPath'],
            status: $data['status'],
            commentsEn: $data['commentsEn'] ?? null,
            commentsAr: $data['commentsAr'] ?? null,
            corrections: $data['corrections'] ?? null,
            verifiedBy: $data['verifiedBy'] ?? auth()->user()->name ?? 'System',
            verifiedAt: $data['verifiedAt'] ?? now()->toDateTimeString()
        );
    }

    public function toArray(): array
    {
        return [
            'fieldName' => $this->fieldName,
            'fieldPath' => $this->fieldPath,
            'status' => $this->status,
            'commentsEn' => $this->commentsEn,
            'commentsAr' => $this->commentsAr,
            'corrections' => $this->corrections,
            'verifiedBy' => $this->verifiedBy,
            'verifiedAt' => $this->verifiedAt
        ];
    }
}