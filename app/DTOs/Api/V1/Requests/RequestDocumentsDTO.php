<?php

namespace App\DTOs\Api\V1\Requests;

use App\Models\Requests;
use Illuminate\Http\Request;


final readonly class RequestDTO
{
    public function __construct(
        public int $entityId,
        public string $documentName,
        public string $type,
        public ?string $meta = null,
        public string $entityType,
        public bool $status = true,
    ) {}


    public static function fromArray(array $data, int $entityId): self
    {
        $meta = [
            'extension'=>$data['extension']
        ];
        return new self(
            entityId: $entityId,
            documentName: $data['documentName'],
            type: $data['key'],
            meta: json_encode(array_filter($meta)),
            entityType: Requests::class,
            status: true,
        );
    }

    public static function fromRequest(Request $request, $entityId): self
    {
        return self::fromArray($request->validated(), $entityId);
    }

    public function toArray(): array
    {
        return [
            'entityId' => $this->entityId,
            'documentName' => $this->documentName,
            'type' => $this->type,
            'meta' => $this->meta,
            'entityType' => $this->entityType,
            'status' => $this->status,
        ];
    }
}
