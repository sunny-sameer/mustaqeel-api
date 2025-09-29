<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestMetaData extends Model
{
    use SoftDeletes;

    protected $table = 'request_meta_data';
    protected $guarded = [];
}
