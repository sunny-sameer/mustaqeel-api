<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestTypeCodes extends Model
{
    use SoftDeletes;

    protected $table = 'request_type_codes';
    protected $guarded = [];
}
