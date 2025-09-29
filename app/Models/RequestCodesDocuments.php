<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestCodesDocuments extends Model
{
    use SoftDeletes;

    protected $table = 'request_codes_documents';
    protected $guarded = [];
}
