<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentAccessLog extends Model
{
    protected $fillable = [
        'document_id',
        'user_id', 
        'ip_address',
        'user_agent',
        'accessed_at'
    ];

    protected $casts = [
        'accessed_at' => 'datetime'
    ];

    public function document()
    {
        return $this->belongsTo(Documents::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}