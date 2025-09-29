<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestStatuses extends Model
{
    use SoftDeletes;

    protected $table = 'request_statuses';
    protected $guarded = [];
}
