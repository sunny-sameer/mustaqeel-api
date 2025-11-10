<?php

namespace App\Http\Controllers\Api\V1\Requests;


use App\Exceptions\BadRequestException;
use App\Exceptions\RequestAlreadyExistException;
use App\Exceptions\RequestNotExistException;
use App\Exceptions\UserNotFoundException;


use App\Http\Controllers\Api\BaseController;


use Illuminate\Http\Request;
use App\Http\Requests\API\V1\RequestsRequest;
use App\Http\Requests\API\V1\RequestsDocumentRequest;
use App\Http\Requests\API\V1\RequestsPartialRequest;
use App\Http\Requests\API\V1\RequestStatusUpdateRequest;
use App\Http\Requests\API\V1\ReuploadDocumentRequest;
use App\Models\Stages;
use App\Services\V1\Requests\RequestsService;


class RequestsController extends BaseController
{
    /**
     * See Swagger annotations in \App\Swaggers\V1\Requests\RequestsSwagger
    */


    protected $status = 'Draft';
    protected $requests;


    public function __construct(RequestsService $requests)
    {
        $this->requests = $requests;
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
                ->setInputsPartial($request,$this->status)
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

    public function createRequest(RequestsRequest $request)
    {
        try {
            $this->status = 'Pending';
            return $this->requests
                ->setInputs($request,$this->status)
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

    public function createRequestDocument(RequestsDocumentRequest $request)
    {
        try {
            return $this->requests
                ->setInputsDocument($request)
                ->userExists()
                ->requestAlreadyExists()
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
        return $this->sendSuccessResponse();
    }

    public function reuploadDocumentRequest(ReuploadDocumentRequest $request, $id)
    {
        try {
            return $this->requests
                ->setReuploadInputsDocument($request,$id)
                ->userExists()
                ->requestNoFound()
                ->reuploadDocumentRequest();
        } catch (UserNotFoundException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (BadRequestException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 400);
        } catch (RequestNotExistException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 403);
        }
        return $this->sendSuccessResponse($request->all());
    }

    public function deleteDocumentRequest($id)
    {
        try {
            return $this->requests
                ->userExists()
                ->deleteDocumentById($id);
        } catch (BadRequestException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 400);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 403);
        }
        return $this->sendSuccessResponse($request->all());
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
        if (empty($request->category) && !isset($request->category)) return $this->sendErrorResponse('Invalid category slug', 'Invalid category slug', 400);

        return $this->requests->getFormFields($request->all());
    }
}
