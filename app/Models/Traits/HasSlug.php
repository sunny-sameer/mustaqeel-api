<?php


namespace App\Models\Traits;

use Illuminate\Support\Str;



trait HasSlug
{


    public static function generateSlug(string $string): string
    {
        $words = explode(' ', preg_replace('/[^A-Za-z0-9 ]/', '', $string));

        if (count($words) === 1) {
            // Single word: take first 3 letters
            $slug = strtolower(substr($words[0], 0, 3));
        } else {
            // Multiple words: take first letter of each word
            preg_match_all('/\b([A-Za-z])/', $string, $matches);
            $slug = strtolower(implode('', $matches[1]));
        }

        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }

    protected static function bootHasSlug()
    {
        static::creating(function ($model) {
            if (empty($model->slug) && !empty($model->name)) {
                $model->slug = static::generateSlug($model->name);
            }
        });
    }
}
