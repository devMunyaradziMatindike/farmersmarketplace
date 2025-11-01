<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SavedSearch extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'search_criteria',
        'has_price_alert',
        'alert_price_threshold',
        'alert_condition',
        'is_active',
        'last_notified_at',
    ];

    protected $casts = [
        'search_criteria' => 'array',
        'has_price_alert' => 'boolean',
        'is_active' => 'boolean',
        'alert_price_threshold' => 'decimal:2',
        'last_notified_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Execute the saved search and return matching products.
     * Note: This is a helper method. For pagination, use SavedSearchController::execute instead.
     */
    public function executeSearch()
    {
        $query = Product::with(['category', 'user', 'photos'])
            ->available();

        $criteria = $this->search_criteria;

        // Apply search term if exists
        if (isset($criteria['q']) && $criteria['q']) {
            $query->advancedSearch($criteria['q'], $criteria);
        } elseif (isset($criteria['search']) && $criteria['search']) {
            $query->searchByName($criteria['search']);
        }

        // Apply category filter
        if (isset($criteria['category_id']) && $criteria['category_id']) {
            $query->where('category_id', $criteria['category_id']);
        }

        // Apply price range
        if (isset($criteria['min_price']) || isset($criteria['max_price'])) {
            $query->filterByPrice(
                $criteria['min_price'] ?? null,
                $criteria['max_price'] ?? null
            );
        }

        // Apply quantity range
        if (isset($criteria['min_quantity']) || isset($criteria['max_quantity'])) {
            $query->filterByQuantity(
                $criteria['min_quantity'] ?? null,
                $criteria['max_quantity'] ?? null
            );
        }

        // Apply unit filter
        if (isset($criteria['unit']) && $criteria['unit']) {
            $query->filterByUnit($criteria['unit']);
        }

        // Apply bulk/wholesale filters
        if (isset($criteria['is_bulk_available']) && $criteria['is_bulk_available']) {
            $query->bulkAvailable();
        }

        if (isset($criteria['wholesale_only']) && $criteria['wholesale_only']) {
            $query->wholesaleOnly();
        }

        // Apply location filter
        if (isset($criteria['location']) && $criteria['location']) {
            $query->whereHas('user', function ($userQuery) use ($criteria) {
                $userQuery->where('location', 'like', "%{$criteria['location']}%");
            });
        }

        // Apply nearby filter
        if (isset($criteria['latitude']) && isset($criteria['longitude'])) {
            $radius = $criteria['radius'] ?? 50;
            $query->nearby($criteria['latitude'], $criteria['longitude'], $radius);
        }

        // Apply sorting
        $sort = $criteria['sort'] ?? 'relevance';
        match ($sort) {
            'price_low' => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            'newest' => $query->orderBy('date_listed', 'desc'),
            'popular' => $query->orderBy('views_count', 'desc'),
            default => $query->orderBy('date_listed', 'desc'),
        };

        return $query->get();
    }

    /**
     * Check if price alert conditions are met and notify user.
     */
    public function checkPriceAlert(): bool
    {
        if (! $this->has_price_alert || ! $this->is_active) {
            return false;
        }

        $products = $this->executeSearch();

        foreach ($products as $product) {
            $match = match ($this->alert_condition) {
                'below' => $product->price < $this->alert_price_threshold,
                'above' => $product->price > $this->alert_price_threshold,
                'equals' => $product->price == $this->alert_price_threshold,
                default => false,
            };

            if ($match) {
                // Update last notified timestamp
                $this->update(['last_notified_at' => now()]);

                // TODO: Send notification (email, SMS, or in-app)
                // For now, just return true to indicate alert was triggered
                return true;
            }
        }

        return false;
    }
}
