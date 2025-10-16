<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Traits\HasSlug;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubCategories extends Model
{
    use HasSlug, SoftDeletes;

    protected $table = 'sub_categories';
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Categories::class,'categoryId','id');
    }

    public static function boot()
    {
        parent::boot();

        static::retrieved(function ($model) {
            $model::$snakeAttributes = false;
        });
    }
}
