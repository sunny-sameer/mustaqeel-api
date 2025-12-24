<?php

namespace App\Services\V1\Requests;


use App\Models\User;
use App\Models\Requests;


use Illuminate\Http\Request;
use App\Http\Requests\API\V1\RequestsStoreRequest;
use App\Http\Requests\API\V1\RequestsDocumentRequest;
use App\Http\Requests\API\V1\RequestsPartialRequest;
use App\Http\Requests\API\V1\RequestsUpdateRequest;
use App\Http\Requests\API\V1\ReuploadDocumentRequest;
use App\Http\Requests\API\V1\RequestsQualityCheck;


use App\Services\V1\BaseService;
use App\Services\V1\User\UserService;
use App\Services\V1\Artifacts\ArtifactsService;


use App\DTOs\V1\Requests\RequestDTO;
use App\DTOs\V1\Requests\RequestMetasDTO;
use App\DTOs\V1\Requests\RequestAttributesDTO;
use App\DTOs\V1\Requests\RequestStageDTO;
use App\DTOs\V1\Requests\RequestStatusDTO;
use App\DTOs\V1\Requests\RequestQCDTO;


use App\Exceptions\BadRequestException;
use App\Exceptions\RequestAlreadyExistException;
use App\Exceptions\RequestNotExistException;
use App\Exceptions\RequestQcAlreadyExistException;
use App\Exceptions\RequestQcNotExistException;
use App\Exceptions\UserNotFoundException;
use App\Models\QualityCheck;
use App\Repositories\V1\Admin\GenericInterface;
use App\Repositories\V1\Artifacts\ArtifactsInterface;
use App\Repositories\V1\Requests\RequestsInterface;


use Carbon\Carbon;


use Illuminate\Support\Facades\DB;


class RequestsService extends BaseService
{
    const Applicant_REQUEST_ID_PATTERN = 'APP-{YEAR}-{000000}';


    protected $requestsInterface;
    protected $genericInterface;
    protected $artifactsInterface;

    protected $artifactsService;
    protected $userService;

    private ?object $user = null;
    private ?object $requests = null;
    private ?object $requestsQc = null;
    private ?string $requestId = null;
    private ?string $referenceNumber = null;
    private ?string $status = 'Under Review';


    public function __construct(
        RequestsInterface $requestsInterface,
        GenericInterface $genericInterface,
        ArtifactsInterface $artifactsInterface,

        ArtifactsService $artifactsService,
        UserService $userService
    ) {
        $this->requestsInterface = $requestsInterface;
        $this->genericInterface = $genericInterface;
        $this->artifactsInterface = $artifactsInterface;

        $this->artifactsService = $artifactsService;
        $this->userService = $userService;
    }

    public function setRequestInputs(Request $request)
    {
        $this->requests = $request;
        return $this;
    }

    public function setInputs(RequestsStoreRequest $request, $status): self
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

    public function setInputsUpdateRequest(RequestsUpdateRequest $request, $id): self
    {
        $this->requests = $request;
        $this->requestId = $id;
        return $this;
    }

    public function setInputsDocument(RequestsDocumentRequest $request)
    {
        $this->requests = $request;
        $this->status = 'Draft';
        return $this;
    }

    public function setReuploadInputsDocument(ReuploadDocumentRequest $request, $id)
    {
        $this->requests = $request;
        $this->status = 'Reupload Documents Requested';
        $this->requestId = $id;
        return $this;
    }

