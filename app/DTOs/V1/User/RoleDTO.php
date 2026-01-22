<?php

namespace App\DTOs\V1\User;

use Illuminate\Http\Request;

final readonly class RoleDTO
{
    public function __construct(
        public string $name,
        public string $guard_name,
        public string $type,
        public string $approval_levels,
    ) {}


    public static function fromArray(array $data, $id=0): self
    {
        return new self(
            name: $data['name'],
            guard_name: 'web',
            type: $data['type'],
            approval_levels: $data['approvalLevels'],
        );
    }

    public static function fromRequest(Request $request, $id=0): self
    {
        return self::fromArray($request->validated(), $id);
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'guard_name' => $this->guard_name,
            'type' => $this->type,
            'approval_levels' => $this->approval_levels,
        ];
    }
}
