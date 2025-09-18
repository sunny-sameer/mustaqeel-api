<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasSlug;

class Entities extends Model
{
    use HasSlug;
    //

    protected $fillable = ['name', 'nameAr', 'slug'];

    // public function activities()
    // {
    //     return $this->belongsToMany(Activities::class, 'activity_entity','entityId');
    // }

    public function activities()
{
    return $this->belongsToMany(Activities::class, 'activity_entity', 'entityId', 'activityId')
                ;
}
}
