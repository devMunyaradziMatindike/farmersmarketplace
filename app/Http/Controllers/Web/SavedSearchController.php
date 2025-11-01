<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CurrencySetting;
use App\Models\Product;
use App\Models\SavedSearch;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SavedSearchController extends Controller
{
    /**
     * Display a listing of saved searches.
     */
    public function index(): Response
    {
        $savedSearches = SavedSearch::where('user_id', auth()->id())
            ->where('is_active', true)
            ->latest()
            ->get();

        return Inertia::render('SavedSearches/Index', [
            'savedSearches' => $savedSearches,
        ]);
    }

    /**
     * Store a newly created saved search.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'search_criteria' => ['required', 'array'],
            'has_price_alert' => ['boolean'],
            'alert_price_threshold' => ['nullable', 'numeric', 'min:0', 'required_if:has_price_alert,true'],
            'alert_condition' => ['nullable', 'in:below,above,equals', 'required_if:has_price_alert,true'],
        ]);

        $savedSearch = SavedSearch::create([
            'user_id' => auth()->id(),
            'name' => $validated['name'],
            'search_criteria' => $validated['search_criteria'],
            'has_price_alert' => $validated['has_price_alert'] ?? false,
            'alert_price_threshold' => $validated['alert_price_threshold'] ?? null,
            'alert_condition' => $validated['alert_condition'] ?? null,
        ]);

        return back()->with('success', 'Search saved successfully!');
    }

    /**
     * Execute a saved search and show results.
     */
    public function execute(SavedSearch $savedSearch): Response
    {
        // Ensure the search belongs to the authenticated user
        if ($savedSearch->user_id !== auth()->id()) {
            abort(403);
        }

        // Rebuild query from saved search criteria (similar to ProductController::index)
        $query = Product::with(['category', 'user', 'photos'])
            ->available();

        $criteria = $savedSearch->search_criteria;

        // Apply search term
        if (isset($criteria['q']) && $criteria['q']) {
            $searchFilters = array_intersect_key($criteria, array_flip([
                'min_quantity', 'max_quantity', 'unit', 'min_order_quantity',
                'is_bulk_available', 'wholesale_only', 'is_perishable',
                'season', 'packaging_type', 'sort',
            ]));
            $query->advancedSearch($criteria['q'], $searchFilters);
        } elseif (isset($criteria['search']) && $criteria['search']) {
            $query->searchByName($criteria['search']);
        }

        // Apply category filter
        if (isset($criteria['category_id']) && $criteria['category_id']) {
            $query->where('category_id', $criteria['category_id']);
        }

        // Apply price range
        if (isset($criteria['min_price']) || isset($criteria['max_price'])) {
            $query->filterByPrice($criteria['min_price'] ?? null, $criteria['max_price'] ?? null);
        }

        // Apply quantity filters
        if (isset($criteria['min_quantity']) || isset($criteria['max_quantity'])) {
            $query->filterByQuantity($criteria['min_quantity'] ?? null, $criteria['max_quantity'] ?? null);
        }

        if (isset($criteria['unit']) && $criteria['unit']) {
            $query->filterByUnit($criteria['unit']);
        }

        if (isset($criteria['is_bulk_available']) && $criteria['is_bulk_available']) {
            $query->bulkAvailable();
        }

        if (isset($criteria['wholesale_only']) && $criteria['wholesale_only']) {
            $query->wholesaleOnly();
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

        $products = $query->paginate(16);

        // Get categories and stats (similar to ProductController)
        $categoriesWithCount = Category::withCount([
            'products' => fn ($query) => $query->where('status', 'available'),
        ])->get();

        $stats = [
            'products' => Product::where('status', 'available')->count(),
            'sellers' => User::where('role', 'seller')->count(),
            'categories' => Category::count(),
        ];

        $currencySettings = CurrencySetting::current();
        $exchangeRate = $currencySettings?->zwg_to_usd_rate ?? 13.5000;

        return Inertia::render('Products/Index', [
            'products' => $products,
            'categories' => Category::all(),
            'categoriesWithCount' => $categoriesWithCount,
            'filters' => $criteria,
            'stats' => $stats,
            'exchangeRate' => $exchangeRate,
            'locations' => User::whereNotNull('location')
                ->where('location', '!=', '')
                ->distinct()
                ->pluck('location')
                ->sort()
                ->values(),
            'savedSearchId' => $savedSearch->id,
        ]);
    }

    /**
     * Update the specified saved search.
     */
    public function update(Request $request, SavedSearch $savedSearch)
    {
        if ($savedSearch->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'has_price_alert' => ['boolean'],
            'alert_price_threshold' => ['nullable', 'numeric', 'min:0'],
            'alert_condition' => ['nullable', 'in:below,above,equals'],
            'is_active' => ['boolean'],
        ]);

        $savedSearch->update($validated);

        return back()->with('success', 'Search updated successfully!');
    }

    /**
     * Remove the specified saved search.
     */
    public function destroy(SavedSearch $savedSearch)
    {
        if ($savedSearch->user_id !== auth()->id()) {
            abort(403);
        }

        $savedSearch->delete();

        return back()->with('success', 'Search deleted successfully!');
    }
}
