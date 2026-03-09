<?php

namespace App\Http\Controllers\Api\V1\Requests;

use App\Exceptions\BadRequestException;
use App\Exceptions\RequestAlreadyExistException;
use App\Exceptions\RequestInvalidException;
use App\Exceptions\RequestNotExistException;
use App\Exceptions\RequestQcAlreadyExistException;
use App\Exceptions\RequestQcNotExistException;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\StageStatusNotFoundException;


use App\Http\Controllers\Api\BaseController;


use Illuminate\Http\Request;
use App\Http\Requests\API\V1\RequestsStoreRequest;
use App\Http\Requests\API\V1\RequestsDocumentRequest;
use App\Http\Requests\API\V1\RequestsPartialRequest;
use App\Http\Requests\API\V1\RequestsUpdateRequest;
use App\Http\Requests\API\V1\RequestStatusUpdateRequest;
use App\Http\Requests\API\V1\ReuploadDocumentRequest;
use App\Http\Requests\API\V1\RequestsQualityCheck;


use App\Services\V1\Requests\RequestsService;
use App\Services\V1\Documents\DocumentService;


use Symfony\Component\HttpFoundation\StreamedResponse;


class RequestsController extends BaseController
{
    /**
     * See Swagger annotations in \App\Swaggers\V1\Requests\RequestsSwagger
     * See Swagger annotations in \App\Swaggers\V1\Admin\AdminRequestsSwagger
     */


    protected $status = 'dra';
    protected $requests;
    protected $documentService;



    public function __construct(RequestsService $requests, DocumentService $documentService)
    {
        $this->requests = $requests;
        $this->documentService = $documentService;
    }

