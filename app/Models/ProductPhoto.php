<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductPhoto extends Model
{
    /** @use HasFactory<\Database\Factories\ProductPhotoFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'product_id',
        'photo_path',
        'is_primary',
        'order',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = ['photo_url'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
        ];
    }

    /**
     * Get the product that owns the photo.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the full URL for the photo.
     */
    public function getPhotoUrlAttribute(): string
    {
        // Check if photo is in public/farm directory (demo images)
        if (str_starts_with($this->photo_path, 'farm/')) {
            return asset($this->photo_path);
        }

        // Otherwise, use storage URL (uploaded images)
        return asset('storage/'.$this->photo_path);
    }
}
