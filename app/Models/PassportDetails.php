<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PassportDetails extends Model
{
    use SoftDeletes;

    protected $table = 'passport_details';
    protected $guarded = [];
}
