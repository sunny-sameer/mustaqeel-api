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
    public function getStageStatus($column1,$value1,$column2,$value2);
    public function createRequestStageStatus($params = [], $request, $status);
    public function getRequest($requestId);
    public function getUserRequestStatus($requestId,$user);
    public function getRequestStatus($requestId);
    public function getRequestStatuses($requestId);
    public function getAllAttributes($requestId);
    public function canSubmitRequest($activitiesIds, $entitySlug);
    public function getQc($requestId, $status);
    public function createQc($request);
    public function updateQc($request, $qcId);
    public function getRequestsCount();
    public function checkSelfAssignedRequest($requestId);
    public function createSecureCode($request);
    public function createSecureCodeDocument($request);
}
