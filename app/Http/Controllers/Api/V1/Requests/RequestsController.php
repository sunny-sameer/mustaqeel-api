<?php

namespace App\Http\Controllers\Api\V1\Requests;

use App\Exceptions\BadRequestException;
use App\Exceptions\UserNotFoundException;


use App\Http\Controllers\Api\BaseController;


use App\Http\Requests\Api\V1\RequestsRequest;


use App\Services\V1\Requests\RequestsService;


class RequestsController extends BaseController
{
    protected $requests;


    public function __construct(RequestsService $requests)
    {
        $this->requests = $requests;
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

    public function getAllSectorsSubCategoriesAndIncubators($catId)
    {
        if (empty($catId)) return $this->sendErrorResponse('Invalid category id', 'Invalid category id', 400);
        return $this->sendSuccessResponse($this->requests->getAllSectorsSubCategoriesAndIncubators($catId));
    }

    public function getAllActivities($secId)
    {
        if (empty($secId)) return $this->sendErrorResponse('Invalid sector id', 'Invalid sector id', 400);
        return $this->sendSuccessResponse($this->requests->getAllActivities($secId));
    }

    public function getAllEntitiesAndSubActivities($actId)
    {
        if (empty($actId)) return $this->sendErrorResponse('Invalid activity id', 'Invalid activity id', 400);
        return $this->sendSuccessResponse($this->requests->getAllEntitiesAndSubActivities($actId));
    }
}
