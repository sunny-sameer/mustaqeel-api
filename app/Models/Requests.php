<?php

namespace App\Models;

use App\Models\Traits\DisableSnakeAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Requests extends Model
{
    use SoftDeletes, DisableSnakeAttributes;

    protected $table = 'requests';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'userId','id');
    }

    public function metas()
    {
        return $this->hasMany(RequestMetaData::class, 'reqId');
    }

    public function category()
    {
        return $this->hasOne(RequestMetaData::class, 'reqId','id')
        ->where('key','category');
    }

    public function activity()
    {
        return $this->hasOne(RequestMetaData::class, 'reqId','id')
        ->where('key','activity');
    }

    public function subActivity()
    {
        return $this->hasOne(RequestMetaData::class, 'reqId','id')
        ->where('key','subActivity');
    }

    public function entity()
    {
        return $this->hasOne(RequestMetaData::class, 'reqId','id')
        ->where('key','entity');
    }

    public function incubator()
    {
        return $this->hasOne(RequestMetaData::class, 'reqId','id')
        ->where('key','incubator');
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

    public function qualityCheck()
    {
        return $this->hasOne(QualityCheck::class,'reqId','id')
        ->orderBy('created_at','DESC');
    }

    public function qualityChecks()
    {
        return $this->hasMany(QualityCheck::class,'reqId','id')
        ->orderBy('created_at','DESC');
    }

    public function secureCode()
    {
        return $this->hasOne(RequestTypeCodes::class,'reqId','id');
    }
}
