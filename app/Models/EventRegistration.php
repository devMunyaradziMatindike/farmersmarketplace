<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventRegistration extends Model
{
    protected $fillable = [
        'event_id',
        'user_id',
        'registration_type',
        'name',
        'email',
        'phone',
        'organization_name',
        'additional_info',
        'status',
    ];

    protected $casts = [
        'event_id' => 'integer',
        'user_id' => 'integer',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
