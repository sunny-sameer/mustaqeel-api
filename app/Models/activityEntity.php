<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class activityEntity extends Model
{
    //


    public function activity()
    {
        return $this->belongsTo(Activities::class);
    }

    public function entity()
    {
        return $this->belongsTo(Entities::class);
    }
}
