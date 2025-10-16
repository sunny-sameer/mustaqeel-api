<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Traits\HasSlug;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activities extends Model
{
    use HasSlug, SoftDeletes;

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

    public static function boot()
    {
        parent::boot();

        static::retrieved(function ($model) {
            $model::$snakeAttributes = false;
        });
    }
}
