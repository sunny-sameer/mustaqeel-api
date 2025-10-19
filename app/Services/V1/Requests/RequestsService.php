<?php

namespace App\Services\V1\Requests;


use App\Models\User;


use Illuminate\Http\Request;
use App\Http\Requests\Api\V1\RequestsRequest;
use App\Http\Requests\Api\V1\RequestsDocumentRequest;
use App\Http\Requests\Api\V1\RequestsPartialRequest;


use App\Services\V1\BaseService;
use App\Services\V1\User\UserService;
use App\Services\V1\Artifacts\ArtifactsService;


use App\DTOs\Api\V1\Requests\RequestDTO;
use App\DTOs\Api\V1\Requests\RequestMetasDTO;
use App\DTOs\Api\V1\Requests\RequestAttributesDTO;
use App\DTOs\Api\V1\Requests\RequestStageDTO;
use App\DTOs\Api\V1\Requests\RequestStatusDTO;


use App\Exceptions\BadRequestException;
use App\Exceptions\RequestAlreadyExistException;
use App\Exceptions\UserNotFoundException;


use App\Models\Requests;


use App\Repositories\V1\Admin\GenericInterface;
use App\Repositories\V1\Requests\RequestsInterface;


use Carbon\Carbon;


use Illuminate\Support\Facades\DB;


class RequestsService extends BaseService
{
    const Applicant_REQUEST_ID_PATTERN = 'APP-{YEAR}-{000000}';


    protected $requestsInterface;
    protected $genericInterface;

    protected $artifactsService;
    protected $userService;

    private ?object $user = null;
    private ?object $requests = null;
    private ?string $requestId = null;
    private ?string $status = 'Pending';


    public function __construct(
        RequestsInterface $requestsInterface,
        GenericInterface $genericInterface,

        ArtifactsService $artifactsService,
        UserService $userService
    )
    {
        $this->requestsInterface = $requestsInterface;
        $this->genericInterface = $genericInterface;

        $this->artifactsService = $artifactsService;
        $this->userService = $userService;
    }

    public function setRequestInputs(Request $request)
    {
        $this->requests = $request;
        return $this;
    }

    public function setInputs(RequestsRequest $request, $status): self
    {
        $this->requests = $request;
        $this->status = $status;
        return $this;
    }

    public function setInputsPartial(RequestsPartialRequest $request, $status): self
    {
        $this->requests = $request;
        $this->status = $status;
        return $this;
    }

    public function setInputsDocument(RequestsDocumentRequest $request)
    {
        $this->requests = $request;
        $this->status = 'Draft';
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

    public function getAllRequests()
    {
        $request = $this->requestsInterface->getAllRequests($this->requests);

        return $this->success(
            data: ['request' => $request],
            message: 'Requests fetched successfully'
        );
    }

    public function createRequestReferenceNumber()
    {
        $request = $this->requestsInterface->getLastRequest($this->requests->id);
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

    public function requestAlreadyExists()
    {
        if(isset($this->requests->id) && !empty($this->requests->id)){
            $req = $this->requestsInterface->getRequest($this->requests->id);

            if (isset($req->reqReferenceNumber) && !empty($req->reqReferenceNumber)) {
                throw new RequestAlreadyExistException();
            }
        }

        return $this;
    }

    public function canSubmitResponse($entitySlug)
    {
        $activitiesIds = $this->genericInterface->getAllActivitiesWithEntity($entitySlug);
        $req = $this->requestsInterface->canSubmitRequest($activitiesIds,$entitySlug);
        return response()->json(['canSubmit'=>$req],200);
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
            $request = $this->requestsInterface->updateOrCreateRequest($requestData,$this->requests->id);


            $requestMetaData = RequestMetasDTO::fromRequest($this->requests->all(),$request->id,Requests::class)->toArray();
            $requestAttributesData = collect(RequestAttributesDTO::fromRequest($this->requests,$request->id))
            ->map(fn($dto) => $dto->toArray())
            ->all();


            $requestMeta = $this->requestsInterface->updateOrCreateRequestMetaData($requestMetaData,$request->id,Requests::class);
            $requestAttributes = $this->requestsInterface->updateOrCreateRequestAttributes($requestAttributesData,$request->id);


            $status = $this->createOrUpdateStageStatus('Application',$request->id);


            $response = $this->requestsInterface->getRequest($request->id);
            DB::commit();


            $message = $this->status == 'Draft' ? 'Request partially created successfully' : 'Request created successfully';
            return $this->success(
                data: ['request'=>$response],
                message: $message
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

    public function createOrUpdateStageStatus($stageName, $reqId)
    {
        $stage = $this->requestsInterface->getStage(['name'=>$stageName]);
        $data = ['reqId'=>$reqId,'stageSlug'=>$stage->slug];
        $requestStageData = RequestStageDTO::fromRequest($data)->toArray();
        $requestStage = $this->requestsInterface->createRequestStage($data,$requestStageData);

        $stageStatus = $this->requestsInterface->getStageStatus(['stageId'=>$stage->id,'name'=>$this->status]);
        $data2 = ['reqStageId'=>$requestStage->id,'stageStatusSlug'=>$stageStatus->slug];
        $requestStatusData = RequestStatusDTO::fromRequest($data2)->toArray();
        $requestStatus = $this->requestsInterface->createRequestStageStatus($data2,$requestStatusData,$this->status);


        $stageStatus = $this->requestsInterface->getRequestStatus($reqId);
        return $stageStatus;
    }

    public function createDocument()
    {
        DB::beginTransaction();
        try {
            $request = $this->requestsInterface->updateOrCreateRequest(
                ['userId'=>auth()->id(),'submittedAt'=>Carbon::now()],
                $this->requests->id
            );

            $stageStatus = $this->createOrUpdateStageStatus('Application',$request->id);

            $this->requests['entityId'] = $request->id;
            $this->requests['entityType'] = Requests::class;
            $response = $this->artifactsService->createDocuments($this->requests);

            if(!$response->ok){
                DB::rollBack();

                return $this->error(
                    message: $response->message,
                    errors: $response->ok,
                    statusCode: $response->status
                );
            }

            DB::commit();

            return $this->success(
                data: ['document'=>$response->document],
                message: 'Document created successfully'
            );
        } catch (BadRequestException $e) {
            DB::rollBack();

            return $this->error(
                message: 'Document creation failed',
                errors: $e->getMessage(),
                statusCode: 500
            );
        }
    }

    public function getRequest($id)
    {
        $request = $this->requestsInterface->getRequest($id);

        return $this->success(
            data: ['request' => $request],
            message: 'Request fetched successfully'
        );
    }

    public function getAllNationalities()
    {
        return $this->genericInterface->getAllNationalities();
    }

    public function getAllCategories()
    {
        return $this->genericInterface->getAllCategories();
    }

    public function getAllSectorsSubCategoriesAndIncubators($catSlug)
    {
        return $this->genericInterface->getAllSectorsSubCategoriesAndIncubators($catSlug);
    }

    public function getAllActivities($secSlug)
    {
        return $this->genericInterface->getAllActivities($secSlug);
    }

    public function getAllEntitiesAndSubActivities($actSlug)
    {
        return $this->genericInterface->getAllEntitiesAndSubActivities($actSlug);
    }
}
