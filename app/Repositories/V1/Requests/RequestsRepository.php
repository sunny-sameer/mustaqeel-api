<?php

namespace App\Repositories\V1\Requests;

use App\Models\Categories;
use App\Models\QualityCheck;
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
use Spatie\Permission\Models\Role;

class RequestsRepository extends CoreRepository implements RequestsInterface
{
    protected $requestMetaData;
    protected $requestAttribute;
    protected $stages;
    protected $requestStages;
    protected $stagesStatuses;
    protected $requestStatuses;
    protected $qualityCheck;


    public function __construct(Requests $model, RequestMetaData $requestMetaData, RequestAttribute $requestAttribute, Stages $stages, RequestStages $requestStages, StagesStatuses $stagesStatuses, RequestStatuses $requestStatuses, QualityCheck $qualityCheck)
    {
        parent::__construct($model);

        $this->requestMetaData = $requestMetaData;
        $this->requestAttribute = $requestAttribute;
        $this->stages = $stages;
        $this->requestStages = $requestStages;
        $this->stagesStatuses = $stagesStatuses;
        $this->requestStatuses = $requestStatuses;
        $this->qualityCheck = $qualityCheck;
    }

    public function getAllRequests($request)
    {
        $id = auth()->id();
        $user = User::find($id);
        $role = $user->roles->pluck('type')->first();

        $search = $request->search ?? NULL;
        $perPage = $request->perPage ?? 10;

        $req = $this->model->with([
            'metas:reqId,key,value',
        ]);

        if (!empty($search)) {
            $req = $req->where(function ($query) use ($search) {
                $query->where('nameEn', 'LIKE', '%' . $search . '%')
                    ->orWhere('nameAr', 'LIKE', '%' . $search . '%')
                    ->orWhere('reqReferenceNumber', 'LIKE', '%' . $search . '%');
                    // ->orWhereHas('metas.category', function ($category) use ($search) {
                    //     $category->where('name', 'LIKE', '%' . $search . '%');
                    // })
                    // ->orWhereHas('metas.sector', function ($sector) use ($search) {
                    //     $sector->where('name', 'LIKE', '%' . $search . '%');
                    // })
                    // ->orWhereHas('metas.activity', function ($activity) use ($search) {
                    //     $activity->where('name', 'LIKE', '%' . $search . '%');
                    // })
                    // ->orWhereHas('metas.entity', function ($entity) use ($search) {
                    //     $entity->where('name', 'LIKE', '%' . $search . '%');
                    // })
                    // ->orWhereHas('metas.incubator', function ($incubator) use ($search) {
                    //     $incubator->where('name', 'LIKE', '%' . $search . '%');
                    // });
                    // ->orWhereHas('metas.related', function ($related) use ($search) {
                    //     $related->where('name', 'LIKE', '%' . $search . '%');
                    // });
            });
        }

        if ($role == 'applicant') {
            $req = $req->where(function ($query) use ($id, $user) {
                $query->where('userId', $id)
                    ->orWhere('email', $user->email);
            });
        } else if($role == 'entity') {
            $adminRole = Role::where('type','jusour')->first();
            $req = $req->whereHas('requestStage.lastRequestStatus', function ($q) use ($adminRole) {
                $q->whereHas('stageStatus', function ($q) {
                    $q->where('name', 'Approved')
                    ->whereHas('stage', function ($q) {
                        $q->where('name', 'Jusour');
                    });
                });
                $q->whereHas('user.levels', function ($q) use ($adminRole) {
                    $q->where('level', $adminRole->approval_levels);
                });
            });

            $getUserMetas =  getUserMetas();
            $getEntityRoles = getEntityRoles();
            $req = $req->where(function ($query1) use ($getUserMetas,$getEntityRoles){
                $query1->where(function ($query2) use ($getUserMetas,$getEntityRoles){
                    if(in_array('incubator',$getEntityRoles)){
                        $query2->whereHas('category', function ($query){
                            $query->where('value','ent');
                        })->whereHas('incubator', function ($query) use ($getUserMetas){
                            $query->whereIn('value',$getUserMetas['incubators']);
                        });
                    }
                })->orWhere(function ($query2) use ($getUserMetas,$getEntityRoles){
                    if(in_array('entity',$getEntityRoles)){
                        $query2->whereHas('category', function ($query){
                            $query->where('value','tal');
                        })->whereHas('entity', function ($query) use ($getUserMetas){
                            $query->whereIn('value',$getUserMetas['entities']);
                        })->whereHas('activity', function ($query) use ($getUserMetas){
                            $query->whereIn('value',$getUserMetas['activities']);
                        })->where(function ($query) use ($getUserMetas) {
                            $query->whereDoesntHave('subActivity')
                            ->orWhereHas('subActivity', function ($q) use ($getUserMetas) {
                                $q->whereIn('value', $getUserMetas['subActivities']);
                            });
                        });
                    }

                });
            });
        }

        $req = $req->orderBy('created_at', 'DESC')->paginate($perPage);


        $req->map(function ($query) {
            $query->statuses = $this->getRequestStatus($query->id);

            $query->metas->map(function ($query1) use ($query) {
                $query->{$query1->key} = $query1?->related;
                return $query1;
            });

            return $query;
        });


        return $req;
    }

