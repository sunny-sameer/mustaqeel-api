<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestAttribute extends Model
{
    use SoftDeletes;

    protected $table = 'request_attributes';
    protected $guarded = [];


}
