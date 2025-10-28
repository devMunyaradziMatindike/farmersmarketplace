<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'description',
        'price',
        'currency',
        'location',
        'latitude',
        'longitude',
        'contact_details',
        'status',
        'date_listed',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'date_listed' => 'datetime',
        ];
    }

    /**
     * Get the user (seller) that owns the product.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category that owns the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the photos for the product.
     */
    public function photos(): HasMany
    {
        return $this->hasMany(ProductPhoto::class)->orderBy('order');
    }

    /**
     * Get the primary photo for the product.
     */
    public function primaryPhoto(): HasMany
    {
        return $this->hasMany(ProductPhoto::class)->where('is_primary', true)->limit(1);
    }

    /**
     * Scope a query to only include available products.
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    /**
     * Scope a query to search products by name.
     */
    public function scopeSearchByName($query, string $name)
    {
        return $query->where('name', 'like', '%'.$name.'%');
    }

    /**
     * Scope a query to filter products by price range.
     */
    public function scopeFilterByPrice($query, ?float $minPrice = null, ?float $maxPrice = null)
    {
        if ($minPrice !== null) {
            $query->where('price', '>=', $minPrice);
        }

        if ($maxPrice !== null) {
            $query->where('price', '<=', $maxPrice);
        }

        return $query;
    }

    /**
     * Scope a query to filter products by location (nearby).
     */
    public function scopeNearby($query, float $latitude, float $longitude, float $radiusInKm = 50)
    {
        // Haversine formula to calculate distance
        return $query->selectRaw(
            '*, ( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance',
            [$latitude, $longitude, $latitude]
        )
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->having('distance', '<=', $radiusInKm)
            ->orderBy('distance');
    }

    /**
     * Get price in specified currency.
     */
    public function getPriceInCurrency(string $currency): float
    {
        if ($this->currency === $currency) {
            return $this->price;
        }

        return CurrencySetting::convert($this->price, $this->currency, $currency);
    }

    /**
     * Get formatted price with currency symbol.
     */
    public function getFormattedPriceAttribute(): string
    {
        $symbol = $this->currency === 'USD' ? '$' : 'ZWG$';

        return $symbol.number_format($this->price, 2);
    }

    /**
     * Get price in both currencies for display.
     */
    public function getPricesAttribute(): array
    {
        return [
            'original' => [
                'amount' => $this->price,
                'currency' => $this->currency ?? 'USD',
                'formatted' => $this->formatted_price,
            ],
            'usd' => [
                'amount' => $this->getPriceInCurrency('USD'),
                'formatted' => '$'.number_format($this->getPriceInCurrency('USD'), 2),
            ],
            'zwg' => [
                'amount' => $this->getPriceInCurrency('ZWG'),
                'formatted' => 'ZWG$'.number_format($this->getPriceInCurrency('ZWG'), 2),
            ],
        ];
    }
}