    public function getLastRequest($id = null)
    {
        return $this->model->withTrashed()
            ->where('reqReferenceNumber', '<>', NULL)
            ->orderBy('id', 'desc')
            ->first();
    }

    public function updateOrCreateRequest($request, $requestId)
    {
        $requests = $this->model->find($requestId);
        if (isset($requests->id)) {
            $requests->update($request);
            return $requests;
        }
        return $this->model->create($request);
    }

    public function updateOrCreateRequestMetaData($request, $requestId)
    {
        $data = [];
        foreach ($request as $key => $value) {
            $data[] = $this->requestMetaData->updateOrCreate(['reqId' => $requestId, 'key' => $value['key']], $value);
        }
        return $data;
    }

    public function updateOrCreateRequestAttributes($request, $requestId)
    {
        $data = [];
        foreach ($request as $key => $value) {
            $data[] = $this->requestAttribute->updateOrCreate(['reqId' => $requestId, 'type' => $value['type']], $value);
        }
        return $data;
    }

    public function getStage($params = [])
    {
        $stage = $this->stages->where($params)->first();
        if (empty($stage)) {
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
        if (empty($requestStage)) {
            $requestStage = $this->requestStages->create($request);
        }
        return $requestStage;
    }

    public function getStageStatus($params = [])
    {
        $stagesStatuses = $this->stagesStatuses->where($params)->first();
        if (empty($stagesStatuses)) {
            $stagesStatuses = $this->stagesStatuses->create($params);
        }
        return $stagesStatuses;
    }

    public function createRequestStageStatus($params = [], $request, $status)
    {
        if ($status === "Draft") {
            return $this->requestStatuses->updateOrCreate($params, $request);
        }
        return $this->requestStatuses->create($request);
    }


    public function getRequest($requestId)
    {
        $id = auth()->id();
        $user = User::find($id);
        $role = $user->roles->pluck('type')->first();

        $req = $this->model->with([
            'metas:reqId,key,value',
            'documents',
            'qualityCheck',
            'qualityChecks'
        ])->withCount('qualityChecks');

        if ($role == 'applicant') {
            $req = $req->where(function ($query) use ($id, $user) {
                $query->where('userId', $id)
                    ->orWhere('email', $user->email);
            });
        } else if($role == 'entity') {
            $adminRole = Role::where('type','jusour')->first();
            $req = $req->whereHas('requestStage.lastRequestStatus', function ($q) use ($adminRole) {
                $q->whereHas('stageStatus', function ($q) {
                    $q->where('name', 'Approved')
                    ->whereHas('stage', function ($q) {
                        $q->where('name', 'Jusour');
                    });
                });
                $q->whereHas('user.levels', function ($q) use ($adminRole) {
                    $q->where('level', $adminRole->approval_levels);
                });
            });


            $getUserMetas =  getUserMetas();
            $getEntityRoles = getEntityRoles();
            $req = $req->where(function ($query1) use ($getUserMetas,$getEntityRoles){
                $query1->where(function ($query2) use ($getUserMetas,$getEntityRoles){
                    if(in_array('incubator',$getEntityRoles)){
                        $query2->whereHas('category', function ($query){
                            $query->where('value','ent');
                        })->whereHas('incubator', function ($query) use ($getUserMetas){
                            $query->whereIn('value',$getUserMetas['incubators']);
                        });
                    }
                })->orWhere(function ($query2) use ($getUserMetas,$getEntityRoles){
                    if(in_array('entity',$getEntityRoles)){
                        $query2->whereHas('category', function ($query){
                            $query->where('value','tal');
                        })->whereHas('entity', function ($query) use ($getUserMetas){
                            $query->whereIn('value',$getUserMetas['entities']);
                        })->whereHas('activity', function ($query) use ($getUserMetas){
                            $query->whereIn('value',$getUserMetas['activities']);
                        })->where(function ($query) use ($getUserMetas) {
                            $query->whereDoesntHave('subActivity')
                            ->orWhereHas('subActivity', function ($q) use ($getUserMetas) {
                                $q->whereIn('value', $getUserMetas['subActivities']);
                            });
                        });
                    }

                });
            });
        }

        $req = $req->where('id', $requestId)->first();

        if ($req) {
            $req->documents->map(function ($query) {
                $query->meta = json_decode($query->meta);
                return $query;
            });

            $req['status'] = $this->getRequestStatuses($requestId);

            // Get all attributes including QC
            $attributes = $this->getAllAttributes($requestId);
            foreach ($attributes as $key => $value) {
                $req->{$key} = $value;
            }

            if(isset($req->qualityCheck)){
                $req->qualityCheck->meta = json_decode($req->qualityCheck->meta);
                $req->qualityCheck->summary = json_decode($req->qualityCheck->summary);
            }

            $req->qualityChecks->map(function ($query) {
                $query->meta = json_decode($query->meta);
                $query->summary = json_decode($query->summary);
                return $query;
            });

            $req->metas->map(function ($query) use ($req) {
                $req->{$query->key} = $query?->related;
                return $query;
            });
        }

        return $req;
    }

    public function getUserRequestStatus($requestId,$user)
    {
        $id = $user->id;
        $type = $user->roles->pluck('type')->first();

        $stage = $this->stages->where('name',ucfirst($type))->first();

        $requestStatus = $this->requestStatuses
        ->with('stageStatus', 'user.roles', 'requestStage.stage')
        ->whereHas('requestStage', function ($query) use ($requestId, $stage) {
            $query->where('reqId', $requestId);
            $query->where('stageSlug', $stage->slug);
        })->where('userId', $id)
        ->orderBy('id', 'DESC')->first();

        return $requestStatus;
    }

    public function getRequestStatus($requestId)
    {
        $id = auth()->id();
        $type = auth()->user()->roles->pluck('type')->first();

        $stages = $this->stages->orderBy('order','ASC')->get();

        $data = [];
        foreach ($stages as $stage) {
            $requestStatus = $this->requestStatuses
                ->with('stageStatus', 'user.roles', 'requestStage.stage')
                ->whereHas('requestStage', function ($query) use ($requestId, $stage) {
                    $query->where('reqId', $requestId);
                    $query->where('stageSlug', $stage->slug);
                });
            if ($stage->name == ucfirst($type)) {
                $requestStatus = $requestStatus->where('userId', $id);
            }
            $requestStatus = $requestStatus->orderBy('id', 'DESC')->first();

            $key = Str::lower($stage->name);
            $data[$key] = [
                'status' => $requestStatus?->stageStatus?->name ?? 'Under Review',
                'stage' => $requestStatus?->requestStage?->stage?->name ?? $stage->name,
                'username' => $requestStatus?->user?->name ?? null,
                'levels' => $requestStatus?->user?->levels?->map(fn ($level) => [
                    'name' => $level->name,
                    'level' => $level->level,
                    'role'  => $level?->role?->name,
                    'totalLevels' => $level?->role?->approval_levels,
                ])->values(),
            ];
        }

        return $data;
    }

    public function getRequestStatuses($requestId)
    {
        $stages = $this->stages->orderBy('order','ASC')->get();

        $data = [];
        foreach ($stages as $stage) {
            $requestStatus = $this->requestStatuses
                ->with('stageStatus', 'user.roles', 'requestStage.stage')
                ->whereHas('requestStage', function ($query) use ($requestId, $stage) {
                    $query->where('reqId', $requestId);
                    $query->where('stageSlug', $stage->slug);
                })
                ->orderByRaw('userId = ? DESC', [auth()->id()])
                ->orderBy('id', 'DESC')->get()->unique('userId');

            $key = Str::lower($stage->name);
            if (isset($requestStatus) && count($requestStatus) > 0) {
                foreach ($requestStatus as $value) {
                    $data[$key][] = [
                        'status' => $value?->stageStatus?->name ?? 'Under Review',
                        'stage' => $value?->requestStage?->stage?->name ?? $stage->name,
                        'username' => $value?->user?->name,
                        'levels' => $value?->user?->levels?->map(fn ($level) => [
                            'name' => $level->name,
                            'level' => $level->level,
                            'role'  => $level?->role?->name,
                            'totalLevels' => $level?->role?->approval_levels,
                        ])->values(),
                        'meta' => $value?->meta ? json_decode($value->meta) : [],
                    ];
                }
            } else {
                $data[$key][] = [
                    'status' => 'Under Review',
                    'stage' => $stage->name,
                    'username' => null,
                    'levels' => null,
                    'meta' => [],
                ];
            }
        }
        return $data;
    }

    // Update the getAllAttributes method for QC
    public function getAllAttributes($requestId)
    {
        $requestAttribute = $this->requestAttribute
            ->where('reqId', $requestId)
            ->get();

        $data = [];
        foreach ($requestAttribute as $key => $value) {
            try {
                $decodedMeta = json_decode($value->meta, true);

                // Handle different data types
                if (json_last_error() === JSON_ERROR_NONE) {
                    $data[$value->type] = $decodedMeta;

                    // Explicit QC key for easy access
                    if ($value->type === 'qc') {
                        $data['qc'] = $decodedMeta;
                    }
                } else {
                    // Fallback for non-JSON data
                    $data[$value->type] = $value->meta;
                }
            } catch (\Exception $e) {
                $data[$value->type] = $value->meta;
            }
        }

        return $data;
    }

    public function canSubmitRequest($activitiesIds, $entitySlug)
    {
        $isExist = $this->model
            ->where(function ($q) use ($activitiesIds, $entitySlug) {
                $q->where(function ($q2) use ($activitiesIds, $entitySlug) {
                    $q2->whereHas('metas', function ($query) use ($entitySlug) {
                        $query->where('entitySlug', $entitySlug);
                    })
                        ->whereHas('metas.activity', function ($query) use ($activitiesIds) {
                            $query->whereIn('id', $activitiesIds);
                        })
                        ->whereHas('requestStage.stage', function ($query) {
                            $query->where('name', 'Application');
                        })
                        ->whereHas('requestStage.requestStatuses.stageStatus', function ($query) {
                            $query->where('name', 'Rejected');
                        });
                })
                    ->orWheredoesntHave('metas', function ($query) use ($entitySlug) {
                        $query->where('entitySlug', $entitySlug);
                    });
            })
            ->where('userId', auth()->id())->exists();

        return $isExist;
    }

    public function getQc($requestId,$status)
    {
        $qc = $this->qualityCheck->where('reqId',$requestId)
        ->where('status',$status)->orderBy('created_at','DESC')->first();

        return $qc;
    }

    public function createQc($request)
    {
        return $this->qualityCheck->create($request);
    }

    public function updateQc($request, $qcId)
    {
        $qc = $this->qualityCheck->find($qcId);
        $qc->update($request);
        return $qc;
    }

    public function getRequestsCount()
    {
        $categories = Categories::all();
        $submitted = [];
        $rejected = [];
        $qcCompleted = [];
        $endorsed = [];
        $molApproved = [];
        $hayyaApproved = [];
        $visaQidIssue = [];
        foreach ($categories as $key => $value) {
            $submitted[$value->name] = $this->model
            ->whereHas('metas',function($query) use ($value){
                $query->where('key','category')->where('value',$value->slug);
            })->count();

            $rejected[$value->name] = $this->model
            ->whereHas('metas',function($query) use ($value){
                $query->where('key','category')->where('value',$value->slug);
            })->whereHas('requestStage.lastRequestStatus', function ($q){
                $q->whereHas('stageStatus', function ($q) {
                    $q->where('name', 'Rejected')
                    ->whereHas('stage', function ($q) {
                        $q->where('name', 'Application');
                    });
                });
            })->count();

            $qcCompleted[$value->name] = $this->model
            ->whereHas('metas',function($query) use ($value){
                $query->where('key','category')->where('value',$value->slug);
            })->whereHas('qualityCheck',function($query){
                $query->where('status','QC Approved');
            })->count();

            $entityRole = Role::where('name','entity')->first();
            $endorsed[$value->name] = $this->model
            ->whereHas('metas',function($query) use ($value){
                $query->where('key','category')->where('value',$value->slug);
            })->whereHas('requestStage.lastRequestStatus', function ($q) use ($entityRole) {
                $q->whereHas('stageStatus', function ($q) {
                    $q->where('name', 'Approved')
                    ->whereHas('stage', function ($q) {
                        $q->where('name', 'Entity');
                    });
                });
                $q->whereHas('user.levels', function ($q) use ($entityRole) {
                    $q->where('levels', $entityRole->approval_levels);
                });
            })->count();

            $molApproved[$value->name] = $this->model
            ->whereHas('metas',function($query) use ($value){
                $query->where('key','category')->where('value',$value->slug);
            })->whereHas('requestStage.lastRequestStatus', function ($q){
                $q->whereHas('stageStatus', function ($q) {
                    $q->where('name', 'Approved')
                    ->whereHas('stage', function ($q) {
                        $q->where('name', 'MOL');
                    });
                });
            })->count();

            $hayyaApproved[$value->name] = $this->model
            ->whereHas('metas',function($query) use ($value){
                $query->where('key','category')->where('value',$value->slug);
            })->whereHas('requestStage.lastRequestStatus', function ($q){
                $q->whereHas('stageStatus', function ($q) {
                    $q->where('name', 'Approved')
                    ->whereHas('stage', function ($q) {
                        $q->where('name', 'Hayya');
                    });
                });
            })->count();

            $visaQidIssue[$value->name] = 0;
        }

        return ['submitted'=>$submitted,'rejected'=>$rejected,'qcCompleted'=>$qcCompleted,'endorsed'=>$endorsed,'molApproved'=>$molApproved,'hayyaApproved'=>$hayyaApproved,'visaQidIssue'=>$visaQidIssue];
    }
}
