<?php

namespace App\Models;

use App\Models\Traits\DisableSnakeAttributes;
use App\Models\Traits\HasCamelSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormFields extends Model
{
    use HasCamelSlug, SoftDeletes, DisableSnakeAttributes;

    protected $table = 'form_fields';
    protected $guarded = [];

    public function formMetas()
    {
        return $this->morphOne(RequestMetaData::class,'model','modelType','modelId');
    }
}
