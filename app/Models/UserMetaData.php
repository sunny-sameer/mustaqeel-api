<?php

namespace App\Models;

use App\Models\Traits\DisableSnakeAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserMetaData extends Model
{
    use SoftDeletes, DisableSnakeAttributes;

    protected $table = 'user_meta_data';
    protected $guarded = [];
}
