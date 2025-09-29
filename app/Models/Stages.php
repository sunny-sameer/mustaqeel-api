<?php

namespace App\Models;

use App\Models\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stages extends Model
{
    use HasSlug, SoftDeletes;

    protected $table = 'stages';
    protected $guarded = [];

}
