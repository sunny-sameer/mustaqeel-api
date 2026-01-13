<?php

namespace App\DTOs\V1\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

final readonly class UserDTO
{
    public function __construct(
        public string $name,
        public ?string $nameArabic = null,
        public string $email,
        public string $password,
        public int $termsAccepted = 0,
        public string $status,
    ) {}


    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['personalInfo']['name'],
            nameArabic: $data['personalInfo']['nameArabic'],
            email: $data['personalInfo']['email'],
            password: Hash::make($data['personalInfo']['password']),
            termsAccepted: 0,
            status: $data['personalInfo']['status'],
        );
    }

    public static function fromRequest(Request $request): self
    {
        return self::fromArray($request->validated());
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'nameArabic' => $this->nameArabic,
            'email' => $this->email,
            'password' => $this->password,
            'termsAccepted' => $this->termsAccepted,
            'status' => $this->status,
        ];
    }
}
