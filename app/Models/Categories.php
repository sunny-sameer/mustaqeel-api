<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Traits\HasSlug;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categories extends Model
{
    use HasSlug, SoftDeletes;

    protected $table = 'categories';
    protected $guarded = [];



    public function sectors()
    {
        return $this->belongsToMany(Sectors::class, 'category_sector', 'sectorId', 'categoryId');
    }

    public function incubators()
    {
        return $this->hasMany(Incubator::class);
    }
}
