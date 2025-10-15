<?php

namespace App\Repositories\V1\Requests;
use App\Repositories\V1\Core\CoreInterface;

interface RequestsInterface  extends CoreInterface {
    public function getAllRequests($request);
    public function getLastRequest($id = null);
    public function updateOrCreateRequest($request, $requestId);
    public function updateOrCreateRequestMetaData($request, $requestId);
    public function updateOrCreateRequestAttributes($request, $requestId);
    public function getStage($params = []);
    public function getRequestStage($params = []);
    public function createRequestStage($params = [], $request);
    public function getStageStatus($params = []);
    public function createRequestStageStatus($params = [], $request, $status);
    public function getRequest($reqId);
    public function getRequestStatus($reqId);
    public function getRequestStatuses($reqId);
    public function getAllAttributes($reqId);
    public function canSubmitRequest($activitiesIds,$entitySlug);
}
