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

        // Enhanced search functionality
        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('description', 'like', "%{$searchTerm}%")
                    ->orWhereHas('category', function ($catQuery) use ($searchTerm) {
                        $catQuery->where('name', 'like', "%{$searchTerm}%");
                    });
            });
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
            'filters' => $request->only(['q', 'search', 'category', 'category_id', 'sort', 'min_price', 'max_price', 'location']),
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

        return Inertia::render('Products/Show', [
            'product' => $product,
        ]);
    }
}
