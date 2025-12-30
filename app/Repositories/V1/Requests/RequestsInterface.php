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
    public function getRequest($requestId);
    public function getRequestMetaData($params = []);
    public function getRequestStatus($requestId);
    public function getRequestStatuses($requestId);
    public function getAllAttributes($requestId);
    public function canSubmitRequest($activitiesIds, $entitySlug);
    public function getQc($requestId, $status);
    public function createQc($request);
    public function updateQc($request, $qcId);
}
