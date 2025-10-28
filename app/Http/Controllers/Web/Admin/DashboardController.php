<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index(Request $request): Response
    {
        // Get statistics
        $stats = [
            'total_users' => User::count(),
            'total_buyers' => User::where('role', 'buyer')->count(),
            'total_sellers' => User::where('role', 'seller')->count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'total_products' => Product::count(),
            'available_products' => Product::where('status', 'available')->count(),
            'sold_products' => Product::where('status', 'sold_out')->count(),
            'pending_products' => Product::where('status', 'soon_to_be_available')->count(),
            'total_categories' => Category::count(),
        ];

        // Recent users (last 10)
        $recentUsers = User::orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone_number' => $user->phone_number,
                    'role' => $user->role,
                    'created_at' => $user->created_at->format('Y-m-d H:i:s'),
                ];
            });

        // Recent products (last 10)
        $recentProducts = Product::with(['user', 'category'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => number_format($product->price, 2),
                    'status' => $product->status,
                    'seller' => $product->user->name,
                    'category' => $product->category->name,
                    'created_at' => $product->created_at->format('Y-m-d H:i:s'),
                ];
            });

        // Category statistics
        $categoryStats = Category::withCount('products')
            ->orderBy('products_count', 'desc')
            ->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'products_count' => $category->products_count,
                ];
            });

        // User growth (last 30 days)
        $userGrowth = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $count = User::whereDate('created_at', $date)->count();
            $userGrowth[] = [
                'date' => $date,
                'count' => $count,
            ];
        }

        // Product growth (last 30 days)
        $productGrowth = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $count = Product::whereDate('created_at', $date)->count();
            $productGrowth[] = [
                'date' => $date,
                'count' => $count,
            ];
        }

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
            'recentUsers' => $recentUsers,
            'recentProducts' => $recentProducts,
            'categoryStats' => $categoryStats,
            'userGrowth' => $userGrowth,
            'productGrowth' => $productGrowth,
        ]);
    }
}





