<?php


namespace App\Models\Traits;


trait DisableSnakeAttributes
{
    protected static function bootDisableSnakeAttributes()
    {
        static::retrieved(function ($model) {
            $model::$snakeAttributes = false;
        });
    }
}
