<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QatarInfo extends Model
{
    use SoftDeletes;

    protected $table = 'qatar_info';
    protected $guarded = [];
}
