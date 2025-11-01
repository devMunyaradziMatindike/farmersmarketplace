<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CurrencySetting;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of available products.
     */
    public function index(Request $request): Response
    {
        $query = Product::with(['category', 'user', 'photos'])
            ->available();

        // Enhanced search functionality with agricultural filters
        $searchFilters = [];

        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $searchFilters = $request->only([
                'min_quantity', 'max_quantity', 'unit', 'min_order_quantity',
                'is_bulk_available', 'wholesale_only', 'is_perishable',
                'season', 'packaging_type', 'sort',
            ]);
            $query->advancedSearch($searchTerm, $searchFilters);
        } elseif ($request->filled('search')) {
            // Legacy search parameter
            $query->searchByName($request->search);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        } elseif ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by price range
        if ($request->filled('min_price') || $request->filled('max_price')) {
            $query->filterByPrice($request->min_price, $request->max_price);
        }

        // Agricultural quantity filters (if not already in advanced search)
        if ($request->filled('q') === false) {
            if ($request->filled('min_quantity') || $request->filled('max_quantity')) {
                $query->filterByQuantity($request->min_quantity, $request->max_quantity);
            }

            if ($request->filled('unit')) {
                $query->filterByUnit($request->unit);
            }

            if ($request->filled('is_bulk_available')) {
                $query->bulkAvailable();
            }

            if ($request->filled('wholesale_only')) {
                $query->wholesaleOnly();
            }

            if ($request->filled('is_perishable')) {
                $query->where('is_perishable', true);
            }

            if ($request->filled('season')) {
                $query->where('season', $request->season);
            }

            if ($request->filled('packaging_type')) {
                $query->where('packaging_type', $request->packaging_type);
            }
        }

        // Filter by location
        if ($request->filled('location')) {
            $query->whereHas('user', function ($userQuery) use ($request) {
                $userQuery->where('location', 'like', "%{$request->location}%");
            });
        }

        // Filter by location (nearby)
        if ($request->filled('latitude') && $request->filled('longitude')) {
            $radius = $request->radius ?? 50;
            $query->nearby($request->latitude, $request->longitude, $radius);
        }

        // Enhanced sorting
        $sort = $request->sort ?? 'relevance';
        match ($sort) {
            'price_low' => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            'newest' => $query->orderBy('date_listed', 'desc'),
            'popular' => $query->orderBy('views_count', 'desc'),
            'cheapest' => $query->orderBy('price', 'asc'), // Legacy
            'nearest' => null, // Already ordered by distance in nearby scope
            'relevance' => $query->orderBy('date_listed', 'desc'), // Default
            default => $query->orderBy('date_listed', 'desc'),
        };

        $products = $query->paginate(16)->withQueryString();

        // Get categories with product counts
        $categoriesWithCount = Category::withCount([
            'products' => fn ($query) => $query->where('status', 'available'),
        ])->get();

        // Calculate stats
        $stats = [
            'products' => Product::where('status', 'available')->count(),
            'sellers' => User::where('role', 'seller')->count(),
            'categories' => Category::count(),
        ];

        // Get currency settings for conversion
        $currencySettings = CurrencySetting::current();
        $exchangeRate = $currencySettings?->zwg_to_usd_rate ?? 13.5000;

        // Get unique locations for filter dropdown (if location column exists)
        $locations = collect();
        try {
            $locations = User::whereNotNull('location')
                ->where('location', '!=', '')
                ->distinct()
                ->pluck('location')
                ->sort()
                ->values();
        } catch (\Exception $e) {
            // Location column doesn't exist, return empty collection
            $locations = collect();
        }

        return Inertia::render('Products/Index', [
            'products' => $products,
            'categories' => Category::all(),
            'categoriesWithCount' => $categoriesWithCount,
            'filters' => $request->only([
                'q', 'search', 'category', 'category_id', 'sort',
                'min_price', 'max_price', 'location',
                'min_quantity', 'max_quantity', 'unit', 'min_order_quantity',
                'is_bulk_available', 'wholesale_only', 'is_perishable',
                'season', 'packaging_type',
            ]),
            'stats' => $stats,
            'exchangeRate' => $exchangeRate,
            'locations' => $locations,
        ]);
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product): Response
    {
        $product->load(['category', 'user', 'photos']);

        // Increment views count
        $product->incrementViews();

        return Inertia::render('Products/Show', [
            'product' => $product,
        ]);
    }
}
