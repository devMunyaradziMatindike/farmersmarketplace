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
        // Agricultural fields
        'quantity',
        'unit',
        'minimum_order_quantity',
        'packaging_type',
        'views_count',
        'inquiries_count',
        'seller_rating',
        'seller_successful_sales',
        'harvest_date',
        'expiry_date',
        'is_perishable',
        'is_bulk_available',
        'wholesale_only',
        'season',
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
            // Agricultural fields
            'quantity' => 'decimal:2',
            'minimum_order_quantity' => 'decimal:2',
            'views_count' => 'integer',
            'inquiries_count' => 'integer',
            'seller_rating' => 'decimal:2',
            'seller_successful_sales' => 'integer',
            'harvest_date' => 'date',
            'expiry_date' => 'date',
            'is_perishable' => 'boolean',
            'is_bulk_available' => 'boolean',
            'wholesale_only' => 'boolean',
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
     * Enhanced search with full-text search and relevance scoring.
     */
    public function scopeAdvancedSearch($query, string $term, array $filters = [])
    {
        // Use full-text search if available (MySQL/MariaDB), fallback to LIKE for SQLite
        $driver = config('database.default');
        $connection = config("database.connections.{$driver}.driver");

        if ($connection === 'mysql' || $connection === 'mariadb') {
            try {
                $query->selectRaw('products.*, 
                    MATCH(name, description) AGAINST(? IN NATURAL LANGUAGE MODE) AS relevance_score',
                    [$term])
                    ->whereRaw('MATCH(name, description) AGAINST(? IN NATURAL LANGUAGE MODE)', [$term])
                    ->orderByDesc('relevance_score');
            } catch (\Exception $e) {
                // Fallback to LIKE search if full-text index not created yet
                $query->where(function ($q) use ($term) {
                    $q->where('name', 'like', "%{$term}%")
                        ->orWhere('description', 'like', "%{$term}%");
                });
            }
        } else {
            // SQLite or other databases - use LIKE with relevance boosting
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%");
            })
                ->orderByRaw('
                CASE 
                    WHEN name LIKE ? THEN 3
                    WHEN name LIKE ? THEN 2
                    WHEN description LIKE ? THEN 1
                    ELSE 0
                END DESC
            ', ["%{$term}%", "{$term}%", "%{$term}%"]);
        }

        // Apply agricultural filters
        if (isset($filters['min_quantity'])) {
            $query->where('quantity', '>=', $filters['min_quantity']);
        }

        if (isset($filters['max_quantity'])) {
            $query->where('quantity', '<=', $filters['max_quantity']);
        }

        if (isset($filters['unit']) && $filters['unit']) {
            $query->where('unit', $filters['unit']);
        }

        if (isset($filters['min_order_quantity'])) {
            $query->where(function ($q) use ($filters) {
                $q->whereNull('minimum_order_quantity')
                    ->orWhere('minimum_order_quantity', '<=', $filters['min_order_quantity']);
            });
        }

        if (isset($filters['is_bulk_available']) && $filters['is_bulk_available']) {
            $query->where('is_bulk_available', true);
        }

        if (isset($filters['wholesale_only']) && $filters['wholesale_only']) {
            $query->where('wholesale_only', true);
        }

        if (isset($filters['is_perishable']) && $filters['is_perishable']) {
            $query->where('is_perishable', true);
        }

        if (isset($filters['season']) && $filters['season']) {
            $query->where('season', $filters['season']);
        }

        if (isset($filters['packaging_type']) && $filters['packaging_type']) {
            $query->where('packaging_type', $filters['packaging_type']);
        }

        // Boost popular/verified sellers
        if (! isset($filters['sort']) || $filters['sort'] === 'relevance') {
            $query->orderByDesc('seller_rating')
                ->orderByDesc('views_count')
                ->orderByDesc('seller_successful_sales');
        }

        return $query;
    }

    /**
     * Filter by quantity range.
     */
    public function scopeFilterByQuantity($query, ?float $minQuantity = null, ?float $maxQuantity = null)
    {
        if ($minQuantity !== null) {
            $query->where('quantity', '>=', $minQuantity);
        }

        if ($maxQuantity !== null) {
            $query->where('quantity', '<=', $maxQuantity);
        }

        return $query;
    }

    /**
     * Filter by unit.
     */
    public function scopeFilterByUnit($query, ?string $unit = null)
    {
        if ($unit) {
            $query->where('unit', $unit);
        }

        return $query;
    }

    /**
     * Filter by bulk availability.
     */
    public function scopeBulkAvailable($query)
    {
        return $query->where('is_bulk_available', true);
    }

    /**
     * Filter by wholesale only.
     */
    public function scopeWholesaleOnly($query)
    {
        return $query->where('wholesale_only', true);
    }

    /**
     * Increment product views.
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    /**
     * Increment product inquiries.
     */
    public function incrementInquiries(): void
    {
        $this->increment('inquiries_count');
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
