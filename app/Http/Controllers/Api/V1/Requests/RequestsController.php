<?php

namespace App\Http\Controllers\Api\V1\Requests;


use App\Exceptions\BadRequestException;
use App\Exceptions\UserNotFoundException;


use App\Http\Controllers\Api\BaseController;


use App\Http\Requests\Api\V1\RequestsRequest;


use Illuminate\Http\Request;
use App\Services\V1\Requests\RequestsService;


class RequestsController extends BaseController
{
    protected $requests;


    public function __construct(RequestsService $requests)
    {
        $this->requests = $requests;
    }

    public function getRequests(Request $request)
    {
        try {
            return $this->requests
                ->setRequestInputs($request)
                ->userExists()
                ->getRequests();
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 403);
        }
    }

    public function createRequest(RequestsRequest $request)
    {
        try {
            return $this->requests
                ->setInputs($request)
                ->userExists()
                ->createRequestReferenceNumber()
                ->createRequest();
        } catch (UserNotFoundException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (BadRequestException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 400);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 403);
        }
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
}
