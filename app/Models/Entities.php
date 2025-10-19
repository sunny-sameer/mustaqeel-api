<?php

namespace App\Models;

use App\Models\Traits\DisableSnakeAttributes;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasSlug;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entities extends Model
{
    use HasSlug, SoftDeletes, DisableSnakeAttributes;

    protected $table = 'entities';
    protected $guarded = [];

    // public function activities()
    // {
    //     return $this->belongsToMany(Activities::class, 'activity_entity','entityId');
    // }

    public function activities()
    {
        return $this->belongsToMany(Activities::class, 'activity_entity', 'entityId', 'activityId');
    }


}
