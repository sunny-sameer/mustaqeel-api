<?php

namespace App\Repositories\V1\Requests;

use App\Models\RequestAttribute;
use App\Models\RequestMetaData;
use App\Models\Requests;
use App\Models\RequestStages;
use App\Models\RequestStatuses;
use App\Models\Stages;
use App\Models\StagesStatuses;
use App\Models\User;
// Core Repository

use Illuminate\Support\Str;


use App\Repositories\V1\Core\CoreRepository;

class RequestsRepository extends CoreRepository implements RequestsInterface
{
    protected $requestMetaData;
    protected $requestAttribute;
    protected $stages;
    protected $requestStages;
    protected $stagesStatuses;
    protected $requestStatuses;


    public function __construct(Requests $model, RequestMetaData $requestMetaData, RequestAttribute $requestAttribute, Stages $stages, RequestStages $requestStages, StagesStatuses $stagesStatuses, RequestStatuses $requestStatuses)
    {
        parent::__construct($model);

        $this->requestMetaData = $requestMetaData;
        $this->requestAttribute = $requestAttribute;
        $this->stages = $stages;
        $this->requestStages = $requestStages;
        $this->stagesStatuses = $stagesStatuses;
        $this->requestStatuses = $requestStatuses;
    }

    public function getAllRequests($request)
    {
        $id = auth()->id();
        $user = User::find($id);
        $role = $user->roles->pluck('type')->first();

        $search = $request->search ?? NULL;
        $perPage = $request->perPage ?? 10;

        $req = $this->model->with([
            'metas.category:name,nameAr,slug',
            'metas.subCategory:name,nameAr,slug',
            'metas.sector:name,nameAr,slug',
            'metas.activity:name,nameAr,slug',
            'metas.subActivity:name,nameAr,slug',
            'metas.entity:name,nameAr,slug',
            'metas.incubator:name,nameAr,slug',
        ]);

        if(!empty($search)){
            $req = $req->where(function ($query) use ($search){
                $query->where('nameEn','LIKE','%'.$search.'%')
                ->orWhere('nameAr','LIKE','%'.$search.'%')
                ->orWhere('reqReferenceNumber','LIKE','%'.$search.'%')
                ->orWhereHas('metas.category', function ($category) use ($search){
                    $category->where('name','LIKE','%'.$search.'%');
                })
                ->orWhereHas('metas.sector', function ($sector) use ($search){
                    $sector->where('name','LIKE','%'.$search.'%');
                })
                ->orWhereHas('metas.activity', function ($activity) use ($search){
                    $activity->where('name','LIKE','%'.$search.'%');
                })
                ->orWhereHas('metas.entity', function ($entity) use ($search){
                    $entity->where('name','LIKE','%'.$search.'%');
                })
                ->orWhereHas('metas.incubator', function ($incubator) use ($search){
                    $incubator->where('name','LIKE','%'.$search.'%');
                });
            });
        }

        if($role == 'applicant')
        {
            $req = $req->where(function ($query) use ($id,$user){
                $query->where('userId',$id)
                ->orWhere('email',$user->email);
            });
        }

        $req = $req->orderBy('created_at','DESC')->paginate($perPage);


        $req->map(function ($query){
            $query->statuses = $this->getRequestStatus($query->id);

            return $query;
        });


        return $req;
    }

    public function getLastRequest($id = null)
    {
        return $this->model->withTrashed()
        ->where('reqReferenceNumber','<>',NULL)
        ->orderBy('id','desc')
        ->first();
    }

    public function updateOrCreateRequest($request,$requestId)
    {
        $requests = $this->model->find($requestId);
        if(isset($requests->id)){
            $requests->update($request);
            return $requests;
        }
        return $this->model->create($request);
    }

    public function updateOrCreateRequestMetaData($request,$requestId,$requestType)
    {
        return $this->requestMetaData->updateOrCreate(['modelId'=>$requestId,'modelType'=>$requestType],$request);
    }

    public function updateOrCreateRequestAttributes($request,$requestId)
    {
        $data = [];
        foreach ($request as $key => $value) {
            $data[] = $this->requestAttribute->updateOrCreate(['reqId'=>$requestId,'type'=>$value['type']],$value);
        }
        return $data;
    }

    public function getStage($params = [])
    {
        $stage = $this->stages->where($params)->first();
        if(empty($stage)){
            $stage = $this->stages->create($params);
        }
        return $stage;
    }

    public function getRequestStage($params = [])
    {
        return $this->requestStages
        ->with('lastRequestStatus.stageStatus.stage')
        ->where($params)
        ->first();
    }

    public function createRequestStage($params = [], $request)
    {
        $requestStage = $this->requestStages->where($params)->first();
        if(empty($requestStage)){
            $requestStage = $this->requestStages->create($request);
        }
        return $requestStage;
    }

    public function getStageStatus($params = [])
    {
        $stagesStatuses = $this->stagesStatuses->where($params)->first();
        if(empty($stagesStatuses)){
            $stagesStatuses = $this->stagesStatuses->create($params);
        }
        return $stagesStatuses;
    }