    public function setQCRequestInputs(RequestsQualityCheck $request)
    {
        $this->requests = $request;
        $this->requestId = $request->requestId;
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

    public function requestAlreadyExists()
    {
        if (isset($this->requests->id) && !empty($this->requests->id)) {
            $req = $this->requestsInterface->show($this->requests->id);

            if (isset($req->reqReferenceNumber) && !empty($req->reqReferenceNumber)) {
                $this->requestsQc = $this->requestsInterface->getQc($this->requestId,'Action Required');
                if(!isset($this->requestsQc->id)){
                    throw new RequestAlreadyExistException();
                }
            }
        }

        return $this;
    }

    public function requestNotFound()
    {
        $request = $this->requestsInterface->show($this->requestId);

        if (!$request) {
            throw new RequestNotExistException();
        }

        $this->referenceNumber = $request->reqReferenceNumber;
        return $this;
    }

    public function requestQcAlreadyExists()
    {
        $this->requestsQc = $this->requestsInterface->getQc($this->requestId,'Action Required');
        if(isset($this->requestsQc->id)){
            throw new RequestQcAlreadyExistException();
        }

        return $this;
    }

    public function requestQcNotFound()
    {
        $this->requestsQc = $this->requestsInterface->getQc($this->requestId,'Action Required');
        if(!isset($this->requestsQc->id)){
            throw new RequestQcNotExistException();
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
        if (isset($request->reqReferenceNumber)) {
            $requestOldIdArr = explode('-', $request->reqReferenceNumber);
            $requestOldId = intval($requestOldIdArr[2]);
            if ($requestOldId > 1000) {
                $requestId = $requestOldId;
            }
        }
        $requestIdNumber = sprintf('%06d', $requestId + 1);
        $this->referenceNumber = str_replace(['{YEAR}', '{000000}'], [Carbon::now()->format('Y'), $requestIdNumber], self::Applicant_REQUEST_ID_PATTERN);

        return $this;
    }

    public function deleteDocumentsIfExist()
    {
        $this->artifactsInterface->deleteDocuments($this->requests, Requests::class);
        return $this;
    }

    public function canSubmitResponse($entitySlug)
    {
        $activitiesIds = $this->genericInterface->getAllActivitiesWithEntity($entitySlug);
        $req = $this->requestsInterface->canSubmitRequest($activitiesIds, $entitySlug);
        return response()->json(['canSubmit' => $req], 200);
    }

    public function createRequest()
    {
        DB::beginTransaction();

        try {
            $role = $this->user->roles->pluck('name')->first();
            if ($role == 'applicant') {
                $profile = $this->userService->userProfileCreateOrUpdate($this->requests);
            }

            $requestData = RequestDTO::fromRequest($this->requests, $this->referenceNumber)->toArray();
            $request = $this->requestsInterface->updateOrCreateRequest($requestData, $this->requests->id);


            $requestMetaData = collect(RequestMetasDTO::fromRequest($this->requests, $request->id))
                ->map(fn($dto) => $dto->toArray())
                ->all();
            $requestAttributesData = collect(RequestAttributesDTO::fromRequest($this->requests, $request->id))
                ->map(fn($dto) => $dto->toArray())
                ->all();


            $this->requestsInterface->updateOrCreateRequestMetaData($requestMetaData, $request->id);
            $this->requestsInterface->updateOrCreateRequestAttributes($requestAttributesData, $request->id);


            $this->createOrUpdateStageStatus('Application', $request->id, []);

            DB::commit();

            $response =  $this->requestsInterface->getRequest($request->id);
            $message = $this->status = 'Draft' ? 'Request partially created successfully' : 'Request created successfully';
            return $this->success(
                data: ['request' => $response],
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

    public function updateRequest()
    {
        DB::beginTransaction();

        try {

            $role = $this->user->roles->pluck('name')->first();
            if ($role == 'applicant') {
                $profile = $this->userService->userProfileCreateOrUpdate($this->requests);
            }

            $requestData = RequestDTO::updateFromArray($this->requests->all(), $this->referenceNumber)->toArray();

            $request = $this->requestsInterface->updateOrCreateRequest($requestData, $this->requestId);

            // $requestMetaData = collect(RequestMetasDTO::fromRequest($this->requests, $request->id))
            //     ->map(fn($dto) => $dto->toArray())
            //     ->all();
            $requestAttributesData = collect(RequestAttributesDTO::updateFromArray($this->requests->all(), $request->id))
                ->map(fn($dto) => $dto->toArray())
                ->all();


            // $this->requestsInterface->updateOrCreateRequestMetaData($requestMetaData, $request->id);
            $this->requestsInterface->updateOrCreateRequestAttributes($requestAttributesData, $request->id);

            $qcData = RequestQCDTO::updateFromRequest($this->requestsQc->toArray(),$this->requests->all())->toArray();

            $this->requestsInterface->updateQc($qcData, $this->requestsQc->id);

            DB::commit();

            $response =  $this->requestsInterface->getRequest($request->id);
            return $this->success(
                data: ['request' => $response],
                message: 'Request updated successfully'
            );
        } catch (BadRequestException $e) {
            DB::rollBack();

            return $this->error(
                message: 'Request updation failed',
                errors: $e->getMessage(),
                statusCode: 500
            );
        }
    }

    public function createOrUpdateStageStatus($stageName, $reqId, $metaData)
    {
        $stage = $this->requestsInterface->getStage(['name' => $stageName]);
        $data = ['reqId' => $reqId, 'stageSlug' => $stage->slug];
        $requestStageData = RequestStageDTO::fromRequest($data)->toArray();
        $requestStage = $this->requestsInterface->createRequestStage($data, $requestStageData);

        $stageStatus = $this->requestsInterface->getStageStatus(['stageId' => $stage->id, 'name' => $this->status]);

        $meta = [];
        if (!empty($metaData)) {
            foreach ($metaData as $key => $value) {
                $meta['comments'][$key][$value['type'] . 'En'] = $value['commentsEn'];
                $meta['comments'][$key][$value['type'] . 'Ar'] = $value['commentsAr'];
            }
        }

        $data2 = [
            'reqStageId' => $requestStage->id,
            'stageStatusSlug' => $stageStatus->slug,
            'userId' => auth()->id(),
            'meta' => $meta,
        ];


        if ($stageName == 'Application') {
            $request = $this->requestsInterface->show($reqId);
            $data2['userId'] = $request->userId;
        }

        $requestStatusData = RequestStatusDTO::fromRequest($data2)->toArray();
        $this->requestsInterface->createRequestStageStatus($data2, $requestStatusData, $this->status);

        $stageStatus = $this->requestsInterface->getRequestStatus($reqId);
        return $stageStatus;
    }

    public function createDocument()
    {
        DB::beginTransaction();
        try {
            $request = $this->requestsInterface->updateOrCreateRequest(
                ['userId' => auth()->id(), 'submittedAt' => Carbon::now()],
                $this->requests->id
            );

            $this->requests['entityId'] = $request->id;
            $this->requests['entityType'] = Requests::class;
            $response = $this->artifactsService->createDocuments($this->requests);

            if (!$response->ok) {
                DB::rollBack();

                return $this->error(
                    message: $response->message,
                    errors: $response->ok,
                    statusCode: $response->status
                );
            }

            DB::commit();

            return $this->success(
                data: ['document' => $response->document],
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

    public function reuploadDocumentRequest()
    {
        DB::beginTransaction();

        try {
            $this->createOrUpdateStageStatus('Application', $this->requestId, $this->requests->reuploadDocument);
            $this->createOrUpdateStageStatus('Jusour', $this->requestId, $this->requests->reuploadDocument);

            $this->artifactsInterface->updateDocuments($this->requests, $this->requestId, Requests::class);

            $request = $this->requestsInterface->getRequest($this->requestId);

            DB::commit();

            return $this->success(
                data: ['request' => $request],
                message: 'Reupload document request submitted successfully'
            );
        } catch (BadRequestException $e) {
            DB::rollBack();

            return $this->error(
                message: 'Reupload document creation failed',
                errors: $e->getMessage(),
                statusCode: 500
            );
        }
    }

    public function deleteDocumentById($id)
    {
        DB::beginTransaction();
        try {
            $name = $this->artifactsInterface->deleteDocumentById($id);

            DB::commit();

            return $this->success(
                message: $name . ' has been deleted successfully'
            );
        } catch (BadRequestException $e) {
            DB::rollBack();

            return $this->error(
                message: 'Document deletion failed',
                errors: $e->getMessage(),
                statusCode: 500
            );
        }
    }

    public function submitQC()
    {
        DB::beginTransaction();

        try {
            $response = $this->requestsInterface->getRequest($this->requestId);

            $request = $this->requests->all();

            foreach ($request['qcChecks'] as $key => $value) {
                $path = explode('.',$value['fieldPath']);
                if(count($path) == 2){
                    $request['qcChecks'][$key]['fieldOldValue'] = $response[$path[0]][$path[1]];
                }else{
                    $request['qcChecks'][$key]['fieldOldValue'] = $response[$path[0]][$path[1]][$path[2]];
                }
            }

            $qcData = RequestQCDTO::fromRequest($request)->toArray();

            $this->requestsInterface->createQc($qcData);

            $this->createOrUpdateStageStatus('Jusour', $this->requestId, []);


            DB::commit();

            return $this->success(
                data: ['request' => $response],
                message: 'QC submitted successfully'
            );
        } catch (BadRequestException $e) {
            DB::rollBack();

            return $this->error(
                message: 'QC submission failed',
                errors: $e->getMessage(),
                statusCode: 500
            );
        }
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

    public function getFormFields($request)
    {
        return $this->genericInterface->getFormFields($request);
    }
}
