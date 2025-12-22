<?php

namespace App\DTOs\V1\Profile;

use App\Models\Addresses;
use Illuminate\Http\Request;


final readonly class AddressDTO
{
    public function __construct(
        public int $userId,
        public ?string $zip = null,
        public ?string $address = null,
        public bool $status = true,
    ) {}


    public static function fromArray(array $data): self
    {
        $address = Addresses::where('userId',auth()->id())->first();
        return new self(
            userId: auth()->id(),
            zip: isset($data['personalInfo']['contactInfo']['poBox']) ? $data['personalInfo']['contactInfo']['poBox'] : ($address->zip ?? NULL),
            address: isset($data['personalInfo']['contactInfo']['permanentAddress']) ? $data['personalInfo']['contactInfo']['permanentAddress'] : ($address->address ?? NULL),
            status: true,
        );
    }

    public static function fromRequest(Request $request): self
    {
        return self::fromArray($request->validated());
    }

    public function toArray(): array
    {
        return [
            'userId' => $this->userId,
            'zip' => $this->zip,
            'address' => $this->address,
            'status' => $this->status,
        ];
    }
}
