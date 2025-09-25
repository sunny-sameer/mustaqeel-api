<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasSlug;

class Incubator extends Model
{
    use HasSlug;

    protected $table = 'incubators';
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Categories::class);
    }
}
