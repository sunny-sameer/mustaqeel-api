<?php

namespace App\Models;

use App\Models\Traits\DisableSnakeAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestMetaData extends Model
{
    use SoftDeletes, DisableSnakeAttributes;

    protected $table = 'request_meta_data';
    protected $guarded = [];

    protected static $modelMap = [
        'category'     => Categories::class,
        'subCategory'  => SubCategories::class,
        'sector'       => Sectors::class,
        'activity'     => Activities::class,
        'subActivity'  => SubActivities::class,
        'entity'       => Entities::class,
        'incubator'    => Incubator::class,
    ];

    public function related()
    {
        $model = self::$modelMap[$this->key] ?? null;

        return $model
            ? $this->belongsTo($model, 'value', 'slug')->select('id','name','nameAr','slug')
            : null;
    }
}
