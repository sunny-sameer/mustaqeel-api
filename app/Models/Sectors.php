<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Traits\HasSlug;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sectors extends Model
{
    use HasSlug, SoftDeletes;

    protected $table = 'sectors';
    protected $guarded = [];


    public function categories()
    {
        return $this->belongsToMany(Categories::class, 'category_sector', 'sectorId', 'categoryId');
    }


    public function activities()
    {
        return $this->hasMany(Activities::class);
    }
}
