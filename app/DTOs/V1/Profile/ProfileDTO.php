<?php

namespace App\DTOs\V1\Profile;

use App\Models\Profiles;
use Illuminate\Http\Request;


use Carbon\Carbon;


final readonly class ProfileDTO
{
    public function __construct(
        public int $userId,
        public ?string $gender = null,
        public ?string $nationality = null,
        public ?string $countryOfResidence = null,
        public ?string $religion = null,
        public ?string $dob = null,
        public ?string $pob = null,
        public ?string $maritalStatus = null,
        public ?string $shortBiography = null,
        public bool $status = true,
    ) {}


    public static function fromArray(array $data): self
    {
        $profile = Profiles::where('userId',auth()->id())->first();
        return new self(
            userId: auth()->id(),
            gender: isset($data['personalInfo']['applicantInfo']['gender']) ? $data['personalInfo']['applicantInfo']['gender'] : ($profile->gender ?? NULL),
            nationality: isset($data['personalInfo']['applicantInfo']['nationality']) ? $data['personalInfo']['applicantInfo']['nationality'] : ($profile->nationality ?? NULL),
            countryOfResidence: isset($data['personalInfo']['applicantInfo']['currentCountry']) ? $data['personalInfo']['applicantInfo']['currentCountry'] : ($profile->countryOfResidence ?? NULL),
            religion: isset($data['personalInfo']['applicantInfo']['religion']) ? $data['personalInfo']['applicantInfo']['religion'] : ($profile->religion ?? NULL),
            dob: isset($data['personalInfo']['applicantInfo']['dob']) ? Carbon::parse($data['personalInfo']['applicantInfo']['dob']) : ($profile->dob ?? NULL),
            pob: isset($data['personalInfo']['applicantInfo']['placeOfBirth']) ? $data['personalInfo']['applicantInfo']['placeOfBirth'] : ($profile->pob ?? NULL),
            maritalStatus: isset($data['personalInfo']['applicantInfo']['maritalStatus']) ? $data['personalInfo']['applicantInfo']['maritalStatus'] : ($profile->maritalStatus ?? NULL),
            shortBiography: isset($data['personalInfo']['applicantInfo']['shortBio']) ? $data['personalInfo']['applicantInfo']['shortBio'] : ($profile->shortBiography ?? NULL),
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
