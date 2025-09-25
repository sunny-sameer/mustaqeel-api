<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class activityEntity extends Model
{
    protected $table = 'activity_entity';
    protected $guarded = [];


    public function activity()
    {
        return $this->belongsTo(Activities::class);
    }

    public function entity()
    {
        return $this->belongsTo(Entities::class);
    }
}
