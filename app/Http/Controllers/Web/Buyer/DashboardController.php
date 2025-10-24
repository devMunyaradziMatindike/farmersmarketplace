<?php

namespace App\Http\Controllers\Web\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the buyer dashboard.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Get featured products (recently listed)
        $featuredProducts = Product::with(['category', 'photos', 'user'])
            ->where('status', 'available')
            ->orderBy('date_listed', 'desc')
            ->limit(8)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => number_format($product->price, 2),
                    'description' => $product->description,
                    'location' => $product->location,
                    'status' => $product->status,
                    'category' => $product->category->name,
                    'seller' => $product->user->name,
                    'photos' => $product->photos->map(fn ($photo) => [
                        'photo_url' => $photo->photo_url,
                    ]),
                ];
            });

        // Get categories with product counts
        $categories = Category::withCount('products')
            ->having('products_count', '>', 0)
            ->orderBy('products_count', 'desc')
            ->limit(6)
            ->get();

        // Get buyer statistics (placeholder for future features)
        $stats = [
            'favorites_count' => 0, // Will be implemented with favorites feature
            'orders_count' => 0,    // Will be implemented with orders feature
            'messages_count' => 0,   // Will be implemented with messaging feature
            'recent_views' => 0,    // Will be implemented with view tracking
        ];

        return Inertia::render('Buyer/Dashboard', [
            'user' => $user,
            'featured_products' => $featuredProducts,
            'categories' => $categories,
            'stats' => $stats,
        ]);
    }

    /**
     * Display buyer profile page.
     */
    public function profile(Request $request)
    {
        $user = $request->user();
        
        $stats = [
            'favorites_count' => 0,
            'orders_count' => 0,
            'messages_count' => 0,
            'recent_views' => 0,
        ];

        return Inertia::render('Buyer/Profile', [
            'user' => $user,
            'stats' => $stats,
        ]);
    }

    /**
     * Display buyer settings page.
     */
    public function settings(Request $request)
    {
        $user = $request->user();

        return Inertia::render('Buyer/Settings', [
            'user' => $user,
        ]);
    }

    /**
     * Update buyer profile.
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'preferences' => 'nullable|array',
        ]);

        $user->update($request->only([
            'name',
            'email',
            'phone_number',
            'location',
            'preferences'
        ]));

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Update buyer settings.
     */
    public function updateSettings(Request $request)
    {
        $user = $request->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'notification_preferences' => 'nullable|array',
        ]);

        $user->update($request->only([
            'name',
            'email',
            'phone_number',
            'location',
            'notification_preferences'
        ]));

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}