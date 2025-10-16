<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nationality extends Model
{
    protected $table = 'nationalities';
    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        static::retrieved(function ($model) {
            $model::$snakeAttributes = false;
        });
    }
}
