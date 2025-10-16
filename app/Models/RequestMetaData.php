<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestMetaData extends Model
{
    use SoftDeletes;

    protected $table = 'request_meta_data';
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Categories::class,'catSlug','slug');
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategories::class,'subCatSlug','slug');
    }

    public function sector()
    {
        return $this->belongsTo(Sectors::class,'sectorSlug','slug');
    }

    public function activity()
    {
        return $this->belongsTo(Activities::class,'activitySlug','slug');
    }

    public function subActivity()
    {
        return $this->belongsTo(SubActivities::class,'subActivitySlug','slug');
    }

    public function entity()
    {
        return $this->belongsTo(Entities::class,'entitySlug','slug');
    }

    public function incubator()
    {
        return $this->belongsTo(Incubator::class,'incubatorSlug','slug');
    }

    public static function boot()
    {
        parent::boot();

        static::retrieved(function ($model) {
            $model::$snakeAttributes = false;
        });
    }
}
