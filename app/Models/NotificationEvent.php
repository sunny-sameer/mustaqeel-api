<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class NotificationEvent extends Model
{
    protected $table = 'notification_events';
    protected $guarded = [];

    public function templates(): BelongsToMany
    {
        return $this->belongsToMany(
            NotificationTemplate::class,
            'notification_event_template'
        );
    }
}
