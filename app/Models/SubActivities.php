<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasSlug;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubActivities extends Model
{
    use HasSlug, SoftDeletes;

    protected $table = 'sub_activities';
    protected $guarded = [];

    public function activity()
    {
        return $this->belongsTo(Activities::class);
    }
}
