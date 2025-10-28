<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class MarketPricingController extends Controller
{
    private array $categories = [
        'fruits_vegetables' => 'Fruits & Vegetables',
        'livestock' => 'Livestock',
        'chemicals' => 'Agricultural Chemicals',
        'equipment' => 'Agricultural Equipment',
        'classifieds' => 'General Agriculture'
    ];

    public function index(Request $request): JsonResponse
    {
        $category = $request->get('category', 'fruits_vegetables');
        $cacheKey = "market_prices_{$category}_" . md5(json_encode($request->query()));

        $payload = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($request, $category) {
            $prices = $this->getPricesByCategory($category);
            
            // Apply filters
            $prices = $this->applyFilters($prices, $request);

            // Transform to expected format
            $data = array_map(function ($row) {
                return [
                    'commodity' => $row['item'],
                    'market' => $row['market'],
                    'province' => $row['province'],
                    'price' => $row['price_usd'],
                    'unit' => $row['quantity'],
                    'trend' => 'stable',
                    'change' => 0,
                    'source' => $row['market'],
                    'category' => $row['category'],
                ];
            }, array_values($prices));

            return [
                'success' => true,
                'data' => $data,
                'meta' => [
                    'last_updated' => now()->toIso8601String(),
                    'sources' => $this->getSourcesForCategory($category),
                    'total_items' => count($data),
                    'category' => $category,
                    'note' => empty($data) ? 'No recent prices found for this category. Try again later.' : null,
                ],
            ];
        });

        return response()->json($payload);
    }

    public function getCategories(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'categories' => $this->categories,
        ]);
    }

    private function getPricesByCategory(string $category): array
    {
        if ($category === 'all') {
            return $this->getAllPrices();
        }

        try {
            $content = Storage::disk('local')->get("market_prices/latest_{$category}.json");
            return $content ? json_decode($content, true) : [];
        } catch (\Exception $e) {
            return [];
        }
    }

    private function getAllPrices(): array
    {
        try {
            $content = Storage::disk('local')->get('private/market_prices/latest_all_categories.json');
            return $content ? json_decode($content, true) : [];
        } catch (\Exception $e) {
            return [];
        }
    }

    private function applyFilters(array $prices, Request $request): array
    {
        if ($request->filled('commodity')) {
            $prices = array_filter($prices, fn($p) => 
                stripos($p['item'], $request->string('commodity')) !== false
            );
        }
        
        if ($request->filled('province')) {
            $prices = array_filter($prices, fn($p) => 
                $p['province'] === $request->string('province')
            );
        }
        
        if ($request->filled('min_price')) {
            $prices = array_filter($prices, fn($p) => 
                $p['price_usd'] >= (float)$request->input('min_price')
            );
        }
        
        if ($request->filled('max_price')) {
            $prices = array_filter($prices, fn($p) => 
                $p['price_usd'] <= (float)$request->input('max_price')
            );
        }

        return $prices;
    }

    private function getSourcesForCategory(string $category): array
    {
        if ($category === 'all') {
            return array_values($this->categories);
        }
        
        return isset($this->categories[$category]) ? [$this->categories[$category]] : [];
    }
}


