<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentAccessLog extends Model
{
    protected $fillable = [
        'documentId',
        'userId', 
        'ipAddress',
        'userAgent',
        'accessedAt'
    ];

    protected $casts = [
        'accessedAt' => 'datetime'
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