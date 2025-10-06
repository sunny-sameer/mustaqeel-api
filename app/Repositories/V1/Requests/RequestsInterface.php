<?php

namespace App\Repositories\V1\Requests;
use App\Repositories\V1\Core\CoreInterface;

interface RequestsInterface  extends CoreInterface {
    public function getAllRequests($status, $role);
    public function getLastRequest();
    public function createRequestMetaData($request);
    public function createRequestAttributes($request);
}
