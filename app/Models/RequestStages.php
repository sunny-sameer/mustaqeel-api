<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestStages extends Model
{
    use SoftDeletes;

    protected $table = 'request_stages';
    protected $guarded = [];

    public function requestStatuses()
    {
        return $this->hasMany(RequestStatuses::class,'reqStageId','id')
        ->orderBy('created_at','DESC');
    }

    public function lastRequestStatus()
    {
        return $this->hasOne(RequestStatuses::class,'reqStageId','id')
        ->orderBy('created_at','DESC');
    }

    public function stage()
    {
        return $this->belongsTo(Stages::class,'stageSlug','slug');
    }
}
