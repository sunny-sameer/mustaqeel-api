<?php

namespace App\Models;

use App\Models\Traits\DisableSnakeAttributes;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasSlug;
use Illuminate\Database\Eloquent\SoftDeletes;

class Incubator extends Model
{
    use HasSlug, SoftDeletes, DisableSnakeAttributes;

    protected $table = 'incubators';
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Categories::class,'categoryId','id');
    }


}
