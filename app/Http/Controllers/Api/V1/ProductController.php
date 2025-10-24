<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Product\SearchProductRequest;
use App\Http\Resources\Api\V1\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    /**
     * Display a listing of available products.
     */
    public function index(SearchProductRequest $request): AnonymousResourceCollection
    {
        $query = Product::with(['category', 'user', 'photos'])
            ->available();

        // Search by name
        if ($request->filled('name')) {
            $query->searchByName($request->name);
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by price range
        if ($request->filled('min_price') || $request->filled('max_price')) {
            $query->filterByPrice($request->min_price, $request->max_price);
        }

        // Filter by location (nearby)
        if ($request->filled('latitude') && $request->filled('longitude')) {
            $radius = $request->radius ?? 50;
            $query->nearby($request->latitude, $request->longitude, $radius);
        }

        // Sorting
        $sort = $request->sort ?? 'latest';
        match ($sort) {
            'cheapest' => $query->orderBy('price', 'asc'),
            'nearest' => null, // Already ordered by distance in nearby scope
            default => $query->orderBy('date_listed', 'desc'),
        };

        $perPage = $request->per_page ?? 15;
        $products = $query->paginate($perPage);

        return ProductResource::collection($products);
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product): JsonResponse
    {
        $product->load(['category', 'user', 'photos']);

        return response()->json([
            'data' => new ProductResource($product),
        ]);
    }

    /**
     * Search products (alias for index with search parameters).
     */
    public function search(SearchProductRequest $request): AnonymousResourceCollection
    {
        return $this->index($request);
    }
}
