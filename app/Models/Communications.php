<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Communications extends Model
{
    use SoftDeletes;

    protected $table = 'communications';
    protected $guarded = [];


}
