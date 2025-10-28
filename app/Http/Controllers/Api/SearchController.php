<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    /**
     * Get search suggestions based on query
     */
    public function suggestions(Request $request)
    {
        $query = $request->get('q');

        if (strlen($query) < 2) {
            return response()->json(['suggestions' => []]);
        }

        // Get suggestions from multiple sources
        $suggestions = collect();

        // Product name suggestions
        $productSuggestions = Product::where('name', 'like', "%{$query}%")
            ->limit(5)
            ->pluck('name');
        $suggestions = $suggestions->merge($productSuggestions);

        // Category suggestions
        $categorySuggestions = Category::where('name', 'like', "%{$query}%")
            ->limit(3)
            ->pluck('name');
        $suggestions = $suggestions->merge($categorySuggestions);

        // Location suggestions
        $locationSuggestions = User::where('location', 'like', "%{$query}%")
            ->distinct()
            ->limit(3)
            ->pluck('location')
            ->filter(); // Remove empty locations
        $suggestions = $suggestions->merge($locationSuggestions);

        return response()->json([
            'suggestions' => $suggestions->unique()->values()->toArray(),
        ]);
    }

    /**
     * Get popular searches
     */
    public function popular()
    {
        // Get popular searches from search logs
        $popularSearches = DB::table('search_logs')
            ->select('query', DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('query')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'popular' => $popularSearches->map(function ($item) {
                return [
                    'term' => $item->query,
                    'count' => $item->count,
                ];
            }),
        ]);
    }

    /**
     * Perform advanced search with filters
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        $category = $request->get('category');
        $minPrice = $request->get('min_price');
        $maxPrice = $request->get('max_price');
        $location = $request->get('location');
        $sort = $request->get('sort', 'relevance');

        // Log the search
        $this->logSearch($query, $request->all());

        $products = Product::with(['photos', 'category', 'user'])
            ->when($query, function ($q) use ($query) {
                $q->where(function ($queryBuilder) use ($query) {
                    $queryBuilder->where('name', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%")
                        ->orWhereHas('category', function ($catQuery) use ($query) {
                            $catQuery->where('name', 'like', "%{$query}%");
                        });
                });
            })
            ->when($category, function ($q) use ($category) {
                $q->where('category_id', $category);
            })
            ->when($minPrice, function ($q) use ($minPrice) {
                $q->where('price', '>=', $minPrice);
            })
            ->when($maxPrice, function ($q) use ($maxPrice) {
                $q->where('price', '<=', $maxPrice);
            })
            ->when($location, function ($q) use ($location) {
                $q->whereHas('user', function ($userQuery) use ($location) {
                    $userQuery->where('location', 'like', "%{$location}%");
                });
            })
            ->when($sort === 'price_low', function ($q) {
                $q->orderBy('price', 'asc');
            })
            ->when($sort === 'price_high', function ($q) {
                $q->orderBy('price', 'desc');
            })
            ->when($sort === 'newest', function ($q) {
                $q->orderBy('created_at', 'desc');
            })
            ->when($sort === 'popular', function ($q) {
                $q->orderBy('views_count', 'desc');
            })
            ->when($sort === 'relevance', function ($q) use ($query) {
                if ($query) {
                    // Custom relevance scoring
                    $q->orderByRaw('
                        CASE 
                            WHEN name LIKE ? THEN 1
                            WHEN description LIKE ? THEN 2
                            ELSE 3
                        END
                    ', ["%{$query}%", "%{$query}%"])
                        ->orderBy('views_count', 'desc');
                } else {
                    $q->orderBy('created_at', 'desc');
                }
            })
            ->paginate(20);

        return response()->json([
            'products' => $products,
            'total' => $products->total(),
            'filters' => [
                'query' => $query,
                'category' => $category,
                'min_price' => $minPrice,
                'max_price' => $maxPrice,
                'location' => $location,
                'sort' => $sort,
            ],
        ]);
    }

    /**
     * Get search analytics for admin
     */
    public function analytics()
    {
        $popularSearches = DB::table('search_logs')
            ->select('query', DB::raw('COUNT(*) as searches'), DB::raw('AVG(results_count) as avg_results'))
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('query')
            ->orderBy('searches', 'desc')
            ->limit(20)
            ->get();

        $searchTrends = DB::table('search_logs')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as searches'))
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $zeroResultSearches = DB::table('search_logs')
            ->where('results_count', 0)
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('query')
            ->orderByRaw('COUNT(*) DESC')
            ->limit(10)
            ->pluck('query');

        return response()->json([
            'popular_searches' => $popularSearches,
            'search_trends' => $searchTrends,
            'zero_result_searches' => $zeroResultSearches,
        ]);
    }

    /**
     * Log search query for analytics
     */
    private function logSearch($query, $filters = [])
    {
        if ($query && strlen($query) > 2) {
            DB::table('search_logs')->insert([
                'query' => $query,
                'user_id' => auth()->id(),
                'ip_address' => request()->ip(),
                'filters' => json_encode($filters),
                'results_count' => 0, // Will be updated after search
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
