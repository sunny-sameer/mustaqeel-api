<?php

namespace App\Services\V1\Requests;


use App\Models\User;


use App\Http\Requests\Api\V1\RequestsRequest;


use App\Services\V1\BaseService;
use App\Services\V1\User\UserService;


use App\DTOs\Api\V1\RequestAttributesDTO;
use App\DTOs\Api\V1\RequestDTO;
use App\DTOs\Api\V1\RequestMetasDTO;


use App\Exceptions\BadRequestException;
use App\Exceptions\UserNotFoundException;


use App\Repositories\V1\Admin\GenericInterface;
use App\Repositories\V1\Requests\RequestsInterface;


use Carbon\Carbon;


use Illuminate\Support\Facades\DB;


class RequestsService extends BaseService
{
    const Applicant_REQUEST_ID_PATTERN = 'APP-{YEAR}-{000000}';


    protected $requestsInterface;
    protected $genericInterface;

    protected $userService;

    private ?object $user = null;
    private ?object $requests = null;
    private ?string $requestId = null;


    public function __construct(RequestsInterface $requestsInterface, GenericInterface $genericInterface, UserService $userService)
    {
        $this->requestsInterface = $requestsInterface;
        $this->genericInterface = $genericInterface;

        $this->userService = $userService;
    }


    public function setInputs(RequestsRequest $request): self
    {
        $this->requests = $request;
        return $this;
    }

    public function userExists()
    {
        $this->user = User::with('profile')->find(auth()->id());

        if (!$this->user) {
            throw new UserNotFoundException();
        }

        return $this;
    }

    public function createRequestReferenceNumber()
    {
        $request = $this->requestsInterface->getLastRequest();
        $requestId = 1000;
        if(isset($request->reqReferenceNumber))
        {
            $requestOldIdArr = explode('-',$request->reqReferenceNumber);
            $requestOldId = intval($requestOldIdArr[2]);
            if($requestOldId > 1000)
            {
                $requestId = $requestOldId;
            }
        }
        $requestIdNumber = sprintf('%06d', $requestId + 1);
        $this->requestId = str_replace(['{YEAR}', '{000000}'], [Carbon::now()->format('Y'), $requestIdNumber], self::Applicant_REQUEST_ID_PATTERN);

        return $this;
    }


    public function createRequest()
    {
        DB::beginTransaction();

        try {
            $role = $this->user->roles->pluck('name')->first();
            if($role == 'applicant'){
                $profile = $this->userService->userProfileCreateOrUpdate($this->requests);
            }


            $requestData = RequestDTO::fromRequest($this->requests,$this->requestId)->toArray();
            $request = $this->requestsInterface->store($requestData);


            $requestMetaData = RequestMetasDTO::fromRequest($this->requests,$request->id)->toArray();
            $requestAttributesData = collect(RequestAttributesDTO::fromRequest($this->requests,$request->id))
            ->map(fn($dto) => $dto->toArray())
            ->all();


            $requestMeta = $this->requestsInterface->createRequestMetaData($requestMetaData);
            $requestAttributes = $this->requestsInterface->createRequestAttributes($requestAttributesData);


            DB::commit();


            return $this->success(
                data: ['request'=>$request,'requestMeta'=>$requestMeta,'requestAttributes'=>$requestAttributes],
                message: 'Request created successfully'
            );
        } catch (BadRequestException $e) {
            DB::rollBack();

            return $this->error(
                message: 'Request creation failed',
                errors: $e->getMessage(),
                statusCode: 500
            );
        }
    }

    public function getAllCategories()
    {
        return $this->genericInterface->getAllCategories();
    }

    public function getAllSectorsSubCategoriesAndIncubators($catId)
    {
        return $this->genericInterface->getAllSectorsSubCategoriesAndIncubators($catId);
    }

    public function getAllActivities($secId)
    {
        return $this->genericInterface->getAllActivities($secId);
    }

    public function getAllEntitiesAndSubActivities($actId)
    {
        return $this->genericInterface->getAllEntitiesAndSubActivities($actId);
    }
}
