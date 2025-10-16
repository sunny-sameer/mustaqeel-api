<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Requests extends Model
{
    use SoftDeletes;

    protected $table = 'requests';
    protected $guarded = [];

    public function metas()
    {
        return $this->hasOne(RequestMetaData::class,'reqId','id');
    }

    public function attributes()
    {
        return $this->hasMany(RequestAttribute::class,'reqId','id');
    }

    public function requestStage()
    {
        return $this->hasMany(RequestStages::class,'reqId','id');
    }

    public function documents()
    {
        return $this->morphMany(Documents::class, 'entity', 'entityType', 'entityId');
    }

    public static function boot()
    {
        parent::boot();

        static::retrieved(function ($model) {
            $model::$snakeAttributes = false;
        });
    }
}
