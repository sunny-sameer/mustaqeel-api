<?php

namespace App\Services\V1\Requests;

use App\DTOs\Api\V1\AddressDTO;
use App\DTOs\Api\V1\CommunicationDTO;
use App\DTOs\Api\V1\PassportDTO;
use App\DTOs\Api\V1\ProfileDTO;
use App\DTOs\Api\V1\QatarInfoDTO;
use App\Exceptions\UserNotFoundException;
use App\Services\V1\BaseService;


use App\Http\Requests\Api\V1\RequestsRequest;


use App\Repositories\V1\Requests\RequestsInterface;
use App\Repositories\V1\Users\UsersInterface;

class RequestsService extends BaseService
{
    protected $requestsInterface;
    protected $userInterface;

    private ?object $user = null;
    private ?object $requests = null;


    public function __construct(RequestsInterface $requestsInterface, UsersInterface $userInterface)
    {
        $this->requestsInterface = $requestsInterface;
        $this->userInterface = $userInterface;
    }


    public function setInputs(RequestsRequest $request): self
    {
        $this->requests = $request;
        return $this;
    }

    public function userExists()
    {
        $id = auth()->id();
        $user = $this->userInterface->show($id);

        if (!$user) {
            throw new UserNotFoundException();
        }

        $this->user = $user;

        return $this;
    }

    public function userProfile()
    {
        $error = '';

        try {
            $profileData = ProfileDTO::fromRequest($this->requests)->toArray();
        } catch (\Throwable $e) {
            $error = $e->getMessage();
        }

        try {
            $passportData = PassportDTO::fromRequest($this->requests)->toArray();
        } catch (\Throwable $e) {
            $error = $e->getMessage();
        }

        try {
            $commsData = CommunicationDTO::fromRequest($this->requests)->toArray();
        } catch (\Throwable $e) {
            $error = $e->getMessage();
        }

        try {
            $addressData = AddressDTO::fromRequest($this->requests)->toArray();
        } catch (\Throwable $e) {
            $error = $e->getMessage();
        }

        try {
            $qatarInfoData = QatarInfoDTO::fromRequest($this->requests)->toArray();
        } catch (\Throwable $e) {
            $error = $e->getMessage();
        }

        if (!empty($error)) {
            throw new \Exception($error);
        }

        $profile = $this->userInterface->createUpdateProfile($profileData,$this->user->id);
        $passport = $this->userInterface->createUpdatePassport($passportData,$this->user->id);
        $comms = $this->userInterface->createUpdateComms($commsData,$this->user->id);
        $address = $this->userInterface->createUpdateAddress($addressData,$this->user->id);
        $qatarInfo = $this->userInterface->createUpdateQatarInfo($qatarInfoData,$this->user->id);

        return $this->requests;
    }
}
