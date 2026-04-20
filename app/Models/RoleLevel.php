<?php

namespace App\Models;

use App\Models\Traits\DisableSnakeAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Role;

class RoleLevel extends Model
{
    use SoftDeletes, DisableSnakeAttributes;

    protected $table = 'role_levels';
    protected $guarded = [];

    public function role() {
        return $this->belongsTo(Role::class,'role_id','id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_role_levels');
    }
}
