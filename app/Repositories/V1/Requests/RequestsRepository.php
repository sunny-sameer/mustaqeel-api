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

    public function getAllRequests($status, $role)
    {
        $request = $this->model->with([
            'metas.category:name,nameAr,slug',
            'metas.subCategory:name,nameAr,slug',
            'metas.sector:name,nameAr,slug',
            'metas.activity:name,nameAr,slug',
            'metas.subActivity:name,nameAr,slug',
            'metas.entity:name,nameAr,slug',
            'metas.incubator:name,nameAr,slug',
            'stage.status'
        ]);

        // if($role == 'applicant')
        // {
        //     $request->whereHas();
        // }

        $request = $request->orderBy('created_at','DESC')->paginate(12);

        return $request;
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
