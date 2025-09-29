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
}
