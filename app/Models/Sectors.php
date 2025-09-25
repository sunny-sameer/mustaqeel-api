<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Traits\HasSlug;


class Sectors extends Model
{
    use HasSlug;

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
