<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Traits\HasSlug;


class Activities extends Model
{
    use HasSlug;

    protected $table = 'activities';
    protected $guarded = [];



    public function sector()
    {
        return $this->belongsTo(Sectors::class, 'sectorId', 'id');
    }

    public function subActivities()
    {
        return $this->hasMany(SubActivities::class, 'activityId', 'id');
    }

    // public function entities()
    // {
    //     return $this->belongsToMany(Entities::class, 'activity_entity');
    // }

    public function entities()
    {
        return $this->belongsToMany(Entities::class, 'activity_entity', 'activityId', 'entityId');
    }
}
