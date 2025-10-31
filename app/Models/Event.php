<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'type',
        'image',
        'start_date',
        'end_date',
        'location',
        'latitude',
        'longitude',
        'capacity',
        'registration_fee',
        'currency',
        'is_registration_open',
        'is_featured',
        'registration_instructions',
        'organizer_name',
        'organizer_contact',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_registration_open' => 'boolean',
        'is_featured' => 'boolean',
        'registration_fee' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    protected $appends = ['registration_count', 'is_full'];

    public function registrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function getRegistrationCountAttribute(): int
    {
        return $this->registrations()->where('status', 'confirmed')->count();
    }

    public function getIsFullAttribute(): bool
    {
        if (! $this->capacity) {
            return false;
        }

        return $this->registration_count >= $this->capacity;
    }
}
