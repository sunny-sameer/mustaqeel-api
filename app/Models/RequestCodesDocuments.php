<?php

namespace App\Models;

use App\Models\Traits\DisableSnakeAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestCodesDocuments extends Model
{
    use SoftDeletes, DisableSnakeAttributes;

    protected $table = 'request_codes_documents';
    protected $guarded = [];


}
