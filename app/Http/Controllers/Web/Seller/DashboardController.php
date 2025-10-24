<?php

namespace App\Http\Controllers\Web\Seller;

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
     * Display the seller dashboard.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Get seller statistics
        $stats = [
            'total_products' => Product::where('user_id', $user->id)->count(),
            'available_products' => Product::where('user_id', $user->id)->where('status', 'available')->count(),
            'sold_products' => Product::where('user_id', $user->id)->where('status', 'sold_out')->count(),
            'total_views' => 0, // This would need to be implemented with a views table
        ];

        // Get recent products (last 5)
        $recentProducts = Product::with(['category', 'photos'])
            ->where('user_id', $user->id)
            ->orderBy('date_listed', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => number_format($product->price, 2),
                    'status' => $product->status,
                    'photos' => $product->photos->map(fn ($photo) => [
                        'photo_url' => $photo->photo_url,
                    ]),
                ];
            });

        // Get categories for overview
        $categories = Category::withCount('products')->get();

        return Inertia::render('Seller/Dashboard', [
            'user' => $user,
            'stats' => $stats,
            'recent_products' => $recentProducts,
            'categories' => $categories,
        ]);
    }

    /**
     * Display analytics page.
     */
    public function analytics(Request $request)
    {
        $user = $request->user();
        
        $stats = [
            'total_products' => Product::where('user_id', $user->id)->count(),
            'available_products' => Product::where('user_id', $user->id)->where('status', 'available')->count(),
            'sold_products' => Product::where('user_id', $user->id)->where('status', 'sold_out')->count(),
            'total_views' => 0,
            'monthly_views' => 0,
            'weekly_views' => 0,
            'top_categories' => [],
            'recent_activity' => []
        ];

        $products = Product::with(['category', 'photos'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return Inertia::render('Seller/Analytics', [
            'user' => $user,
            'stats' => $stats,
            'products' => $products,
        ]);
    }

    /**
     * Display orders page.
     */
    public function orders(Request $request)
    {
        $user = $request->user();
        
        $stats = [
            'total_orders' => 0,
            'pending_orders' => 0,
            'completed_orders' => 0,
            'total_revenue' => 0
        ];

        $orders = [
            'data' => [],
            'links' => []
        ];

        return Inertia::render('Seller/Orders', [
            'user' => $user,
            'stats' => $stats,
            'orders' => $orders,
        ]);
    }

    /**
     * Display messages page.
     */
    public function messages(Request $request)
    {
        $user = $request->user();
        
        $stats = [
            'total_conversations' => 0,
            'unread_messages' => 0,
            'active_conversations' => 0
        ];

        $conversations = [
            'data' => [],
            'links' => []
        ];

        return Inertia::render('Seller/Messages', [
            'user' => $user,
            'stats' => $stats,
            'conversations' => $conversations,
        ]);
    }

    /**
     * Display profile page.
     */
    public function profile(Request $request)
    {
        $user = $request->user();
        
        $stats = [
            'total_products' => Product::where('user_id', $user->id)->count(),
            'total_views' => 0,
            'total_sales' => 0,
            'rating' => 4.5,
            'reviews_count' => 0
        ];

        $recentProducts = Product::with(['category', 'photos'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => number_format($product->price, 2),
                    'status' => $product->status,
                    'photos' => $product->photos->map(fn ($photo) => [
                        'photo_url' => $photo->photo_url,
                    ]),
                ];
            });

        return Inertia::render('Seller/Profile', [
            'user' => $user,
            'stats' => $stats,
            'recent_products' => $recentProducts,
        ]);
    }

    /**
     * Display settings page.
     */
    public function settings(Request $request)
    {
        $user = $request->user();

        return Inertia::render('Seller/Settings', [
            'user' => $user,
        ]);
    }

    /**
     * Update profile.
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();
        
        $request->validate([
            'business_name' => 'nullable|string|max:255',
            'business_description' => 'nullable|string|max:1000',
            'location' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'contact_details' => 'nullable|string|max:255',
            'social_media' => 'nullable|array',
        ]);

        $user->update($request->only([
            'business_name',
            'business_description', 
            'location',
            'website',
            'contact_details',
            'social_media'
        ]));

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Upload profile photo.
     */
    public function uploadPhoto(Request $request)
    {
        $user = $request->user();
        
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
        ]);

        if ($user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        $path = $request->file('profile_photo')->store('profiles', 'public');
        $user->update(['profile_photo' => $path]);

        return redirect()->back()->with('success', 'Profile photo updated successfully.');
    }

    /**
     * Update settings.
     */
    public function updateSettings(Request $request)
    {
        $user = $request->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone_number' => 'required|string|max:20',
            'contact_details' => 'nullable|string|max:255',
        ]);

        $user->update($request->only([
            'name',
            'email',
            'phone_number',
            'contact_details'
        ]));

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
