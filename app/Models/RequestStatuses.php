<?php

namespace App\Models;

use App\Models\Traits\DisableSnakeAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestStatuses extends Model
{
    use SoftDeletes, DisableSnakeAttributes;

    protected $table = 'request_statuses';
    protected $guarded = [];


    public function stageStatus()
    {
        return $this->belongsTo(StagesStatuses::class,'stageStatusSlug','slug')
        ->orderBy('created_at','DESC');
    }

    public function requestStage()
    {
        return $this->belongsTo(RequestStages::class,'reqStageId','id')
        ->orderBy('created_at','DESC');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'userId','id');
    }


}
