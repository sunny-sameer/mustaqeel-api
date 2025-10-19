<?php

namespace App\Models;

use App\Models\Traits\DisableSnakeAttributes;
use Illuminate\Database\Eloquent\Model;

use App\Models\Traits\HasSlug;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubCategories extends Model
{
    use HasSlug, SoftDeletes, DisableSnakeAttributes;

    protected $table = 'sub_categories';
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Categories::class,'categoryId','id');
    }


}