    public function getAllRequests(Request $request)
    {
        try {
            return $this->requests
                ->setRequestInputs($request)
                ->userExists()
                ->getAllRequests();
        } catch (UserNotFoundException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 403);
        }
    }

    public function getRequest($id)
    {
        try {
            return $this->requests
                ->userExists()
                ->getRequest($id);
        } catch (UserNotFoundException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 403);
        }
    }

    public function createRequestPartially(RequestsPartialRequest $request)
    {
        try {
            return $this->requests
                ->setInputsPartial($request, $this->status)
                ->userExists()
                ->requestAlreadyExists()
                ->deleteDocumentsIfExist()
                ->createRequest();
        } catch (UserNotFoundException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (RequestAlreadyExistException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 409);
        } catch (BadRequestException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 400);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 403);
        }
    }

    public function createRequest(RequestsStoreRequest $request)
    {
        try {
            $this->status = 'ur';
            return $this->requests
                ->setInputs($request, $this->status)
                ->userExists()
                ->createRequestReferenceNumber()
                ->requestAlreadyExists()
                ->deleteDocumentsIfExist()
                ->createRequest();
        } catch (UserNotFoundException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (RequestAlreadyExistException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 409);
        } catch (BadRequestException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 400);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 403);
        }
    }

    public function updateRequest(RequestsUpdateRequest $request, $id)
    {
        try {
            return $this->requests
                ->setInputsUpdateRequest($request, $id)
                ->userExists()
                ->requestNotFound()
                ->requestQcNotFound('Action Required')
                ->updateRequest();
        } catch (UserNotFoundException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (RequestNotExistException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (RequestQcNotExistException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (BadRequestException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 400);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 403);
        }
    }

    public function createRequestDocument(RequestsDocumentRequest $request)
    {
        try {
            return $this->requests
                ->setInputsDocument($request)
                ->userExists()
                ->requestAlreadyExistsForDocuments()
                ->createDocument();
        } catch (UserNotFoundException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (RequestAlreadyExistException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 409);
        } catch (BadRequestException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 400);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 403);
        }
    }

    public function canSubmitApplication($entitySlug)
    {
        try {
            return $this->requests
                ->userExists()
                ->canSubmitResponse($entitySlug);
        } catch (UserNotFoundException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 403);
        }
    }

    public function updateStatus(RequestStatusUpdateRequest $request, $id)
    {
        try {
            return $this->requests
                ->setInputsUpdateRequestStatus($request, $id)
                ->userExists()
                ->stageStatus($request->status)
                ->requestNotFound()
                ->requestInvalid()
                ->updateRequestStatus();
        } catch (UserNotFoundException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (StageStatusNotFoundException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 422);
        } catch (RequestNotExistException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (RequestInvalidException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 422);
        } catch (BadRequestException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 400);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 403);
        }
    }

    public function reuploadDocumentRequest(ReuploadDocumentRequest $request, $id)
    {
        try {
            return $this->requests
                ->setReuploadInputsDocument($request, $id)
                ->userExists()
                ->requestNotFound()
                ->reuploadDocumentRequest();
        } catch (UserNotFoundException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (RequestNotExistException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (BadRequestException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 400);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 403);
        }
    }

    public function deleteDocumentRequest($id)
    {
        try {
            return $this->requests
                ->userExists()
                ->requestNotFound()
                ->deleteDocumentById($id);
        } catch (BadRequestException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 400);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 403);
        }
    }

    public function getQc(Request $request)
    {
        try {
            $status = isset($request->status) ? $request->status : 'Action Required';
            return $this->requests
                ->setRequestIdInputs($request)
                ->userExists()
                ->requestNotFound()
                ->requestQcNotFound($status)
                ->getQcRequest();
        } catch (UserNotFoundException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (RequestNotExistException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (RequestQcNotExistException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 403);
        }
    }

    public function submitQC(RequestsQualityCheck $request)
    {
        try {
            return $this->requests
                ->setQCRequestInputs($request)
                ->userExists()
                ->requestNotFound()
                ->requestQcAlreadyExists('Action Required')
                ->submitQC();
        } catch (UserNotFoundException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (RequestNotExistException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (RequestQcAlreadyExistException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (BadRequestException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 400);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 403);
        }
    }

    public function approveQC(Request $request)
    {
        try {
            return $this->requests
                ->setRequestIdInputs($request)
                ->userExists()
                ->requestNotFound()
                ->requestQcNotFound('Resubmitted')
                ->approveQC();
        } catch (UserNotFoundException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (RequestNotExistException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (RequestQcNotExistException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (BadRequestException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 400);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 403);
        }
    }

    public function requestsCount()
    {
        try {
            return $this->requests
                ->userExists()
                ->getAllRequestsCount();
        } catch (UserNotFoundException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 403);
        }
    }

    public function getAllNationalities()
    {
        return $this->sendSuccessResponse($this->requests->getAllNationalities());
    }

    public function getAllCategories()
    {
        return $this->sendSuccessResponse($this->requests->getAllCategories());
    }

    public function getAllSectorsSubCategoriesAndIncubators($catSlug)
    {
        if (empty($catSlug)) return $this->sendErrorResponse('Invalid category slug', 'Invalid category slug', 400);

        $data = $this->requests->getAllSectorsSubCategoriesAndIncubators($catSlug);
        if (!$data) return $this->sendErrorResponse('Invalid category slug', 'Invalid category slug', 400);

        return $this->sendSuccessResponse($data);
    }

    public function getAllActivities($secSlug)
    {
        if (empty($secSlug)) return $this->sendErrorResponse('Invalid sector slug', 'Invalid sector slug', 400);

        $data = $this->requests->getAllActivities($secSlug);
        if (!$data) return $this->sendErrorResponse('Invalid sector slug', 'Invalid sector slug', 400);

        return $this->sendSuccessResponse($data);
    }

    public function getAllEntitiesAndSubActivities($actSlug)
    {
        if (empty($actSlug)) return $this->sendErrorResponse('Invalid activity slug', 'Invalid activity slug', 400);

        $data = $this->requests->getAllEntitiesAndSubActivities($actSlug);
        if (!$data) return $this->sendErrorResponse('Invalid activity slug', 'Invalid activity slug', 400);

        return $this->sendSuccessResponse($data);
    }

    public function getFormFields(Request $request)
    {
        try {
            $data = [
                'category' => $request->category,
                'subCategory' => $request->subCategory,
                'sector' => $request->sector,
                'activity' => $request->activity,
                'subActivity' => $request->subActivity,
                'entity' => $request->entity,
                'incubator' => $request->incubator,
            ];

            // Validate required fields
            if (empty($data['category'])) {
                return $this->sendErrorResponse('Category is required', 'Category is required', 400);
            }

            $formFields = $this->requests->getFormFields($data);

            return $this->sendSuccessResponse($formFields);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 500);
        }
    }

    /**
     * Preview document securely - Accessible to any authenticated user with proper permissions
     *
     * @param string $documentId
     * @param Request $request
     * @return StreamedResponse|\Illuminate\Http\JsonResponse
     */
    public function previewDocument($documentId)
    {
        try {
            return $this->documentService
                ->validateDocumentAccess($documentId)
                ->getDocumentPreview($documentId);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 500);
        }
    }
}
