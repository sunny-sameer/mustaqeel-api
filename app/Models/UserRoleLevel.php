<?php

namespace App\Models;

use App\Models\Traits\DisableSnakeAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserRoleLevel extends Model
{
    use SoftDeletes, DisableSnakeAttributes;

    protected $table = 'user_role_levels';
    protected $guarded = [];
}
