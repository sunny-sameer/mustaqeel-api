<?php

namespace App\Models;

use App\Models\Traits\DisableSnakeAttributes;
use App\Models\Traits\HasCamelSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormFieldMeta extends Model
{
    use HasCamelSlug, SoftDeletes, DisableSnakeAttributes;

    protected $table = 'form_field_metas';
    protected $guarded = [];
}
