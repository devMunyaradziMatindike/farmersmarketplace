<?php

namespace App\Http\Controllers\Web\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Display a listing of the seller's products.
     */
    public function index(Request $request)
    {
        $products = Product::with(['category', 'photos'])
            ->where('user_id', $request->user()->id)
            ->orderBy('date_listed', 'desc')
            ->paginate(15);

        $categories = Category::orderBy('name')->get();

        return Inertia::render('Seller/Products/Index', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return Inertia::render('Seller/Products/Create', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request)
    {
        // Check if request is too large before validation
        if ($request->header('content-length') > 50 * 1024 * 1024) { // 50MB
            return back()->withErrors(['photos' => 'Total upload size is too large. Please reduce the number or size of images.']);
        }

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|in:USD,ZWG',
            'location' => 'required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'contact_details' => 'required|string|max:255',
            'status' => 'required|in:available,sold_out,soon_to_be_available,delisted',
            'photos' => 'nullable|array|max:10',
            'photos.*' => 'image|mimes:jpeg,jpg,png,gif,webp|max:10240', // Increased to 10MB per file
        ]);

        $product = Product::create([
            'user_id' => $request->user()->id,
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'currency' => $request->currency,
            'location' => $request->location,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'contact_details' => $request->contact_details,
            'status' => $request->status,
            'date_listed' => now(),
        ]);

        // Handle photo uploads with better error handling
        if ($request->hasFile('photos')) {
            try {
                foreach ($request->file('photos') as $index => $photo) {
                    if ($photo->isValid()) {
                        $path = $photo->store('products', 'public');

                        ProductPhoto::create([
                            'product_id' => $product->id,
                            'photo_path' => $path,
                            'is_primary' => $index === 0,
                            'order' => $index,
                        ]);
                    } else {
                        \Log::warning('Invalid photo upload', [
                            'product_id' => $product->id,
                            'photo_index' => $index,
                            'error' => $photo->getError()
                        ]);
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Photo upload failed', [
                    'product_id' => $product->id,
                    'error' => $e->getMessage()
                ]);
                
                // Delete the product if photo upload fails
                $product->delete();
                return back()->withErrors(['photos' => 'Failed to upload photos. Please try again with smaller files.']);
            }
        }

        return redirect()->route('seller.products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified product.
     */
    public function show(Request $request, Product $product)
    {
        // Ensure the product belongs to the authenticated seller
        if ($product->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized. This product does not belong to you.');
        }

        $product->load(['category', 'photos']);

        return Inertia::render('Seller/Products/Show', [
            'product' => $product,
        ]);
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Request $request, Product $product)
    {
        // Ensure the product belongs to the authenticated seller
        if ($product->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized. This product does not belong to you.');
        }

        $product->load(['category', 'photos']);
        $categories = Category::orderBy('name')->get();

        return Inertia::render('Seller/Products/Edit', [
            'product' => $product,
            'categories' => $categories,
        ]);
    }

    /**
     * Update the specified product.
     */
    public function update(Request $request, Product $product)
    {
        // Add debug logging
        \Log::info('Product update request', [
            'product_id' => $product->id,
            'product_name_before' => $product->name,
            'request_data' => $request->all(),
            'user_id' => $request->user()->id,
            'product_user_id' => $product->user_id
        ]);

        // Ensure the product belongs to the authenticated seller
        if ($product->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized. This product does not belong to you.');
        }

        try {
            // If request is photos only, validate photos and skip main fields
            if ($request->hasFile('photos') && !$request->hasAny([
                'category_id','name','description','price','currency','location','latitude','longitude','contact_details','status'
            ])) {
                $request->validate([
                    'photos' => 'required|array|max:10',
                    'photos.*' => 'image|mimes:jpeg,jpg,png,gif,webp|max:10240',
                ]);
            } else {
                // Partial update: validate only provided fields
                $request->validate([
                    'category_id' => 'sometimes|required|exists:categories,id',
                    'name' => 'sometimes|required|string|max:255',
                    'description' => 'sometimes|required|string',
                    'price' => 'sometimes|required|numeric|min:0',
                    'currency' => 'sometimes|required|in:USD,ZWG',
                    'location' => 'sometimes|required|string|max:255',
                    'latitude' => 'nullable|numeric|between:-90,90',
                    'longitude' => 'nullable|numeric|between:-180,180',
                    'contact_details' => 'sometimes|required|string|max:255',
                    'status' => 'sometimes|required|in:available,sold_out,soon_to_be_available,delisted',
                    'photos' => 'nullable|array|max:10',
                    'photos.*' => 'image|mimes:jpeg,jpg,png,gif,webp|max:10240',
                ]);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Product update validation failed:', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            throw $e;
        }

        // Only update attributes that were actually provided
        $updatable = ['category_id','name','description','price','currency','location','latitude','longitude','contact_details','status'];
        $updateData = array_filter(
            $request->only($updatable),
            fn ($v) => !is_null($v)
        );

        $result = true;
        if (!empty($updateData)) {
            \Log::info('Attempting to update product with data:', $updateData);
            $result = $product->update($updateData);
        }

        \Log::info('Product update result:', [
            'update_successful' => $result,
            'product_name_after' => $product->fresh()->name,
            'product_updated_at' => $product->fresh()->updated_at
        ]);

        // Handle new photo uploads with better error handling
        if ($request->hasFile('photos')) {
            try {
                $currentPhotoCount = $product->photos()->count();
                $maxOrder = $product->photos()->max('order') ?? -1;

                // By default, when new photos are uploaded, make the first one primary
                // and demote any existing primary photo.
                if ($currentPhotoCount > 0) {
                    $product->photos()->where('is_primary', true)->update(['is_primary' => false]);
                }

                foreach ($request->file('photos') as $index => $photo) {
                    if ($photo->isValid()) {
                        $path = $photo->store('products', 'public');

                        ProductPhoto::create([
                            'product_id' => $product->id,
                            'photo_path' => $path,
                            'is_primary' => $index === 0, // first newly uploaded becomes primary
                            'order' => $maxOrder + $index + 1,
                        ]);
                    } else {
                        \Log::warning('Invalid photo upload during update', [
                            'product_id' => $product->id,
                            'photo_index' => $index,
                            'error' => $photo->getError()
                        ]);
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Photo upload failed during update', [
                    'product_id' => $product->id,
                    'error' => $e->getMessage()
                ]);
                
                return back()->withErrors(['photos' => 'Failed to upload photos. Please try again with smaller files.']);
            }
        }

        return redirect()->route('seller.products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product.
     */
    public function destroy(Request $request, Product $product)
    {
        // Ensure the product belongs to the authenticated seller
        if ($product->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized. This product does not belong to you.');
        }

        // Delete associated photos from storage
        foreach ($product->photos as $photo) {
            Storage::disk('public')->delete($photo->photo_path);
        }

        $product->delete();

        return redirect()->route('seller.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    /**
     * Update the product status.
     */
    public function updateStatus(Request $request, Product $product)
    {
        // Ensure the product belongs to the authenticated seller
        if ($product->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized. This product does not belong to you.');
        }

        $request->validate([
            'status' => 'required|in:available,sold_out,soon_to_be_available,delisted',
        ]);

        $product->update(['status' => $request->status]);

        return redirect()->back()
            ->with('success', 'Product status updated successfully.');
    }

    /**
     * Delete a specific photo from the product and reassign primary if needed.
     */
    public function deletePhoto(Request $request, Product $product, ProductPhoto $photo)
    {
        // Ensure the product belongs to the authenticated seller
        if ($product->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized. This product does not belong to you.');
        }

        // Ensure the photo belongs to the product
        if ($photo->product_id !== $product->id) {
            abort(404, 'Photo not found for this product.');
        }

        $wasPrimary = (bool) $photo->is_primary;

        // Delete file from storage (ignore if missing)
        Storage::disk('public')->delete($photo->photo_path);

        // Delete DB record
        $photo->delete();

        // If it was primary, promote the next one
        if ($wasPrimary) {
            $next = $product->photos()->orderBy('order')->first();
            if ($next) {
                $next->update(['is_primary' => true]);
            }
        }

        return redirect()->back()->with('success', 'Photo removed successfully.');
    }
}
