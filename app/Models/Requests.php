<?php

namespace App\Models;

use App\Models\Traits\DisableSnakeAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Requests extends Model
{
    use SoftDeletes, DisableSnakeAttributes;

    protected $table = 'requests';
    protected $guarded = [];

    public function metas()
    {
        return $this->morphOne(RequestMetaData::class,'model','modelType','modelId');
    }

    public function attributes()
    {
        return $this->hasMany(RequestAttribute::class,'reqId','id');
    }

    public function requestStage()
    {
        return $this->hasMany(RequestStages::class,'reqId','id');
    }

    public function documents()
    {
        return $this->morphMany(Documents::class, 'entity', 'entityType', 'entityId');
    }


}
