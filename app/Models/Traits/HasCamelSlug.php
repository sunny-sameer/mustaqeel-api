<?php


namespace App\Models\Traits;


use Illuminate\Support\Str;


trait HasCamelSlug
{
    protected static function bootHasCamelSlug()
    {
        static::creating(function ($model) {
            if (isset($model->nameEn) && empty($model->slug)) {
                $model->slug = self::toCamelSlug($model->nameEn);
            }
        });

        static::updating(function ($model) {
            if ($model->isDirty('nameEn')) {
                $model->slug = self::toCamelSlug($model->nameEn);
            }
        });
    }

    public static function toCamelSlug($string)
    {
        $string = preg_replace('/[^A-Za-z0-9 ]/', '', $string);
        $string = strtolower($string);
        $words = explode(' ', $string);

        $camel = array_shift($words);
        foreach ($words as $word) {
            $camel .= ucfirst($word);
        }

        return $camel;
    }
}
