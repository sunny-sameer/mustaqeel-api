<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QatarInfo extends Model
{
    use SoftDeletes;

    protected $table = 'qatar_info';
    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        static::retrieved(function ($model) {
            $model::$snakeAttributes = false;
        });
    }
}
