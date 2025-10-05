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
}
