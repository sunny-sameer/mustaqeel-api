<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Traits\HasSlug;


class SubCategories extends Model
{
    use HasSlug;

    protected $table = 'sub_categories';
    protected $guarded = [];
}
