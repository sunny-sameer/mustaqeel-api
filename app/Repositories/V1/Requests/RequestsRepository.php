<?php

namespace App\Repositories\V1\Requests;

use App\Models\RequestAttribute;
use App\Models\RequestMetaData;
use App\Models\Requests;

// Core Repository
use App\Repositories\V1\Core\CoreRepository;

class RequestsRepository extends CoreRepository implements RequestsInterface
{
    protected $requestMetaData;
    protected $requestAttribute;


    public function __construct(Requests $model, RequestMetaData $requestMetaData, RequestAttribute $requestAttribute)
    {
        parent::__construct($model);

        $this->requestMetaData = $requestMetaData;
        $this->requestAttribute = $requestAttribute;
    }

    public function getLastRequest()
    {
        return $this->model->withTrashed()->orderBy('id','desc')->first();
    }

    public function createRequestMetaData($request)
    {
        return $this->requestMetaData->create($request);
    }

    public function createRequestAttributes($request)
    {
        $data = [];
        foreach ($request as $key => $value) {
            $data[] = $this->requestAttribute->create($value);
        }
        return $data;
    }
}
