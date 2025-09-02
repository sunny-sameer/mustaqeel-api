<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class NotificationTemplate extends Model
{
    protected $fillable = [
        'subject',
        'default_content',
        'custom_content',
        'type',
        'placeholders'
    ];

    protected $casts = [
        'placeholders' => 'array'
    ];

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(
            NotificationEvent::class,
            'notification_event_template'
        );
    }
}
