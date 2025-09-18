<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasSlug;


class SubActivities extends Model
{
    //
    use HasSlug;

    protected $fillable = ['activityId', 'nameEn', 'nameAr', 'slug'];

    public function activity()
    {
        return $this->belongsTo(Activities::class);
    }
}
