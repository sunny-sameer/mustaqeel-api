<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestStages extends Model
{
    use SoftDeletes;

    protected $table = 'request_stages';
    protected $guarded = [];

    public function status()
    {
        return $this->hasMany(RequestStatuses::class,'reqStageId','id');
    }
}
