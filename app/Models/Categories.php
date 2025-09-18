<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Traits\HasSlug;

class Categories extends Model
{
    use HasSlug;



    public function sectors()
    {
        return $this->belongsToMany(Sectors::class, 'category_sector', 'sectorId', 'categoryId');
    }

    public function incubators()
    {
        return $this->hasMany(Incubator::class);
    }
}
