<?php

namespace App\Models;

use App\Models\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StagesStatuses extends Model
{
    use HasSlug, SoftDeletes;

    protected $table = 'stages_statuses';
    protected $guarded = [];

    public function stage()
    {
        return $this->belongsTo(Stages::class,'stageId','id');
    }
}
