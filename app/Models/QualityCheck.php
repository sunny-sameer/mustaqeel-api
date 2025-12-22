<?php

namespace App\Models;

use App\Models\Traits\DisableSnakeAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QualityCheck extends Model
{
    use SoftDeletes, DisableSnakeAttributes;

    protected $table = 'quality_checks';
    protected $guarded = [];
}
