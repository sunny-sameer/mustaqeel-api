<?php

namespace App\DTOs\Api\V1\Profile;


use Illuminate\Http\Request;


use Carbon\Carbon;


final readonly class ProfileDTO
{
    public function __construct(
        public int $userId,
        public ?string $occupation = null,
        public string $gender,
        public string $nationality,
        public string $countryOfResidence,
        public string $religion,
        public Carbon $dob,
        public string $pob,
        public string $maritalStatus,
        public string $shortBiography,
        public bool $status = true,
    ) {}


    public static function fromArray(array $data): self
    {
        return new self(
            userId: auth()->id(),
            occupation: ($data['personalInfo']['applicantInfo']['areYouQatarResident'] && ($data['personalInfo']['identificationData']['category'] == 'tal' || $data['personalInfo']['identificationData']['category'] == 'ent')) ? ($data['personalInfo']['employmentDetails']['profession'] ?? NULL) : NULL,
            gender: $data['personalInfo']['applicantInfo']['gender'],
            nationality: $data['personalInfo']['applicantInfo']['nationality'],
            countryOfResidence: $data['personalInfo']['applicantInfo']['currentCountry'],
            religion: $data['personalInfo']['applicantInfo']['religion'],
            dob: Carbon::parse($data['personalInfo']['applicantInfo']['dob']),
            pob: $data['personalInfo']['applicantInfo']['placeOfBirth'],
            maritalStatus: $data['personalInfo']['applicantInfo']['maritalStatus'],
            shortBiography: $data['personalInfo']['applicantInfo']['shortBio'],
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
            'occupation' => $this->occupation,
            'gender' => $this->gender,
            'nationality' => $this->nationality,
            'countryOfResidence' => $this->countryOfResidence,
            'religion' => $this->religion,
            'dob' => $this->dob,
            'pob' => $this->pob,
            'maritalStatus' => $this->maritalStatus,
            'shortBiography' => $this->shortBiography,
            'status' => $this->status,
        ];
    }
}
