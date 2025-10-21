<?php

namespace App\DTOs\V1\Profile;


use Illuminate\Http\Request;


use Carbon\Carbon;


final readonly class ProfileDTO
{
    public function __construct(
        public int $userId,
        // public ?string $occupation = null,
        public ?string $gender = null,
        public ?string $nationality = null,
        public ?string $countryOfResidence = null,
        public ?string $religion = null,
        public ?Carbon $dob = null,
        public ?string $pob = null,
        public ?string $maritalStatus = null,
        public ?string $shortBiography = null,
        public bool $status = true,
    ) {}


    public static function fromArray(array $data): self
    {
        return new self(
            userId: auth()->id(),
            // occupation: ($data['personalInfo']['applicantInfo']['areYouQatarResident'] && ($data['personalInfo']['identificationData']['category'] == 'tal' || $data['personalInfo']['identificationData']['category'] == 'ent')) ? ($data['employmentAndEducation']['employmentDetails']['profession'] ?? NULL) : NULL,
            gender: $data['personalInfo']['applicantInfo']['gender'] ?? NULL,
            nationality: $data['personalInfo']['applicantInfo']['nationality'] ?? NULL,
            countryOfResidence: $data['personalInfo']['applicantInfo']['currentCountry'] ?? NULL,
            religion: $data['personalInfo']['applicantInfo']['religion'] ?? NULL,
            dob: $data['personalInfo']['applicantInfo']['dob'] ? Carbon::parse($data['personalInfo']['applicantInfo']['dob']) : NULL,
            pob: $data['personalInfo']['applicantInfo']['placeOfBirth'] ?? NULL,
            maritalStatus: $data['personalInfo']['applicantInfo']['maritalStatus'] ?? NULL,
            shortBiography: $data['personalInfo']['applicantInfo']['shortBio'] ?? NULL,
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
            // 'occupation' => $this->occupation,
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