    public function createRequestStageStatus($params = [], $request, $status)
    {
        if($status === "Draft"){
            return $this->requestStatuses->updateOrCreate($params,$request);
        }
        return $this->requestStatuses->create($request);
    }

    public function getRequest($reqId)
    {
        $id = auth()->id();
        $user = User::find($id);
        $role = $user->roles->pluck('type')->first();


        $req = $this->model->with([
            'metas.category:name,nameAr,slug',
            'metas.subCategory:name,nameAr,slug',
            'metas.sector:name,nameAr,slug',
            'metas.activity:name,nameAr,slug',
            'metas.subActivity:name,nameAr,slug',
            'metas.entity:name,nameAr,slug',
            'metas.incubator:name,nameAr,slug',
            'documents'
        ]);

        if($role == 'applicant')
        {
            $req = $req->where(function ($query) use ($id,$user){
                $query->where('userId',$id)
                ->orWhere('email',$user->email);
            });
        }

        $req = $req->where('id',$reqId)->first();

        if($req)
        {
            $req->documents->map(function ($query){
                $query->meta = json_decode($query->meta);

                return $query;
            });

            $req['status'] = $this->getRequestStatuses($reqId);

            foreach ($this->getAllAttributes($reqId) as $key => $value) {
                $req->{$key} = $value;
            }
        }

        return $req;
    }

    public function getRequestStatus($reqId)
    {
        $id = auth()->id();

        $stages = $this->stages->all();

        $data = [];
        foreach ($stages as $stage) {
            $requestStatus = $this->requestStatuses
            ->with('stageStatus','user.roles','requestStage.stage')
            ->whereHas('requestStage', function ($query) use ($reqId,$stage){
                $query->where('reqId',$reqId);
                $query->where('stageSlug',$stage->slug);
            });
            if($stage->name <> 'Application'){
                $requestStatus = $requestStatus->where('userId',$id);
            }
            $requestStatus = $requestStatus->orderBy('created_at','DESC')->first();

            $key = Str::lower($stage->name);
            $data[$key] = [
                'status'=>$requestStatus?->stageStatus?->name ?? 'Pending',
                'stage'=>$requestStatus?->requestStage?->stage?->name ?? $stage->name,
                'username'=>$requestStatus?->user?->name ?? null,
                'role'=>$requestStatus?->user?->roles->pluck('name')->first() ?? null
            ];
        }

        return $data;
    }

    public function getRequestStatuses($reqId)
    {
        $stages = $this->stages->all();

        $data = [];
        foreach ($stages as $stage) {
            $requestStatus = $this->requestStatuses
            ->with('stageStatus','user.roles','requestStage.stage')
            ->whereHas('requestStage', function ($query) use ($reqId,$stage){
                $query->where('reqId',$reqId);
                $query->where('stageSlug',$stage->slug);
            })
            ->orderBy('created_at','DESC')->get()->unique('userId');

            $key = Str::lower($stage->name);
            if(isset($requestStatus) && count($requestStatus) > 0){
                foreach ($requestStatus as $value) {
                    $data[$key][] = [
                        'status'=>$value?->stageStatus?->name ?? 'Pending',
                        'stage'=>$value?->requestStage?->stage?->name ?? $stage->name,
                        'username'=>$value?->user?->name,
                        'role'=>$value?->user?->roles->pluck('name')->first(),
                        'meta'=>$value?->meta ? json_decode($value->meta) : [],
                    ];
                }
            }
            else{
                $data[$key][] = [
                    'status'=>'Pending',
                    'stage'=>$stage->name,
                    'username'=>null,
                    'role'=>null,
                    'meta'=>null,
                ];
            }
        }
        return $data;
    }

    public function getAllAttributes($reqId)
    {
        $requestAttribute = $this->requestAttribute
        ->where('reqId',$reqId)
        ->get();

        $data = [];
        foreach ($requestAttribute as $key => $value) {
            $data[$value->type] = json_decode($value->meta);
        }

        return $data;
    }

    public function canSubmitRequest($activitiesIds,$entitySlug)
    {
        $isExist = $this->model
        ->where(function ($q) use ($activitiesIds,$entitySlug){
            $q->where(function ($q2) use ($activitiesIds,$entitySlug){
                $q2->whereHas('metas',function ($query) use ($entitySlug){
                    $query->where('entitySlug',$entitySlug);
                })
                ->whereHas('metas.activity',function ($query) use ($activitiesIds){
                    $query->whereIn('id',$activitiesIds);
                })
                ->whereHas('requestStage.stage',function ($query){
                    $query->where('name','Application');
                })
                ->whereHas('requestStage.requestStatuses.stageStatus',function ($query){
                    $query->where('name','Rejected');
                });
            })
            ->orWheredoesntHave('metas',function ($query) use ($entitySlug){
                $query->where('entitySlug',$entitySlug);
            });
        })
        ->where('userId',auth()->id())->exists();

        return $isExist;
    }
}
