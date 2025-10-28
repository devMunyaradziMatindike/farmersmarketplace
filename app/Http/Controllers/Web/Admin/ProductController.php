<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(Request $request): Response
    {
        $query = Product::with(['user', 'category', 'photos']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by seller
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Sort
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $products = $query->paginate(20)->through(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => substr($product->description, 0, 100) . '...',
                'price' => number_format($product->price, 2),
                'status' => $product->status,
                'location' => $product->location,
                'seller' => [
                    'id' => $product->user->id,
                    'name' => $product->user->name,
                ],
                'category' => [
                    'id' => $product->category->id,
                    'name' => $product->category->name,
                ],
                'primary_photo' => $product->photos->where('is_primary', true)->first()?->photo_url ?? 
                    $product->photos->first()?->photo_url ?? 
                    '/images/placeholder.svg',
                'created_at' => $product->created_at->format('Y-m-d H:i:s'),
            ];
        });

        $stats = [
            'total' => Product::count(),
            'available' => Product::where('status', 'available')->count(),
            'sold_out' => Product::where('status', 'sold_out')->count(),
            'soon_available' => Product::where('status', 'soon_to_be_available')->count(),
            'delisted' => Product::where('status', 'delisted')->count(),
        ];

        $categories = Category::all(['id', 'name']);

        return Inertia::render('Admin/Products/Index', [
            'products' => $products,
            'stats' => $stats,
            'categories' => $categories,
            'filters' => [
                'search' => $request->search,
                'status' => $request->status,
                'category_id' => $request->category_id,
                'user_id' => $request->user_id,
                'sort' => $sortBy,
                'order' => $sortOrder,
            ],
        ]);
    }

    /**
     * Display the specified product.
     */
    public function show(Request $request, Product $product): Response
    {
        $product->load(['user', 'category', 'photos']);

        $productData = [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'price' => $product->price,
            'location' => $product->location,
            'latitude' => $product->latitude,
            'longitude' => $product->longitude,
            'contact_details' => $product->contact_details,
            'status' => $product->status,
            'date_listed' => $product->date_listed,
            'seller' => [
                'id' => $product->user->id,
                'name' => $product->user->name,
                'email' => $product->user->email,
                'phone_number' => $product->user->phone_number,
            ],
            'category' => [
                'id' => $product->category->id,
                'name' => $product->category->name,
            ],
            'photos' => $product->photos->map(function ($photo) {
                return [
                    'id' => $photo->id,
                    'photo_url' => $photo->photo_url,
                    'is_primary' => $photo->is_primary,
                    'order' => $photo->order,
                ];
            }),
            'created_at' => $product->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $product->updated_at->format('Y-m-d H:i:s'),
        ];

        return Inertia::render('Admin/Products/Show', [
            'product' => $productData,
        ]);
    }

    /**
     * Remove the specified product (for fraudulent listings).
     */
    public function destroy(Request $request, Product $product): RedirectResponse
    {
        // Delete product photos
        foreach ($product->photos as $photo) {
            Storage::disk('public')->delete($photo->photo_path);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product removed successfully.');
    }

    /**
     * Approve a product.
     */
    public function approve(Request $request, Product $product): RedirectResponse
    {
        $product->update(['status' => 'available']);
        return redirect()->back()->with('success', 'Product approved successfully.');
    }

    /**
     * Reject a product.
     */
    public function reject(Request $request, Product $product): RedirectResponse
    {
        $product->update(['status' => 'delisted']);
        return redirect()->back()->with('success', 'Product rejected successfully.');
    }
}





